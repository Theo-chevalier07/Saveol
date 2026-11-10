<?php
if (!defined('SAVEOL')) {
    exit;
}
?>
<?php require "includes/header.php"; ?>

<a href="index.php?uc=catalogue" class="retour">&lsaquo; Retour au catalogue</a>

<?php if ($produit == false) { ?>
    <div class="alert alert-danger mt-3">Ce produit n'existe pas.</div>
<?php } else { ?>
    <!-- Fiche du produit -->
    <div class="card fiche-produit mt-3">
        <div class="row g-0">
            <div class="col-md-6">
                <img src="images/produits/<?php echo htmlspecialchars($produit['image']); ?>" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <span class="categorie"><?php echo htmlspecialchars($produit['libelle']); ?></span>
                    <h1><?php echo htmlspecialchars($produit['nom']); ?></h1>
                    <p class="conditionnement"><?php echo htmlspecialchars($produit['conditionnement']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($produit['description'])); ?></p>

                    <?php if ($produit['prix'] != null) { ?>
                        <p class="prix"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                    <?php } else { ?>
                        <p class="prix sur-devis">Sur devis</p>
                        <p class="text-muted small">Ce produit est vendu en gros volume, contactez Savéol pour obtenir un prix.</p>
                    <?php } ?>

                    <?php if ($_SESSION['role'] == 'client' && $produit['prix'] != null) { ?>
                        <!-- Ajout au panier -->
                        <form method="post" action="index.php?uc=panier&action=ajouter" class="ajout-panier">
                            <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">
                            <input type="hidden" name="retour" value="index.php?<?php echo htmlspecialchars($_SERVER['QUERY_STRING']); ?>">
                            <input type="number" name="quantite" min="1" value="1" class="form-control champ-quantite" onfocus="this.select()">
                            <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php require "includes/footer.php"; ?>
