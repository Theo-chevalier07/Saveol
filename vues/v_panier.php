<?php require "includes/header.php"; ?>

<h1 class="mb-4">Mon panier</h1>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<?php if (count($lesLignes) == 0) { ?>
    <p>Votre panier est vide.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle bg-white">
            <thead>
                <tr>
                    <th></th>
                    <th>Produit</th>
                    <th>Conditionnement</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Sous-total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lesLignes as $ligne) { ?>
                    <tr>
                        <td><img class="miniature" src="images/produits/<?php echo $ligne['image']; ?>" alt=""></td>
                        <td><?php echo htmlspecialchars($ligne['nom']); ?></td>
                        <td><?php echo htmlspecialchars($ligne['conditionnement']); ?></td>
                        <td><?php echo number_format($ligne['prix'], 2, ',', ' '); ?> €</td>
                        <td>
                            <form method="post" action="index.php?uc=panier&action=modifier" class="d-flex gap-2">
                                <input type="hidden" name="id_produit" value="<?php echo $ligne['id_produit']; ?>">
                                <input type="number" name="quantite" min="0" value="<?php echo $ligne['quantite']; ?>" class="form-control form-control-sm" style="width: 80px;">
                                <button type="submit" class="btn btn-outline-secondary btn-sm">OK</button>
                            </form>
                        </td>
                        <td><?php echo number_format($ligne['sous_total'], 2, ',', ' '); ?> €</td>
                        <td><a class="btn btn-danger btn-sm" href="index.php?uc=panier&action=supprimer&id_produit=<?php echo $ligne['id_produit']; ?>">Supprimer</a></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" class="text-end">Total</th>
                    <th colspan="2"><?php echo number_format($total, 2, ',', ' '); ?> €</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <form method="post" action="index.php?uc=panier&action=valider" class="text-end">
        <button type="submit" class="btn btn-success">Valider ma commande</button>
    </form>
<?php } ?>

<h2 class="h5 mt-5">Ajouter un produit</h2>
<form method="post" action="index.php?uc=panier&action=ajouter" class="row g-2">
    <div class="col-md-6">
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
    <div class="col-md-3">
        <input type="number" name="quantite" min="1" value="1" class="form-control">
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Ajouter au panier</button>
    </div>
</form>

<?php require "includes/footer.php"; ?>
