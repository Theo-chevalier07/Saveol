<?php

/* ===== CONNEXION ===== */

function verifier_identification($email, $mdp) {
    require "connexion.php";
    $sql = "select id_utilisateur, nom, prenom, role from utilisateur where email = ? and mot_de_passe = ?";
    $exec = $bdd->prepare($sql);
    // Le mot de passe est haché en sha256 avant d'être comparé
    $data = [$email, hash('sha256', $mdp)];
    $trouver = false;
    $exec->execute($data);
    $ligne = $exec->fetch();
    if ($ligne != false) {
        $trouver = true;
        $_SESSION['id'] = $ligne['id_utilisateur'];
        $_SESSION['role'] = $ligne['role'];
        $_SESSION['nom'] = $ligne['nom'];
        $_SESSION['prenom'] = $ligne['prenom'];
    }
    return $trouver;
}

function produitsEnAvant(){
    require "connexion.php";
    $sql = "select nom, description, prix, image from produit where mis_en_avant = 1";
    $exec = $bdd->prepare($sql);
    $exec->execute();
    return $exec->fetchAll();
}

/* ===== PRODUITS ===== */

function getProduit($id) {
    require "connexion.php";
    $sql = "select * from produit where id_produit = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
    return $exec->fetch();
}

function getTousLesProduits() {
    require "connexion.php";
    $sql = "select p.*, c.libelle from produit p join categorie c on c.id_categorie = p.id_categorie order by c.libelle, p.nom";
    $exec = $bdd->prepare($sql);
    $exec->execute();
    return $exec->fetchAll();
}

/* ===== PANIER ET COMMANDE ===== */

function creerCommande($idUtilisateur, $panier) {
    require "connexion.php";
    // Soit tout est enregistré, soit rien en cas d'erreur
    $bdd->beginTransaction();
    try {
        $sql = "insert into commande (statut, id_utilisateur) values ('En attente', ?)";
        $exec = $bdd->prepare($sql);
        $exec->execute([$idUtilisateur]);
        $idCommande = $bdd->lastInsertId();

        $sql = "insert into ligne_commande (id_commande, id_produit, quantite, prix_unitaire) values (?, ?, ?, ?)";
        $exec = $bdd->prepare($sql);
        foreach ($panier as $idProduit => $quantite) {
            // On garde le prix du jour dans la ligne
            $produit = getProduit($idProduit);
            $exec->execute([$idCommande, $idProduit, $quantite, $produit['prix']]);
        }
        $bdd->commit();
    }
    catch (Exception $e) {
        $bdd->rollBack();
        $idCommande = 0;
    }
    return $idCommande;
}

/* ===== MON COMPTE ===== */

function getUtilisateur($id) {
    require "connexion.php";
    $sql = "select * from utilisateur where id_utilisateur = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
    return $exec->fetch();
}

function emailDejaUtilise($email, $id) {
    require "connexion.php";
    $sql = "select count(*) from utilisateur where email = ? and id_utilisateur != ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$email, $id]);
    return $exec->fetchColumn() > 0;
}

function modifierUtilisateur($id, $nom, $prenom, $email, $telephone, $adresse) {
    require "connexion.php";
    $sql = "update utilisateur set nom = ?, prenom = ?, email = ?, telephone = ?, adresse = ? where id_utilisateur = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$nom, $prenom, $email, $telephone, $adresse, $id]);
}

function getCommandesClient($idUtilisateur) {
    require "connexion.php";
    $sql = "select c.id_commande, c.date_commande, c.statut, sum(l.quantite * l.prix_unitaire) as montant
            from commande c join ligne_commande l on l.id_commande = c.id_commande
            where c.id_utilisateur = ?
            group by c.id_commande
            order by c.date_commande desc";
    $exec = $bdd->prepare($sql);
    $exec->execute([$idUtilisateur]);
    return $exec->fetchAll();
}

/* ===== COMMANDES ===== */

function getCommande($idCommande) {
    require "connexion.php";
    $sql = "select c.*, u.nom, u.prenom, u.email, u.telephone, u.adresse, sum(l.quantite * l.prix_unitaire) as montant
            from commande c
            join utilisateur u on u.id_utilisateur = c.id_utilisateur
            join ligne_commande l on l.id_commande = c.id_commande
            where c.id_commande = ?
            group by c.id_commande";
    $exec = $bdd->prepare($sql);
    $exec->execute([$idCommande]);
    return $exec->fetch();
}

function getLignesCommande($idCommande) {
    require "connexion.php";
    $sql = "select p.nom, p.image, p.conditionnement, l.quantite, l.prix_unitaire, l.quantite * l.prix_unitaire as sous_total
            from ligne_commande l join produit p on p.id_produit = l.id_produit
            where l.id_commande = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$idCommande]);
    return $exec->fetchAll();
}

function getToutesLesCommandes($statut) {
    require "connexion.php";
    $sql = "select c.id_commande, c.date_commande, c.statut, u.nom, u.prenom, sum(l.quantite * l.prix_unitaire) as montant
            from commande c
            join utilisateur u on u.id_utilisateur = c.id_utilisateur
            join ligne_commande l on l.id_commande = c.id_commande";
    $data = [];
    // Filtre par statut si on en a choisi un
    if ($statut != '') {
        $sql = $sql . " where c.statut = ?";
        $data = [$statut];
    }
    $sql = $sql . " group by c.id_commande order by c.date_commande desc";
    $exec = $bdd->prepare($sql);
    $exec->execute($data);
    return $exec->fetchAll();
}

function getStatutsSuivants($statut) {
    $suivants = array(
        'En attente' => array('Validée', 'Refusée'),
        'Validée' => array('Expédiée', 'Refusée'),
        'Expédiée' => array('Terminée'),
        'Terminée' => array(),
        'Refusée' => array()
    );
    return $suivants[$statut];
}

function modifierStatutCommande($idCommande, $statut) {
    require "connexion.php";
    $sql = "update commande set statut = ? where id_commande = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$statut, $idCommande]);
}

/* ===== GESTION DU CATALOGUE ===== */

function ajouterProduit($nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie) {
    require "connexion.php";
    $sql = "insert into produit (nom, description, prix, conditionnement, image, mis_en_avant, id_categorie) values (?, ?, ?, ?, ?, ?, ?)";
    $exec = $bdd->prepare($sql);
    $exec->execute([$nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie]);
}

function modifierProduit($id, $nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie) {
    require "connexion.php";
    $sql = "update produit set nom = ?, description = ?, prix = ?, conditionnement = ?, image = ?, mis_en_avant = ?, id_categorie = ? where id_produit = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$nom, $description, $prix, $conditionnement, $image, $misEnAvant, $idCategorie, $id]);
}

function produitDansUneCommande($id) {
    require "connexion.php";
    $sql = "select count(*) from ligne_commande where id_produit = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
    return $exec->fetchColumn() > 0;
}

function supprimerProduit($id) {
    require "connexion.php";
    $sql = "delete from produit where id_produit = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
}

function changerMiseEnAvant($id) {
    require "connexion.php";
    // On inverse la valeur : 0 devient 1 et 1 devient 0
    $sql = "update produit set mis_en_avant = 1 - mis_en_avant where id_produit = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
}

/* ===== CATEGORIES ===== */

function getCategories() {
    require "connexion.php";
    $sql = "select c.id_categorie, c.libelle, count(p.id_produit) as nb_produits
            from categorie c left join produit p on p.id_categorie = c.id_categorie
            group by c.id_categorie
            order by c.libelle";
    $exec = $bdd->prepare($sql);
    $exec->execute();
    return $exec->fetchAll();
}

function getCategorie($id) {
    require "connexion.php";
    $sql = "select * from categorie where id_categorie = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
    return $exec->fetch();
}

function ajouterCategorie($libelle) {
    require "connexion.php";
    $sql = "insert into categorie (libelle) values (?)";
    $exec = $bdd->prepare($sql);
    $exec->execute([$libelle]);
}

function modifierCategorie($id, $libelle) {
    require "connexion.php";
    $sql = "update categorie set libelle = ? where id_categorie = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$libelle, $id]);
}

function supprimerCategorie($id) {
    require "connexion.php";
    $sql = "delete from categorie where id_categorie = ?";
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
}
?>
