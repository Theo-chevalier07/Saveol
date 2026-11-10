<?php require "includes/header.php"; ?>

<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
    <h1 class="mb-0">Gestion du catalogue</h1>
    <a class="btn btn-success" href="index.php?uc=gestion_catalogue&action=ajouter">Ajouter un produit</a>
</div>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<div class="table-responsive">
    <table class="table align-middle bg-white">
        <thead>
            <tr><th></th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Conditionnement</th><th>Mis en avant</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($lesProduits as $produit) { ?>
                <tr>
                    <td><img class="miniature" src="images/produits/<?php echo $produit['image']; ?>" alt=""></td>
                    <td><?php echo htmlspecialchars($produit['nom']); ?></td>
                    <td><?php echo htmlspecialchars($produit['libelle']); ?></td>
                    <td>
                        <?php if ($produit['prix'] == null) { ?>
                            Sur devis
                        <?php } else { ?>
                            <?php echo number_format($produit['prix'], 2, ',', ' '); ?> €
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($produit['conditionnement']); ?></td>
                    <td>
                        <a href="index.php?uc=gestion_catalogue&action=mise_en_avant&id_produit=<?php echo $produit['id_produit']; ?>"
                           class="btn btn-sm <?php if ($produit['mis_en_avant'] == 1) { echo 'btn-success'; } else { echo 'btn-outline-secondary'; } ?>">
                            <?php if ($produit['mis_en_avant'] == 1) { echo 'Oui'; } else { echo 'Non'; } ?>
                        </a>
                    </td>
                    <td class="text-nowrap">
                        <a class="btn btn-primary btn-sm" href="index.php?uc=gestion_catalogue&action=modifier&id_produit=<?php echo $produit['id_produit']; ?>">Modifier</a>
                        <a class="btn btn-danger btn-sm" href="index.php?uc=gestion_catalogue&action=supprimer&id_produit=<?php echo $produit['id_produit']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require "includes/footer.php"; ?>
