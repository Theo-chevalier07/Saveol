<div class="carte-inscription">
    <div class="entete-page page-centree">
        <a class="navbar-brand d-inline-flex align-items-center mb-3" href="index.php?uc=auth"><span class="logo-rond">S</span>Savéol</a>
        <h1>Créer un compte</h1>
        <p>Ouvrez votre espace professionnel pour commander les produits de la coopérative.</p>
    </div>

    <?php if (isset($msgErreur) && !empty($msgErreur)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($msgErreur) ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire d'inscription -->
    <div class="card">
        <div class="card-body p-4">
            <form action="index.php?uc=inscription&action=validerInscription" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($nom ?? '') ?>" required placeholder="Votre nom">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($prenom ?? '') ?>" required placeholder="Votre prénom">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Adresse email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required placeholder="nom@entreprise.fr">
                </div>

                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($telephone ?? '') ?>" required placeholder="02 98 12 34 56">
                </div>

                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse postale</label>
                    <textarea class="form-control" id="adresse" name="adresse" rows="2" required placeholder="N° et nom de rue, code postal, ville"><?= htmlspecialchars($adresse ?? '') ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="mdp" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>

    <p class="text-center mt-4">Déjà inscrit ? <a href="index.php?uc=auth">Se connecter</a></p>
</div>
