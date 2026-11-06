<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function changements_possibles() {
    return ['En attente' => ['Validée', 'Refusée'], 'Validée' => ['Expédiée', 'Refusée'],
            'Expédiée' => [], 'Refusée' => []];
}

function commandes_gestion($bdd, $statut = '', $id = null) {
    $sql = 'SELECT c.id_commande, c.date_commande, c.statut, u.nom, u.prenom,
                   COALESCE(SUM(l.quantite * l.prix_unitaire), 0) AS montant
            FROM commande c JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur
            LEFT JOIN ligne_commande l ON l.id_commande = c.id_commande';
    $params = [];
    if ($id !== null) { $sql .= ' WHERE c.id_commande = ?'; $params[] = $id; }
    elseif ($statut !== '') { $sql .= ' WHERE c.statut = ?'; $params[] = $statut; }
    $sql .= ' GROUP BY c.id_commande, c.date_commande, c.statut, u.nom, u.prenom
              ORDER BY c.date_commande DESC, c.id_commande DESC';
    $exec = $bdd->prepare($sql);
    $exec->execute($params);
    return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function lignes_gestion($bdd, $id) {
    $exec = $bdd->prepare("SELECT COALESCE(p.nom, 'Produit indisponible') AS nom, l.quantite, l.prix_unitaire
        FROM ligne_commande l LEFT JOIN produit p ON p.id_produit = l.id_produit
        WHERE l.id_commande = ? ORDER BY l.id_produit");
    $exec->execute([$id]);
    return $exec->fetchAll(PDO::FETCH_ASSOC);
}

function changer_statut_commande($bdd, $id, $ancien, $nouveau) {
    if (!is_string($ancien) || !in_array($nouveau, changements_possibles()[$ancien] ?? [], true)) {
        return false;
    }
    // Si un autre gestionnaire a déjà traité la commande, conserver sa décision.
    $exec = $bdd->prepare('UPDATE commande SET statut = ? WHERE id_commande = ? AND statut = ?');
    $exec->execute([$nouveau, $id, $ancien]);
    return $exec->rowCount() === 1;
}
