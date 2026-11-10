<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function ajouterUtilisateur($bdd, $nom, $prenom, $email, $mdp, $adresse, $telephone) {
    // Hachage du mot de passe pour la sécurité
    $mdpHash = hash('sha256', $mdp);
    
    $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone, mdp_a_changer, role, date_creation) 
            VALUES (:nom, :prenom, :email, :mdp, :adresse, :telephone, 0, 'client', NOW())";
            
    $res = $bdd->prepare($sql);
    return $res->execute([
        ':nom'       => $nom,
        ':prenom'    => $prenom,
        ':email'     => $email,
        ':mdp'       => $mdpHash,
        ':adresse'   => $adresse,
        ':telephone' => $telephone
    ]);
}


?>