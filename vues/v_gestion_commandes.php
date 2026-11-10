<?php require "includes/header.php"; ?>

<h1 class="mb-4">Gestion des commandes</h1>

<form method="get" action="index.php" class="row g-2 mb-3">
    <input type="hidden" name="uc" value="gestion_commandes">
    <div class="col-md-4">
        <select name="statut" class="form-select">
            <option value="">Tous les statuts</option>
            <?php foreach (array('En attente', 'Validée', 'Expédiée', 'Terminée', 'Refusée') as $statut) { ?>
                <option value="<?php echo $statut; ?>" <?php if ($statut == $statutChoisi) { echo 'selected'; } ?>><?php echo $statut; ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filtrer</button>
    </div>
</form>

<?php if (count($lesCommandes) == 0) { ?>
    <p>Aucune commande.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle bg-white">
            <thead>
                <tr><th>N°</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($lesCommandes as $commande) { ?>
                    <tr>
                        <td><?php echo $commande['id_commande']; ?></td>
                        <td><?php echo htmlspecialchars($commande['nom'] . ' ' . $commande['prenom']); ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?></td>
                        <td><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</td>
                        <td><?php require "vues/v_badge_statut.php"; ?></td>
                        <td><a class="btn btn-outline-secondary btn-sm" href="index.php?uc=gestion_commandes&action=detail&id_commande=<?php echo $commande['id_commande']; ?>">Détail</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php require "includes/footer.php"; ?>
