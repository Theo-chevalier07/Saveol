<!-- Carte d'un produit, utilisée dans l'accueil et le catalogue -->
<div class="card carte-produit">
    <a href="index.php?uc=catalogue&action=detail_produit&id_produit=<?php echo $unProduit['id_produit']; ?>">
        <img src="images/produits/<?php echo htmlspecialchars($unProduit['image']); ?>" alt="<?php echo htmlspecialchars($unProduit['nom']); ?>">
    </a>
    <div class="card-body">
        <h2><a href="index.php?uc=catalogue&action=detail_produit&id_produit=<?php echo $unProduit['id_produit']; ?>"><?php echo htmlspecialchars($unProduit['nom']); ?></a></h2>
        <p class="conditionnement"><?php echo htmlspecialchars($unProduit['conditionnement']); ?></p>

        <div class="bas-carte">
            <?php if ($unProduit['prix'] != null) { ?>
                <span class="prix"><?php echo number_format($unProduit['prix'], 2, ',', ' '); ?> €</span>
            <?php } else { ?>
                <span class="prix sur-devis">Sur devis</span>
            <?php } ?>

            <?php if ($unProduit['disponible'] == 0) { ?>
                <span class="statut statut-refusee">Rupture de stock</span>
            <?php } elseif ($_SESSION['role'] == 'client' && $unProduit['prix'] != null) { ?>
                <!-- Ajout au panier -->
                <form method="post" action="index.php?uc=panier&action=ajouter" class="ajout-panier">
                    <input type="hidden" name="id_produit" value="<?php echo $unProduit['id_produit']; ?>">
                    <input type="hidden" name="retour" value="index.php?<?php echo htmlspecialchars($_SERVER['QUERY_STRING']); ?>">
                    <input type="number" name="quantite" min="1" value="1" class="form-control form-control-sm champ-quantite" onfocus="this.select()">
                    <button type="submit" class="btn btn-primary btn-sm">Ajouter</button>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
