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
<nav class="navbar navbar-expand-lg navbar-dark bg-saveol">
    <div class="container">
        <a class="navbar-brand" href="index.php?uc=accueil">Savéol</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?uc=accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?uc=catalogue">Catalogue</a></li>

                <?php if ($_SESSION['role'] == 'client') { ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=panier">Panier</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=compte">Mon compte</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'gestionnaire' || $_SESSION['role'] == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=gestion_commandes">Commandes</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=gestion_catalogue">Gérer le catalogue</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=gestion_categories">Catégories</a></li>
                <?php } ?>

                <?php if ($_SESSION['role'] == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?uc=gestion_comptes">Comptes</a></li>
                <?php } ?>
            </ul>
            <span class="navbar-text me-3"><?php echo htmlspecialchars($_SESSION['prenom'].' '.$_SESSION['nom']); ?></span>
            <a class="btn btn-light btn-sm" href="index.php?uc=deco">Déconnexion</a>
        </div>
    </div>
</nav>
<?php } ?>

<main class="container py-4">
