<?php if (!defined('SAVEOL')) { http_response_code(403); exit; } ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($titre ?? 'Savéol', ENT_QUOTES, 'UTF-8') ?> - Savéol</title>
    <link href="includes/css/bootstrap.min.css" rel="stylesheet">
    <link href="includes/css/style.css" rel="stylesheet">
</head>
<body>
<header class="site-header">
    <a class="navbar-brand" href="index.php?uc=accueil">Savéol</a>
    <?php if (utilisateur_connecte()): ?>
    <nav class="nav-links" aria-label="Navigation principale">
        <a href="index.php?uc=accueil">Accueil</a>
        <a href="index.php?uc=catalogue">Catalogue</a>
        <?php if ($_SESSION['role'] === 'client'): ?>
            <a href="index.php?uc=panier">Panier</a>
            <a href="index.php?uc=compte">Mon compte</a>
        <?php endif; ?>
        <?php if (in_array($_SESSION['role'], ['gestionnaire', 'admin'], true)): ?>
            <a href="index.php?uc=gestion_commandes">Commandes</a>
            <a href="index.php?uc=gestion_catalogue">Gérer le catalogue</a>
        <?php endif; ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="index.php?uc=gestion_comptes">Comptes utilisateurs</a>
        <?php endif; ?>
        <form method="post" action="index.php?uc=deco">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
            <button class="btn btn-outline-light" type="submit">Se déconnecter</button>
        </form>
    </nav>
    <?php endif; ?>
</header>
<main class="container py-4">
