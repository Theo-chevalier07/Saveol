<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function entier_panier($valeur, $maximum = PHP_INT_MAX) {
    if ((!is_string($valeur) && !is_int($valeur))
        || filter_var($valeur, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => $maximum]]) === false) {
        throw new DomainException('Le produit et la quantité doivent être des nombres entiers positifs (quantité : 1 à 9999).');
    }
    return (int) $valeur;
}

function client_du_panier() {
    if (($_SESSION['role'] ?? '') !== 'client' || empty($_SESSION['id'])) {
        throw new DomainException('Connectez-vous avec un compte client pour commander.');
    }
    return entier_panier($_SESSION['id']);
}

function prix_en_centimes($prix) {
    if ($prix === null || !preg_match('/^([0-9]{1,6})(?:\.([0-9]{1,2}))?$/', (string) $prix, $parties)) {
        return null;
    }
    // Le calcul du total utilise des entiers pour éviter les erreurs d'arrondi.
    return (int) $parties[1] * 100 + (int) str_pad($parties[2] ?? '', 2, '0');
}

function produits_a_ajouter() {
    global $bdd;
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    return $bdd->query('SELECT id_produit, nom, prix, conditionnement FROM produit ORDER BY nom')->fetchAll(PDO::FETCH_ASSOC);
}

function ajouter_au_panier($id, $quantite) {
    global $bdd;
    client_du_panier();
    $id = entier_panier($id);
    $quantite = entier_panier($quantite, 9999);
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    $exec = $bdd->prepare('SELECT prix FROM produit WHERE id_produit = ?');
    $exec->execute([$id]);
    $produit = $exec->fetch(PDO::FETCH_ASSOC);
    if (!$produit) { throw new DomainException('Ce produit n’est plus disponible.'); }
    if (prix_en_centimes($produit['prix']) === null) {
        throw new DomainException('Ce produit est sur devis. Contactez Savéol pour le commander.');
    }
    $nouvelleQuantite = ($_SESSION['panier'][$id] ?? 0) + $quantite;
    $_SESSION['panier'][$id] = entier_panier($nouvelleQuantite, 9999);
}

function modifier_quantite_panier($id, $quantite) {
    client_du_panier();
    $id = entier_panier($id);
    $quantite = entier_panier($quantite, 9999);
    if (!isset($_SESSION['panier'][$id])) { throw new DomainException('Ce produit n’est pas dans votre panier.'); }
    $_SESSION['panier'][$id] = $quantite;
}

function supprimer_du_panier($id) {
    client_du_panier();
    $id = entier_panier($id);
    unset($_SESSION['panier'][$id]);
}

function recapitulatif_panier() {
    global $bdd;
    client_du_panier();
    $panier = $_SESSION['panier'] ?? [];
    $resultat = ['lignes' => [], 'total' => 0, 'valide' => !empty($panier)];
    if (!$panier) { return $resultat; }
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    $ids = array_map('entier_panier', array_keys($panier));
    $parametres = implode(',', array_fill(0, count($ids), '?'));
    $exec = $bdd->prepare('SELECT id_produit, nom, prix, conditionnement FROM produit WHERE id_produit IN (' . $parametres . ')');
    $exec->execute($ids);
    $produits = [];
    foreach ($exec->fetchAll(PDO::FETCH_ASSOC) as $produit) { $produits[$produit['id_produit']] = $produit; }
    foreach ($panier as $id => $quantite) {
        $quantite = entier_panier($quantite, 9999);
        $produit = $produits[$id] ?? null;
        $prix = $produit ? prix_en_centimes($produit['prix']) : null;
        $disponible = $produit !== null && $prix !== null;
        if (!$disponible) { $resultat['valide'] = false; }
        $total = $disponible ? $prix * $quantite : 0;
        $resultat['lignes'][] = [
            'id_produit' => (int) $id,
            'nom' => $produit['nom'] ?? 'Produit indisponible',
            'conditionnement' => $produit['conditionnement'] ?? '',
            'quantite' => $quantite, 'prix' => $prix,
            'total' => $total, 'disponible' => $disponible
        ];
        $resultat['total'] += $total;
    }
    return $resultat;
}

function valider_commande() {
    global $bdd;
    $idClient = client_du_panier();
    if (empty($_SESSION['panier'])) { throw new DomainException('Votre panier est vide.'); }
    if (!isset($bdd)) { require __DIR__ . '/connexion.php'; }
    // Commande et lignes sont enregistrées ensemble, ou rien n'est enregistré.
    $bdd->beginTransaction();
    try {
        $exec = $bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE id_utilisateur = ? AND role = 'client'");
        $exec->execute([$idClient]);
        if (!$exec->fetchColumn()) { throw new DomainException('Votre compte client n’est plus disponible.'); }
        $recap = recapitulatif_panier();
        if (!$recap['valide']) { throw new DomainException('Un produit est indisponible ou sur devis. Retirez-le avant de valider.'); }
        $exec = $bdd->prepare('INSERT INTO commande (id_utilisateur, statut) VALUES (?, ?)');
        $exec->execute([$idClient, 'En attente']);
        $idCommande = (int) $bdd->lastInsertId();
        $exec = $bdd->prepare('INSERT INTO ligne_commande (id_commande, id_produit, quantite, prix_unitaire) VALUES (?, ?, ?, ?)');
        foreach ($recap['lignes'] as $ligne) {
            $prix = intdiv($ligne['prix'], 100) . '.' . str_pad((string) ($ligne['prix'] % 100), 2, '0', STR_PAD_LEFT);
            $exec->execute([$idCommande, $ligne['id_produit'], $ligne['quantite'], $prix]);
        }
        $bdd->commit();
    } catch (Throwable $e) {
        if ($bdd->inTransaction()) { $bdd->rollBack(); }
        throw $e;
    }
    $_SESSION['panier'] = [];
    return $idCommande;
}
