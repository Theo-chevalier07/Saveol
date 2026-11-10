<?php
if (!defined('SAVEOL')) {
    exit;
}
?>
<?php require "includes/header.php"; ?>

<div class="container my-4">
    <!-- Section de bienvenue -->
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm text-center">
        <div class="container-fluid py-3">
            <h1 class="display-5 fw-bold text-success">Bienvenue sur Saveol</h1>
            <p class="col-md-8 fs-4 mx-auto text-muted">Découvrez nos produits frais de qualité rigoureusement sélectionnés.</p>
        </div>
    </div>

    <!-- Affichage direct des produits mis en avant -->
    <h2 class="text-center mb-4">🌟 Nos produits mis en avant</h2>

    <?php if (empty($lesProduitsEnAvant)) { ?>
        <div class="alert alert-info text-center">Aucun produit n'est mis en avant pour le moment.</div>
    <?php } else { ?>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($lesProduitsEnAvant as $unProduit) { ?>
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <?php if (!empty($unProduit['image']) && file_exists("images/produits/" . $unProduit['image'])) { ?>
                            <img src="images/produits/<?php echo htmlspecialchars($unProduit['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($unProduit['nom']); ?>" style="height: 200px; object-fit: cover;">
                        <?php } else { ?>
                            <img src="images/produits/default.png" class="card-img-top" alt="Image non disponible" style="height: 200px; object-fit: cover;">
                        <?php } ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-dark"><?php echo htmlspecialchars($unProduit['nom']); ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?php echo nl2br(htmlspecialchars(substr($unProduit['description'], 0, 90))); ?>...
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                                <span class="fw-bold text-success fs-5">
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

<?php require "includes/footer.php"; ?>