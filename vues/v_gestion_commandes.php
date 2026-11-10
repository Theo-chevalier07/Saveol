<?php require "includes/header.php"; ?>

<div class="entete-page">
    <h1>Gestion des commandes</h1>
    <p>Suivez les commandes des clients et changez leur statut.</p>
</div>

<!-- Filtre par statut -->
<div class="d-flex flex-wrap gap-2 mb-4">
    <a href="index.php?uc=gestion_commandes" class="btn btn-sm <?php if ($statutChoisi == '') { echo 'btn-primary'; } else { echo 'btn-outline-secondary'; } ?>">Toutes</a>
    <?php foreach (array('En attente', 'Validée', 'Expédiée', 'Terminée', 'Refusée') as $statut) { ?>
        <a href="index.php?uc=gestion_commandes&statut=<?php echo urlencode($statut); ?>" class="btn btn-sm <?php if ($statut == $statutChoisi) { echo 'btn-primary'; } else { echo 'btn-outline-secondary'; } ?>"><?php echo $statut; ?></a>
    <?php } ?>
</div>

<!-- Liste des commandes -->
<div class="card carte-tableau">
    <?php if (count($lesCommandes) == 0) { ?>
        <div class="vide">
            <p class="mb-0">Aucune commande.</p>
        </div>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr><th>N°</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($lesCommandes as $commande) { ?>
                        <tr>
                            <td><strong>#<?php echo $commande['id_commande']; ?></strong></td>
                            <td><?php echo htmlspecialchars($commande['nom'] . ' ' . $commande['prenom']); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?></td>
                            <td><strong><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</strong></td>
                            <td><?php require "vues/v_badge_statut.php"; ?></td>
                            <td class="text-end"><a class="btn btn-outline-secondary btn-sm" href="index.php?uc=gestion_commandes&action=detail&id_commande=<?php echo $commande['id_commande']; ?>">Détail</a></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>

<?php require "includes/footer.php"; ?>
