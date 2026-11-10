<?php require "includes/header.php"; ?>

<h1 class="mb-4">Mon compte</h1>

<?php if ($message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if ($erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header">Mes informations</div>
            <div class="card-body">
                <form method="post" action="index.php?uc=compte&action=modifier">
                    <div class="mb-2">
                        <label class="form-label" for="nom">Nom</label>
                        <input class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($utilisateur['nom']); ?>" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label" for="prenom">Prénom</label>
                        <input class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($utilisateur['prenom']); ?>" required>
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
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">Mes commandes</div>
            <div class="card-body">
                <?php if (count($lesCommandes) == 0) { ?>
                    <p class="mb-0">Vous n'avez pas encore passé de commande.</p>
                <?php } else { ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr><th>N°</th><th>Date</th><th>Montant</th><th>Statut</th><th></th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($lesCommandes as $commande) { ?>
                                    <tr>
                                        <td><?php echo $commande['id_commande']; ?></td>
                                        <td><?php echo date('d/m/Y', strtotime($commande['date_commande'])); ?></td>
                                        <td><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</td>
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
