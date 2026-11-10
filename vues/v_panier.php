<?php require "includes/header.php"; ?>

<div class="entete-page">
    <h1>Mon panier</h1>
    <p>Vérifiez vos produits avant de valider la commande.</p>
</div>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<div class="row g-4">
    <!-- Produits du panier -->
    <div class="col-lg-8">
        <div class="card">
            <?php if (count($lesLignes) == 0) { ?>
                <div class="vide">
                    <p class="mb-0">Votre panier est vide.</p>
                </div>
            <?php } else { ?>
                <?php foreach ($lesLignes as $ligne) { ?>
                    <div class="ligne-panier">
                        <img class="miniature" src="images/produits/<?php echo $ligne['image']; ?>" alt="">
                        <div class="infos">
                            <strong><?php echo htmlspecialchars($ligne['nom']); ?></strong><br>
                            <small><?php echo htmlspecialchars($ligne['conditionnement']); ?> - <?php echo number_format($ligne['prix'], 2, ',', ' '); ?> €</small>
                        </div>
                        <form method="post" action="index.php?uc=panier&action=modifier" class="d-flex gap-2">
                            <input type="hidden" name="id_produit" value="<?php echo $ligne['id_produit']; ?>">
                            <input type="number" name="quantite" min="0" value="<?php echo $ligne['quantite']; ?>" class="form-control form-control-sm champ-quantite">
                            <button type="submit" class="btn btn-outline-secondary btn-sm">OK</button>
                        </form>
                        <div class="prix-ligne"><?php echo number_format($ligne['sous_total'], 2, ',', ' '); ?> €</div>
                        <a class="btn btn-danger btn-sm" href="index.php?uc=panier&action=supprimer&id_produit=<?php echo $ligne['id_produit']; ?>">Retirer</a>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>

        <!-- En attendant la fiche produit du catalogue -->
        <div class="card mt-4">
            <div class="card-header">Ajouter un produit</div>
            <div class="card-body">
                <form method="post" action="index.php?uc=panier&action=ajouter" class="row g-2">
                    <div class="col-md-7">
                        <select name="id_produit" class="form-select">
                            <?php foreach ($lesProduits as $produit) { ?>
                                <?php if ($produit['prix'] != null) { ?>
                                    <option value="<?php echo $produit['id_produit']; ?>">
                                        <?php echo htmlspecialchars($produit['nom'] . ' - ' . $produit['conditionnement']); ?>
                                    </option>
                                <?php } ?>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-4 col-md-2">
                        <input type="number" name="quantite" min="1" value="1" class="form-control">
                    </div>
                    <div class="col-8 col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Total et validation -->
    <div class="col-lg-4">
        <div class="card recap">
            <div class="card-header">Récapitulatif</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Produits</span>
                    <span><?php echo count($lesLignes); ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span>Total</span>
                    <span class="total"><?php echo number_format($total, 2, ',', ' '); ?> €</span>
                </div>
                <?php if (count($lesLignes) > 0) { ?>
                    <form method="post" action="index.php?uc=panier&action=valider">
                        <button type="submit" class="btn btn-success w-100 py-2">Valider ma commande</button>
                    </form>
                    <p class="text-muted small text-center mt-2 mb-0">La commande sera « En attente » jusqu'à sa validation par Savéol.</p>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>
