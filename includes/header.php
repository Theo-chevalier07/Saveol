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

// Page où revenir après une action du petit panier
if ($page == 'panier') {
    $pageRetour = 'index.php?uc=panier';
}
else {
    $pageRetour = 'index.php?' . $_SERVER['QUERY_STRING'];
}

$nomsRoles = array('client' => 'Client', 'gestionnaire' => 'Gestionnaire', 'admin' => 'Administrateur');
$initiales = strtoupper(substr($_SESSION['prenom'], 0, 1) . substr($_SESSION['nom'], 0, 1));
?>
<!-- Menu, différent selon le rôle -->
<nav class="navbar navbar-expand-lg navbar-light navbar-saveol">
    <div class="container">
        <a class="navbar-brand" href="index.php?uc=accueil"><span class="logo-rond">S</span>Savéol</a>
        <?php if ($_SESSION['role'] == 'client') { ?>
            <!-- Bouton du panier, qui ouvre le petit panier sur le côté -->
            <button class="bouton-panier ms-auto me-2 ms-lg-3 me-lg-0 order-lg-last" type="button" data-bs-toggle="offcanvas" data-bs-target="#miniPanier">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"></path></svg>
                <?php if (count($miniPanier) > 0) { ?>
                    <span class="pastille"><?php echo count($miniPanier); ?></span>
                <?php } ?>
            </button>
        <?php } ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto gap-lg-1">
                <li class="nav-item"><a class="nav-link <?php if ($page == 'accueil') { echo 'actif'; } ?>" href="index.php?uc=accueil">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?php if ($page == 'catalogue') { echo 'actif'; } ?>" href="index.php?uc=catalogue">Catalogue</a></li>

                <?php if ($_SESSION['role'] == 'client') { ?>
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

<?php if ($_SESSION['role'] == 'client') { ?>
<!-- Petit panier qui s'ouvre sur le côté -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="miniPanier">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Mon panier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column">
        <?php if (count($miniPanier) == 0) { ?>
            <p class="text-muted">Votre panier est vide.</p>
        <?php } else { ?>
            <?php foreach ($miniPanier as $ligne) { ?>
                <div class="ligne-mini-panier">
                    <img class="miniature" src="images/produits/<?php echo $ligne['image']; ?>" alt="">
                    <div class="flex-grow-1">
                        <strong><?php echo htmlspecialchars($ligne['nom']); ?></strong><br>
                        <small class="text-muted"><?php echo number_format($ligne['prix'], 2, ',', ' '); ?> € - <?php echo htmlspecialchars($ligne['conditionnement']); ?></small>
                        <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                            <form method="post" action="index.php?uc=panier&action=modifier" class="d-flex gap-1">
                                <input type="hidden" name="id_produit" value="<?php echo $ligne['id_produit']; ?>">
                                <input type="hidden" name="retour" value="<?php echo htmlspecialchars($pageRetour); ?>">
                                <input type="hidden" name="depuis_panier" value="1">
                                <input type="number" name="quantite" min="0" value="<?php echo $ligne['quantite']; ?>" class="form-control form-control-sm champ-quantite" onfocus="this.select()">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">OK</button>
                            </form>
                            <a class="btn btn-outline-secondary btn-sm" href="index.php?uc=panier&action=retirer_un&id_produit=<?php echo $ligne['id_produit']; ?>&retour=<?php echo urlencode($pageRetour); ?>&depuis_panier=1">Retirer</a>
                            <a class="btn btn-danger btn-sm" href="index.php?uc=panier&action=supprimer&id_produit=<?php echo $ligne['id_produit']; ?>&retour=<?php echo urlencode($pageRetour); ?>&depuis_panier=1">Tout retirer</a>
                        </div>
                    </div>
                    <strong class="text-nowrap"><?php echo number_format($ligne['sous_total'], 2, ',', ' '); ?> €</strong>
                </div>
            <?php } ?>
            <div class="mt-auto pt-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Total</span>
                    <span class="prix"><?php echo number_format($totalMiniPanier, 2, ',', ' '); ?> €</span>
                </div>
                <form method="post" action="index.php?uc=panier&action=valider">
                    <button type="submit" class="btn btn-success w-100 py-2 mb-2">Valider ma commande</button>
                </form>
                <a href="index.php?uc=panier" class="btn btn-outline-secondary w-100">Voir le détail du panier</a>
                <p class="text-muted small text-center mt-2 mb-0">La commande sera « En attente » jusqu'à sa validation par Savéol.</p>
            </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<?php } ?>

<?php if (isset($pleinEcran)) { ?>
<main>
<?php } else { ?>
<main class="container py-4 py-lg-5">
<?php } ?>

<?php if (isset($_SESSION['message_ajout'])) { ?>
    <!-- Message après un ajout au panier depuis le catalogue -->
    <div class="alert alert-<?php echo $_SESSION['message_ajout_type']; ?> d-flex justify-content-between align-items-center">
        <span><?php echo htmlspecialchars($_SESSION['message_ajout']); ?></span>
        <a href="index.php?uc=panier" class="btn btn-sm btn-success">Voir mon panier</a>
    </div>
    <?php unset($_SESSION['message_ajout']); ?>
<?php } ?>
