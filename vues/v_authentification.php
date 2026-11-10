<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$titre = 'Connexion';
require 'includes/header.php';
?>
<section class="auth-card">
    <h1>Connexion</h1>
    <p>Connectez-vous à votre espace Savéol.</p>
    <?php if ($erreur !== ''): ?>
        <p class="alert alert-danger" role="alert"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
    <form method="post" action="index.php?uc=auth&amp;action=connexion">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" id="email" name="email" type="email" autocomplete="username" value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe</label>
            <input class="form-control" id="mot_de_passe" name="mot_de_passe" type="password" autocomplete="current-password" required>
        </div>
        <button class="btn btn-primary w-100" type="submit">Se connecter</button>
    </form>
</section>
<?php require 'includes/footer.php'; ?>
