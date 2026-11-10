<?php require "includes/header.php"; ?>

<h1 class="mb-4">Gestion des catégories</h1>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<form method="post" action="index.php?uc=gestion_categories&action=ajouter" class="row g-2 mb-4">
    <div class="col-md-6">
        <input class="form-control" name="libelle" placeholder="Nom de la nouvelle catégorie" required>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-success w-100">Ajouter</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table align-middle bg-white">
        <thead>
            <tr><th>Catégorie</th><th>Nombre de produits</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($lesCategories as $categorie) { ?>
                <tr>
                    <td>
                        <form method="post" action="index.php?uc=gestion_categories&action=modifier" class="d-flex gap-2">
                            <input type="hidden" name="id_categorie" value="<?php echo $categorie['id_categorie']; ?>">
                            <input class="form-control form-control-sm" name="libelle" value="<?php echo htmlspecialchars($categorie['libelle']); ?>" required>
                            <button type="submit" class="btn btn-primary btn-sm">Modifier</button>
                        </form>
                    </td>
                    <td><?php echo $categorie['nb_produits']; ?></td>
                    <td><a class="btn btn-danger btn-sm" href="index.php?uc=gestion_categories&action=supprimer&id_categorie=<?php echo $categorie['id_categorie']; ?>">Supprimer</a></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require "includes/footer.php"; ?>
