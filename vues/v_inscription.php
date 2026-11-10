<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h3 class="card-title mb-0">Créer un compte</h3>
                </div>
                <div class="card-body p-4">
                    <?php if (isset($msgErreur) && !empty($msgErreur)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars($msgErreur) ?>
                        </div>
                    <?php endif; ?>

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
                            <label for="email" class="form-label">Adresse Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required placeholder="nom@exemple.com">
                        </div>

                        <div class="mb-3">
                            <label for="telephone" class="form-label">Numéro de Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" value="<?= htmlspecialchars($telephone ?? '') ?>" required placeholder="06 12 34 56 78">
                        </div>

                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse postale</label>
                            <textarea class="form-control" id="adresse" name="adresse" rows="2" required placeholder="N° et nom de rue, code postal, ville"><?= htmlspecialchars($adresse ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="mdp" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="mdp" name="mdp" required placeholder="••••••••">
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success">S'inscrire</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <span class="text-muted">Déjà inscrit ?</span>
                    <a href="index.php?uc=connexion" class="text-success fw-bold ms-1 text-decoration-none">Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>