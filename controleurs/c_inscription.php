<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

// Inclusion du nouveau fichier de modèle dédié à l'inscription
require 'includes/modele/gestion_inscription.php';

$action = $_REQUEST['action'] ?? 'demandeInscription';
if (!in_array($action, ['demandeInscription', 'validerInscription'], true)) {
    http_response_code(404); exit('Action introuvable.');
}

if ($action === 'validerInscription' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST'); 
    http_response_code(405); 
    exit;
}

try {
    // Connexion à la base de données (fournit $bdd)
    require 'includes/modele/connexion.php';

    $msgErreur = '';

    if ($action === 'validerInscription') {
        $nom       = trim($_POST['nom'] ?? '');
        $prenom    = trim($_POST['prenom'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $mdp       = $_POST['mdp'] ?? '';
        $adresse   = trim($_POST['adresse'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');

        // Vérification que tous les champs requis sont remplis
        if (empty($nom) || empty($prenom) || empty($email) || empty($mdp) || empty($adresse) || empty($telephone)) {
            $msgErreur = "Veuillez remplir tous les champs du formulaire.";
        } else {
            // Appel de la fonction ajouterUtilisateur définie dans gestion_inscription.php
            $resultat = ajouterUtilisateur($bdd, $nom, $prenom, $email, $mdp, $adresse, $telephone);

            if ($resultat) {
                header("Location: index.php?uc=connexion&msg=succesInscription", true, 303);
                exit();
            } else {
                $msgErreur = "Une erreur est survenue lors de l'inscription (cet email est peut-être déjà utilisé).";
            }
        }
    }

    // Affichage de la vue
    include("includes/header.php");
    include("vues/v_inscription.php");
    include("includes/footer.php");

} catch (PDOException $e) {
    http_response_code(503); 
    echo 'Le service d\'inscription est momentanément indisponible.';
}