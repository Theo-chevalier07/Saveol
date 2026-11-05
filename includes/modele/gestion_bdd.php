<?php

function verifier_identification($email, $mdp) {
    require "connexion.php";
    $sql = "select id_utilisateur, role from utilisateur where email = ? and mot_de_passe = ?";
    $exec = $bdd->prepare($sql);
    $data =[$email, hash('sha256', $mdp)];
    $trouver = false;
    $exec->execute($data);
    $ligne = $exec->fetch();
    if($exec->rowCount() > 0) {
        $trouver = true;
        $_SESSION['id'] = $ligne['id_utilisateur'];
        $_SESSION['role'] = $ligne['role'];
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