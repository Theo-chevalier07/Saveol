<?php require "includes/header.php"; ?>

<div class="card auth-card">
    <div class="card-header">
        <h1 class="h3 mb-0">Savéol</h1>
        <small>Espace professionnel</small>
    </div>
    <div class="card-body">
        <h2 class="h5 text-center mb-3">Connexion</h2>

        <?php if ($erreur != '') { ?>
            <div class="alert alert-danger"><?php echo $erreur; ?></div>
        <?php } ?>

        <form method="post" action="index.php?uc=auth&action=connexion">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input id="mot_de_passe" name="mot_de_passe" type="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>

        <p class="text-center mt-3 mb-0">Pas encore de compte ? <a href="index.php?uc=inscription">Créer un compte</a></p>
    </div>
</div>

<?php require "includes/footer.php"; ?>
