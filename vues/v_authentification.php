<?php
// La page de connexion prend tout l'écran
$pleinEcran = true;
require "includes/header.php";
?>

<div class="page-connexion">
    <!-- Partie gauche avec l'image -->
    <div class="connexion-image">
        <h1>Savéol</h1>
        <p>L'espace des professionnels pour commander les produits de la coopérative, directement auprès des producteurs bretons.</p>
        <div class="connexion-produits">
            <span>Tomates</span>
            <span>Fraises</span>
            <span>Mâche</span>
        </div>
    </div>

    <!-- Formulaire de connexion -->
    <div class="connexion-formulaire">
        <div>
            <h2 class="mb-1">Connexion</h2>
            <p class="text-muted mb-4">Connectez-vous à votre espace professionnel.</p>

            <?php if ($erreur != '') { ?>
                <div class="alert alert-danger"><?php echo $erreur; ?></div>
            <?php } ?>

            <form method="post" action="index.php?uc=auth&action=connexion">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control form-control-lg" placeholder="nom@entreprise.fr" required>
                </div>
                <div class="mb-4">
                    <label for="mot_de_passe" class="form-label">Mot de passe</label>
                    <input id="mot_de_passe" name="mot_de_passe" type="password" class="form-control form-control-lg" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>

            <p class="text-center mt-4 mb-0">Pas encore de compte ? <a href="index.php?uc=inscription">Créer un compte</a></p>
        </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>
