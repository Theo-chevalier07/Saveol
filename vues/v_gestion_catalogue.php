<?php require "includes/header.php"; ?>

<div class="entete-page d-flex justify-content-between align-items-end flex-wrap gap-3">
    <div>
        <h1>Gestion du catalogue</h1>
        <p><?php echo count($lesProduits); ?> produits. L'étoile met un produit en avant sur l'accueil.</p>
    </div>
    <a class="btn btn-success" href="index.php?uc=gestion_catalogue&action=ajouter">+ Ajouter un produit</a>
</div>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<!-- Liste des produits -->
<div class="card carte-tableau">
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th></th><th>Nom</th><th>Catégorie</th><th>Prix</th><th>Conditionnement</th><th>En avant</th><th>Stock</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($lesProduits as $produit) { ?>
                <tr>
                    <td><img class="miniature" src="images/produits/<?php echo $produit['image']; ?>" alt=""></td>
                    <td><strong><?php echo htmlspecialchars($produit['nom']); ?></strong></td>
                    <td><?php echo htmlspecialchars($produit['libelle']); ?></td>
                    <td>
                        <?php if ($produit['prix'] == null) { ?>
                            <span class="statut statut-attente">Sur devis</span>
                        <?php } else { ?>
                            <?php echo number_format($produit['prix'], 2, ',', ' '); ?> €
                        <?php } ?>
                    </td>
                    <td><?php echo htmlspecialchars($produit['conditionnement']); ?></td>
                    <td>
                        <a href="index.php?uc=gestion_catalogue&action=mise_en_avant&id_produit=<?php echo $produit['id_produit']; ?>"
                           class="etoile <?php if ($produit['mis_en_avant'] == 1) { echo 'active'; } ?>" title="Mettre en avant">★</a>
                    </td>
                    <td>
                        <!-- Un clic passe le produit en rupture ou le remet en stock -->
                        <?php if ($produit['disponible'] == 1) { ?>
                            <a href="index.php?uc=gestion_catalogue&action=disponibilite&id_produit=<?php echo $produit['id_produit']; ?>" class="statut statut-terminee text-decoration-none" title="Mettre en rupture">En stock</a>
                        <?php } else { ?>
                            <a href="index.php?uc=gestion_catalogue&action=disponibilite&id_produit=<?php echo $produit['id_produit']; ?>" class="statut statut-refusee text-decoration-none" title="Remettre en stock">Rupture</a>
                        <?php } ?>
                    </td>
                    <td class="text-nowrap text-end">
                        <a class="btn btn-outline-secondary btn-sm" href="index.php?uc=gestion_catalogue&action=modifier&id_produit=<?php echo $produit['id_produit']; ?>">Modifier</a>
                        <a class="btn btn-danger btn-sm" href="index.php?uc=gestion_catalogue&action=supprimer&id_produit=<?php echo $produit['id_produit']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>

<?php require "includes/footer.php"; ?>
