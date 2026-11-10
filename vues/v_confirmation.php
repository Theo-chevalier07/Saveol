<?php require "includes/header.php"; ?>

<div class="card mx-auto" style="max-width: 500px;">
    <div class="card-body text-center">
        <h1 class="h5 mb-4"><?php echo htmlspecialchars($titre); ?></h1>
        <a href="<?php echo $lienOui; ?>" class="btn btn-danger">Oui, supprimer</a>
        <a href="<?php echo $lienNon; ?>" class="btn btn-outline-secondary">Annuler</a>
    </div>
</div>

<?php require "includes/footer.php"; ?>
