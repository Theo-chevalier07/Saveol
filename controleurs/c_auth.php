<?php
if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

$erreur = '';

switch($action) {
    case "afficher": {
        require "vues/v_authentification.php";
        break;
    }

    case "connexion": {
        $login = $_REQUEST['email'];
        $mdp = $_REQUEST['mot_de_passe'];
        $utilisateur = verifier_identification($login, $mdp);
        if ($utilisateur == false) {
            $erreur = "Email ou mot de passe incorrect.";
            require "vues/v_authentification.php";
        }
        else {
            // Chaque rôle arrive sur son espace
            if ($_SESSION['role'] == 'admin') {
                header("Location: index.php?uc=gestion_comptes");
            }
            elseif ($_SESSION['role'] == 'gestionnaire') {
                header("Location: index.php?uc=gestion_commandes");
            }
            else {
                header("Location: index.php?uc=accueil");
            }
            exit;
        }
        break;
    }
}
?>
