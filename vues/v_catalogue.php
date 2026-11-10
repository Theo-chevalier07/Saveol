<?php
if (!defined('SAVEOL')) {
    exit;
}
?>
<?php require "includes/header.php"; ?>
<div class="container my-4">
    <div class="row">
        <!-- Menu latéral des catégories -->
        <div class="col-md-3 mb-4">
            <h3 class="h5">Catégories</h3>
            <div class="list-group">
                <a href="index.php?uc=catalogue&action=voir_catalogue" class="list-group-item list-group-item-action">
                    Tous les produits
                </a>
                <?php foreach ($lesCategories as $uneCategorie) { ?>
                    <a href="index.php?uc=catalogue&action=par_categorie&id_categorie=<?php echo $uneCategorie['id_categorie']; ?>" class="list-group-item list-group-item-action">
                        <?php echo htmlspecialchars($uneCategorie['libelle']); ?>
                    </a>
                <?php } ?>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="col-md-9">
            <h2 class="mb-4"><?php echo htmlspecialchars($titreCatalogue); ?></h2>

            <?php if (empty($lesProduits)) { ?>
                <div class="alert alert-info">Aucun produit n'est disponible pour le moment.</div>
            <?php } else { ?>
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php foreach ($lesProduits as $unProduit) { ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm">
                                <?php if (!empty($unProduit['image']) && file_exists("images/produits/" . $unProduit['image'])) { ?>
                                    <img src="images/produits/<?php echo htmlspecialchars($unProduit['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($unProduit['nom']); ?>" style="height: 200px; object-fit: cover;">
                                <?php } else { ?>
                                    <img src="images/produits/default.png" class="card-img-top" alt="Image non disponible" style="height: 200px; object-fit: cover;">
                                <?php } ?>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?php echo htmlspecialchars($unProduit['nom']); ?></h5>
                                    <p class="card-text text-muted small"><?php echo htmlspecialchars($unProduit['conditionnement']); ?></p>
                                    <p class="card-text flex-grow-1"><?php echo nl2br(htmlspecialchars(substr($unProduit['description'], 0, 100))); ?>...</p>
                                    
                                    <div class="mt-3">
                                        <span class="fw-bold text-success">
                                            <?php echo ($unProduit['prix'] !== null) ? number_format($unProduit['prix'], 2, ',', ' ') . ' €' : 'Sur devis'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php require "includes/footer.php"; ?>