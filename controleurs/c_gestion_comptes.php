<?php
if (!isset($_REQUEST['action'])){
    $action = 'liste';
}
else {
    $action = $_REQUEST['action'];
}

$message = '';
$erreur = '';
$lesRoles = array('client' => 'Client', 'gestionnaire' => 'Gestionnaire', 'admin' => 'Administrateur');

switch($action) {
    case "ajouter": {
        $nom = trim($_REQUEST['nom']);
        $prenom = trim($_REQUEST['prenom']);
        $email = trim($_REQUEST['email']);
        $telephone = trim($_REQUEST['telephone']);
        $adresse = trim($_REQUEST['adresse']);
        $role = $_REQUEST['role'];
        $mdp = $_REQUEST['mdp'];

        if ($nom == '' || $prenom == '' || $email == '') {
            $erreur = "Le nom, le prénom et l'email sont obligatoires.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "L'adresse email n'est pas valide.";
        }
        elseif (emailDejaUtilise($email, 0)) {
            $erreur = "Cette adresse email est déjà utilisée par un autre compte.";
        }
        elseif (!isset($lesRoles[$role])) {
            $erreur = "Le rôle choisi n'existe pas.";
        }
        elseif (strlen($mdp) < 8) {
            $erreur = "Le mot de passe provisoire doit faire au moins 8 caractères.";
        }
        else {
            creerCompte($nom, $prenom, $email, $telephone, $adresse, $role, $mdp);
            $message = "Le compte de " . $prenom . " " . $nom . " a été créé. Il devra changer son mot de passe à la première connexion.";
        }
        break;
    }

    case "modifier_role": {
        $id = $_REQUEST['id_utilisateur'];
        $role = $_REQUEST['role'];
        // On ne peut pas changer son propre rôle, sinon on perd l'accès admin
        if ($id == $_SESSION['id']) {
            $erreur = "Vous ne pouvez pas changer votre propre rôle.";
        }
        elseif (!isset($lesRoles[$role])) {
            $erreur = "Le rôle choisi n'existe pas.";
        }
        else {
            modifierRole($id, $role);
            $message = "Le rôle a été modifié.";
        }
        break;
    }

    case "reinitialiser": {
        $id = $_REQUEST['id_utilisateur'];
        $mdp = $_REQUEST['mdp'];
        if (strlen($mdp) < 8) {
            $erreur = "Le mot de passe provisoire doit faire au moins 8 caractères.";
        }
        else {
            reinitialiserMotDePasse($id, $mdp);
            $message = "Le mot de passe a été réinitialisé. L'utilisateur devra le changer à sa prochaine connexion.";
        }
        break;
    }

    case "supprimer": {
        // On demande une confirmation avant de supprimer
        $utilisateur = getUtilisateur($_REQUEST['id_utilisateur']);
        $titre = "Supprimer le compte de " . $utilisateur['prenom'] . " " . $utilisateur['nom'] . " ?";
        $lienOui = "index.php?uc=gestion_comptes&action=confirmer_suppression&id_utilisateur=" . $utilisateur['id_utilisateur'];
        $lienNon = "index.php?uc=gestion_comptes";
        require "vues/v_confirmation.php";
        exit;
    }

    case "confirmer_suppression": {
        $id = $_REQUEST['id_utilisateur'];
        // Un compte qui a passé des commandes doit rester pour garder l'historique
        foreach (getTousLesUtilisateurs() as $utilisateur) {
            if ($utilisateur['id_utilisateur'] == $id && $utilisateur['nb_commandes'] > 0) {
                $erreur = "Ce compte a passé des commandes, il ne peut pas être supprimé.";
            }
        }
        if ($id == $_SESSION['id']) {
            $erreur = "Vous ne pouvez pas supprimer votre propre compte.";
        }
        if ($erreur == '') {
            supprimerUtilisateur($id);
            $message = "Le compte a été supprimé.";
        }
        break;
    }
}

$lesUtilisateurs = getTousLesUtilisateurs();
require "vues/v_gestion_comptes.php";
?>
