<?php require "includes/header.php"; ?>

<div class="entete-page">
    <h1>Mon compte</h1>
    <p>Vos informations et le suivi de vos commandes.</p>
</div>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<div class="row g-4">
    <!-- Informations du client -->
    <div class="col-lg-5">
        <div class="card">
            <div class="card-body">
                <div class="profil">
                    <span class="initiales"><?php echo htmlspecialchars(strtoupper(substr($utilisateur['prenom'], 0, 1) . substr($utilisateur['nom'], 0, 1))); ?></span>
                    <h2 class="h5 mb-0"><?php echo htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']); ?></h2>
                    <p class="text-muted small">Client depuis le <?php echo date('d/m/Y', strtotime($utilisateur['date_creation'])); ?></p>
                </div>
                <hr>
                <form method="post" action="index.php?uc=compte&action=modifier">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label" for="nom">Nom</label>
                            <input class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="prenom">Prénom</label>
                            <input class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="email">Email</label>
                        <input class="form-control" id="email" name="email" type="email" value="<?php echo htmlspecialchars($utilisateur['email']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="telephone">Téléphone</label>
                        <input class="form-control" id="telephone" name="telephone" value="<?php echo htmlspecialchars($utilisateur['telephone']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="adresse">Adresse</label>
                        <input class="form-control" id="adresse" name="adresse" value="<?php echo htmlspecialchars($utilisateur['adresse']); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Enregistrer</button>
                </form>
                <a href="index.php?uc=mot_de_passe" class="btn btn-outline-secondary w-100 mt-2">Changer mon mot de passe</a>
            </div>
        </div>
    </div>

    <!-- Historique des commandes -->
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Mes commandes</div>
            <div>
                <?php if (count($lesCommandes) == 0) { ?>
                    <div class="vide">
                        <p class="mb-0">Vous n'avez pas encore passé de commande.</p>
                    </div>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr><th>N°</th><th>Date</th><th>Montant</th><th>Statut</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lesCommandes as $commande) { ?>
                                    <tr>
                                        <td><strong>#<?php echo $commande['id_commande']; ?></strong></td>
                                        <td><?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></td>
                                        <td><strong><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</strong></td>
                                        <td><?php require "vues/v_badge_statut.php"; ?></td>
                                        <td><a class="btn btn-outline-secondary btn-sm" href="index.php?uc=compte&action=detail&id_commande=<?php echo $commande['id_commande']; ?>">Détail</a></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require "includes/footer.php"; ?>
