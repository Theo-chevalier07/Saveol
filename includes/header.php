<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Savéol</title>
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/style.css" rel="stylesheet">
</head>
<body>

<?php if (isset($_SESSION['id'])) { ?>
<?php
// La page où on est, pour colorer son lien dans le menu
$page = '';
if (isset($_REQUEST['uc'])) {
    $page = $_REQUEST['uc'];
}

$nomsRoles = array('client' => 'Client', 'gestionnaire' => 'Gestionnaire', 'admin' => 'Administrateur');
$initiales = strtoupper(substr($_SESSION['prenom'], 0, 1) . substr($_SESSION['nom'], 0, 1));
?>
<!-- Menu, différent selon le rôle -->
<nav class="navbar navbar-expand-lg navbar-light navbar-saveol">
    <div class="container">
        <a class="navbar-brand" href="index.php?uc=accueil"><span class="logo-rond">S</span>Savéol</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto gap-lg-1">
                <li class="nav-item"><a class="nav-link <?php if ($page == 'accueil') { echo 'actif'; } ?>" href="index.php?uc=accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?php if ($page == 'catalogue') { echo 'actif'; } ?>" href="index.php?uc=catalogue">Catalogue</a></li>

                <?php if ($_SESSION['role'] == 'client') { ?>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'panier') { echo 'actif'; } ?>" href="index.php?uc=panier">Panier</a></li>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'compte') { echo 'actif'; } ?>" href="index.php?uc=compte">Mon compte</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'gestionnaire' || $_SESSION['role'] == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'gestion_commandes') { echo 'actif'; } ?>" href="index.php?uc=gestion_commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'gestion_catalogue') { echo 'actif'; } ?>" href="index.php?uc=gestion_catalogue">Produits</a></li>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'gestion_categories') { echo 'actif'; } ?>" href="index.php?uc=gestion_categories">Catégories</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link <?php if ($page == 'gestion_comptes') { echo 'actif'; } ?>" href="index.php?uc=gestion_comptes">Comptes</a></li>
                <?php } ?>
            </ul>
            <!-- Utilisateur connecté, avec son menu déroulant -->
            <div class="dropdown py-2 py-lg-0">
                <button class="utilisateur-connecte dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <span class="initiales"><?php echo htmlspecialchars($initiales); ?></span>
                    <span class="text-start">
                        <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?>
                        <span class="role"><?php echo $nomsRoles[$_SESSION['role']]; ?></span>
                    </span>
                    <span class="fleche">&lsaquo;</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="index.php?uc=mot_de_passe">Changer mon mot de passe</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item deconnexion" href="index.php?uc=deco">Déconnexion</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>
<?php } ?>

<?php if (isset($pleinEcran)) { ?>
<main>
<?php } else { ?>
<main class="container py-4 py-lg-5">
<?php } ?>
