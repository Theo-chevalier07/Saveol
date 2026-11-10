<?php require "includes/header.php"; ?>

<a href="index.php?uc=gestion_catalogue" class="btn btn-outline-secondary btn-sm mb-3">Retour au catalogue</a>

<div class="entete-page">
    <?php if ($produit['id_produit'] == '') { ?>
        <h1>Ajouter un produit</h1>
    <?php } else { ?>
        <h1>Modifier un produit</h1>
    <?php } ?>
    <p>Sans prix, le produit est affiché « Sur devis ».</p>
</div>

<?php if (isset($erreur) && $erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<!-- Formulaire du produit -->
<div class="card">
<div class="card-body">
<form method="post" action="index.php?uc=gestion_catalogue&action=enregistrer" enctype="multipart/form-data" class="row g-3">
    <input type="hidden" name="id_produit" value="<?php echo $produit['id_produit']; ?>">

    <div class="col-md-6">
        <label class="form-label" for="nom">Nom</label>
        <input class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="id_categorie">Catégorie</label>
        <select class="form-select" id="id_categorie" name="id_categorie" required>
            <option value="">Choisir une catégorie</option>
            <?php foreach ($lesCategories as $categorie) { ?>
                <option value="<?php echo $categorie['id_categorie']; ?>" <?php if ($categorie['id_categorie'] == $produit['id_categorie']) { echo 'selected'; } ?>>
                    <?php echo htmlspecialchars($categorie['libelle']); ?>
                </option>
            <?php } ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label" for="description">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($produit['description']); ?></textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="prix">Prix en € (laisser vide pour « Sur devis »)</label>
        <input class="form-control" id="prix" name="prix" value="<?php echo $produit['prix']; ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="conditionnement">Conditionnement</label>
        <input class="form-control" id="conditionnement" name="conditionnement" value="<?php echo htmlspecialchars($produit['conditionnement']); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="image">Image (jpg, png ou webp, 2 Mo maximum)</label>
        <input class="form-control" id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">
        <?php if ($produit['image'] != '') { ?>
            <img class="miniature mt-2" src="images/produits/<?php echo $produit['image']; ?>" alt="">
            <small class="text-muted">Sans nouvelle image, on garde celle-ci.</small>
        <?php } ?>
    </div>
    <div class="col-md-6 d-flex align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="mis_en_avant" name="mis_en_avant" <?php if ($produit['mis_en_avant'] == 1) { echo 'checked'; } ?>>
            <label class="form-check-label" for="mis_en_avant">Mis en avant sur l'accueil</label>
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="index.php?uc=gestion_catalogue" class="btn btn-outline-secondary">Annuler</a>
    </div>
</form>
</div>
</div>

<?php require "includes/footer.php"; ?>
