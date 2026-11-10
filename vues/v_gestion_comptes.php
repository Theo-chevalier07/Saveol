<?php require "includes/header.php"; ?>

<div class="entete-page">
    <h1>Gestion des comptes</h1>
    <p>Un nouveau compte reçoit un mot de passe provisoire, qu'il devra changer à sa première connexion.</p>
</div>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<!-- Création d'un compte -->
<div class="card mb-4">
    <div class="card-header">Créer un compte</div>
    <div class="card-body">
        <form method="post" action="index.php?uc=gestion_comptes&action=ajouter" class="row g-3">
            <div class="col-md-4">
                <label for="nom" class="form-label">Nom</label>
                <input id="nom" name="nom" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="prenom" class="form-label">Prénom</label>
                <input id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="email" class="form-label">Email</label>
                <input id="email" name="email" type="email" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="telephone" class="form-label">Téléphone</label>
                <input id="telephone" name="telephone" class="form-control">
            </div>
            <div class="col-md-8">
                <label for="adresse" class="form-label">Adresse</label>
                <input id="adresse" name="adresse" class="form-control">
            </div>
            <div class="col-md-4">
                <label for="role" class="form-label">Rôle</label>
                <select id="role" name="role" class="form-select">
                    <?php foreach ($lesRoles as $code => $libelle) { ?>
                        <option value="<?php echo $code; ?>"><?php echo $libelle; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="mdp" class="form-label">Mot de passe provisoire</label>
                <input id="mdp" name="mdp" type="text" class="form-control" minlength="8" required>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success w-100">Créer le compte</button>
            </div>
        </form>
    </div>
</div>

<!-- Liste des comptes -->
<div class="card carte-tableau">
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th>Utilisateur</th><th>Rôle</th><th>Mot de passe</th><th>Commandes</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($lesUtilisateurs as $utilisateur) { ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']); ?></strong><br>
                        <small class="text-muted"><?php echo htmlspecialchars($utilisateur['email']); ?></small>
                    </td>
                    <td>
                        <?php if ($utilisateur['id_utilisateur'] == $_SESSION['id']) { ?>
                            <?php echo $lesRoles[$utilisateur['role']]; ?> <small class="text-muted">(vous)</small>
                        <?php } else { ?>
                            <form method="post" action="index.php?uc=gestion_comptes&action=modifier_role" class="d-flex gap-2">
                                <input type="hidden" name="id_utilisateur" value="<?php echo $utilisateur['id_utilisateur']; ?>">
                                <select name="role" class="form-select form-select-sm">
                                    <?php foreach ($lesRoles as $code => $libelle) { ?>
                                        <option value="<?php echo $code; ?>" <?php if ($utilisateur['role'] == $code) { echo 'selected'; } ?>><?php echo $libelle; ?></option>
                                    <?php } ?>
                                </select>
                                <button type="submit" class="btn btn-outline-secondary btn-sm">OK</button>
                            </form>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($utilisateur['mdp_a_changer'] == 1) { ?>
                            <span class="statut statut-attente">Provisoire</span>
                        <?php } else { ?>
                            <span class="statut statut-terminee">Changé</span>
                        <?php } ?>
                    </td>
                    <td><?php echo $utilisateur['nb_commandes']; ?></td>
                    <td class="text-end text-nowrap">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#reinit<?php echo $utilisateur['id_utilisateur']; ?>">Réinitialiser le mot de passe</button>
                        <?php if ($utilisateur['nb_commandes'] == 0 && $utilisateur['id_utilisateur'] != $_SESSION['id']) { ?>
                            <a class="btn btn-danger btn-sm" href="index.php?uc=gestion_comptes&action=supprimer&id_utilisateur=<?php echo $utilisateur['id_utilisateur']; ?>">Supprimer</a>
                        <?php } ?>
                    </td>
                </tr>
                <!-- Nouveau mot de passe provisoire, caché tant qu'on ne clique pas -->
                <tr class="collapse" id="reinit<?php echo $utilisateur['id_utilisateur']; ?>">
                    <td colspan="5">
                        <form method="post" action="index.php?uc=gestion_comptes&action=reinitialiser" class="d-flex gap-2 justify-content-end">
                            <input type="hidden" name="id_utilisateur" value="<?php echo $utilisateur['id_utilisateur']; ?>">
                            <input name="mdp" type="text" class="form-control form-control-sm champ-mdp" minlength="8" placeholder="Nouveau mot de passe provisoire" required>
                            <button type="submit" class="btn btn-primary btn-sm">Valider</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</div>

<?php require "includes/footer.php"; ?>
