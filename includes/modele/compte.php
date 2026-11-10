<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function client_du_compte() {
    $id = $_SESSION['id'] ?? null;
    if (($_SESSION['role'] ?? '') !== 'client' || (!is_int($id) && !is_string($id))
        || filter_var($id, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false) {
        throw new DomainException('Cet espace est réservé aux clients connectés.');
    }
    return (int) $id;
}

function consulter_compte() {
    global $bdd;
    $id = client_du_compte();
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    $exec = $bdd->prepare("SELECT id_utilisateur, nom, prenom, email, telephone, adresse FROM utilisateur WHERE id_utilisateur = ? AND role = 'client'");
    $exec->execute([$id]);
    $compte = $exec->fetch(PDO::FETCH_ASSOC);
    if (!$compte) { throw new DomainException('Votre compte client n’est plus disponible.'); }
    return $compte;
}

function modifier_compte($donnees) {
    global $bdd;
    $compte = consulter_compte();
    $champs = ['nom'=>50, 'prenom'=>50, 'email'=>100, 'telephone'=>20, 'adresse'=>255];
    $valeurs = [];
    foreach ($champs as $champ => $maximum) {
        $valeur = $donnees[$champ] ?? '';
        if (!is_string($valeur)) { throw new DomainException('Veuillez saisir des informations valides.'); }
        $valeur = trim($valeur);
        // Les limites correspondent aux colonnes MySQL, en caractères et non en octets.
        if (!preg_match('/^.{0,' . $maximum . '}$/usD', $valeur) || strpos($valeur, "\0") !== false) {
            throw new DomainException('Le champ ' . $champ . ' doit contenir au maximum ' . $maximum . ' caractères.');
        }
        $valeurs[$champ] = $valeur;
    }
    if ($valeurs['nom'] === '' || $valeurs['prenom'] === '' || !filter_var($valeurs['email'], FILTER_VALIDATE_EMAIL)) {
        throw new DomainException('Renseignez votre nom, votre prénom et une adresse email valide.');
    }
    $exec = $bdd->prepare('SELECT id_utilisateur FROM utilisateur WHERE email = ? AND id_utilisateur <> ?');
    $exec->execute([$valeurs['email'], $compte['id_utilisateur']]);
    if ($exec->fetchColumn()) { throw new DomainException('Cette adresse email est déjà utilisée.'); }
    $exec = $bdd->prepare("UPDATE utilisateur SET nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? WHERE id_utilisateur = ? AND role = 'client'");
    try {
        $exec->execute([
            $valeurs['nom'], $valeurs['prenom'], $valeurs['email'],
            $valeurs['telephone'] === '' ? null : $valeurs['telephone'],
            $valeurs['adresse'] === '' ? null : $valeurs['adresse'], $compte['id_utilisateur']
        ]);
    } catch (PDOException $e) {
        // La contrainte UNIQUE couvre aussi deux changements d'email simultanés.
        if ($e->getCode() === '23000') { throw new DomainException('Cette adresse email est déjà utilisée.'); }
        throw $e;
    }
    $_SESSION['nom'] = $valeurs['nom'];
    $_SESSION['prenom'] = $valeurs['prenom'];
}

function historique_commandes() {
    global $bdd;
    $compte = consulter_compte();
    $sql = 'SELECT c.id_commande, c.date_commande, c.statut, COALESCE(SUM(l.quantite * l.prix_unitaire), 0) AS montant
            FROM commande c LEFT JOIN ligne_commande l ON l.id_commande = c.id_commande
            WHERE c.id_utilisateur = ?
            GROUP BY c.id_commande, c.date_commande, c.statut
            ORDER BY c.date_commande DESC, c.id_commande DESC';
    $exec = $bdd->prepare($sql);
    $exec->execute([$compte['id_utilisateur']]);
    return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function detail_commande_client($idCommande) {
    global $bdd;
    $compte = consulter_compte();
    if ((!is_string($idCommande) && !is_int($idCommande))
        || filter_var($idCommande, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false) { return false; }
    // Vérifier le propriétaire dans la requête, pas uniquement dans les liens affichés.
    $exec = $bdd->prepare('SELECT c.id_commande, c.date_commande, c.statut, COALESCE(SUM(l.quantite * l.prix_unitaire), 0) AS montant
        FROM commande c LEFT JOIN ligne_commande l ON l.id_commande = c.id_commande
        WHERE c.id_commande = ? AND c.id_utilisateur = ?
        GROUP BY c.id_commande, c.date_commande, c.statut');
    $exec->execute([(int) $idCommande, $compte['id_utilisateur']]);
    $commande = $exec->fetch(PDO::FETCH_ASSOC);
    if (!$commande) { return false; }
    $exec = $bdd->prepare("SELECT COALESCE(p.nom, 'Produit indisponible') AS nom, p.conditionnement,
        l.quantite, l.prix_unitaire, l.quantite * l.prix_unitaire AS montant
        FROM ligne_commande l LEFT JOIN produit p ON p.id_produit = l.id_produit
        WHERE l.id_commande = ? ORDER BY l.id_produit");
    $exec->execute([(int) $idCommande]);
    return ['commande'=>$commande, 'lignes'=>$exec->fetchAll(PDO::FETCH_ASSOC)];
}
