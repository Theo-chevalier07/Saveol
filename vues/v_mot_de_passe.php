<?php require "includes/header.php"; ?>

<div class="entete-page page-centree">
    <h1>Changer mon mot de passe</h1>
    <p>Le mot de passe donné par Savéol est provisoire : remplacez-le par le vôtre.</p>
</div>

<?php if ($_SESSION['mdp_a_changer'] == 1) { ?>
    <div class="alert alert-warning carte-petite">C'est votre première connexion : choisissez votre mot de passe pour accéder au site.</div>
<?php } ?>

<?php if ($message != '') { ?>
    <div class="alert alert-success carte-petite">
        <?php echo $message; ?>
        <a href="index.php?uc=accueil" class="alert-link">Accéder au site</a>
    </div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger carte-petite"><?php echo $erreur; ?></div>
<?php } ?>

<!-- Formulaire de changement du mot de passe -->
<div class="card carte-petite">
    <div class="card-body">
        <form method="post" action="index.php?uc=mot_de_passe&action=modifier">
            <div class="mb-3">
                <label class="form-label" for="ancien">Mot de passe actuel</label>
                <input class="form-control" id="ancien" name="ancien" type="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="nouveau">Nouveau mot de passe</label>
                <input class="form-control" id="nouveau" name="nouveau" type="password" minlength="8" required>
                <small class="text-muted">8 caractères minimum.</small>
            </div>
            <div class="mb-4">
                <label class="form-label" for="confirmation">Confirmer le nouveau mot de passe</label>
                <input class="form-control" id="confirmation" name="confirmation" type="password" minlength="8" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require "includes/footer.php"; ?>
