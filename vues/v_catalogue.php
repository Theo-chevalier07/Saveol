<?php
if (!defined('SAVEOL')) {
    exit;
}
?>
<?php require "includes/header.php"; ?>

<div class="entete-page">
    <h1><?php echo htmlspecialchars($titreCatalogue); ?></h1>
    <p>Tomates, fraises et mâche cultivées par nos producteurs en Bretagne et dans le Val Nantais.</p>
</div>

<!-- Filtre par catégorie -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="index.php?uc=catalogue&action=voir_catalogue" class="btn btn-sm <?php if (!isset($_REQUEST['id_categorie'])) { echo 'btn-primary'; } else { echo 'btn-outline-secondary'; } ?>">Tous les produits</a>
    <?php foreach ($lesCategories as $uneCategorie) { ?>
        <a href="index.php?uc=catalogue&action=par_categorie&id_categorie=<?php echo $uneCategorie['id_categorie']; ?>" class="btn btn-sm <?php if (isset($_REQUEST['id_categorie']) && $_REQUEST['id_categorie'] == $uneCategorie['id_categorie']) { echo 'btn-primary'; } else { echo 'btn-outline-secondary'; } ?>">
            <?php echo htmlspecialchars($uneCategorie['libelle']); ?>
        </a>
    <?php } ?>
</div>

<!-- Liste des produits -->
<?php if (empty($lesProduits)) { ?>
    <div class="card"><div class="vide"><p class="mb-0">Aucun produit n'est disponible pour le moment.</p></div></div>
<?php } else { ?>
    <div class="grille-produits">
        <?php foreach ($lesProduits as $unProduit) { ?>
            <?php include "vues/v_carte_produit.php"; ?>
        <?php } ?>
    </div>
<?php } ?>

<?php require "includes/footer.php"; ?>
