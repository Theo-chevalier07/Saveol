<?php require "includes/header.php"; ?>

<?php if ($_SESSION['role'] == 'client') { ?>
    <a href="index.php?uc=compte" class="btn btn-outline-secondary btn-sm mb-3">Retour à mon compte</a>
<?php } else { ?>
    <a href="index.php?uc=gestion_commandes" class="btn btn-outline-secondary btn-sm mb-3">Retour aux commandes</a>
<?php } ?>

<div class="entete-page d-flex flex-wrap align-items-center gap-3">
    <h1 class="mb-0">Commande n°<?php echo $commande['id_commande']; ?></h1>
    <?php require "vues/v_badge_statut.php"; ?>
</div>

<?php
// Les étapes de la commande, dans l'ordre
$etapes = array('En attente', 'Validée', 'Expédiée', 'Terminée');
$faite = true;
?>
<!-- Suivi de la commande -->
<?php if ($commande['statut'] == 'Refusée') { ?>
    <div class="alert alert-danger">Cette commande a été refusée.</div>
<?php } else { ?>
    <div class="card mb-4">
        <div class="card-body">
            <div class="suivi">
                <?php foreach ($etapes as $numero => $etape) { ?>
                    <div class="etape <?php if ($faite) { echo 'faite'; } ?>">
                        <span><?php echo $numero + 1; ?></span>
                        <?php echo $etape; ?>
                    </div>
                    <?php
                    // Les étapes après le statut actuel ne sont pas encore faites
                    if ($etape == $commande['statut']) {
                        $faite = false;
                    }
                    ?>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>

<?php if (isset($message) && $message != '') { ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php } ?>
<?php if (isset($erreur) && $erreur != '') { ?>
    <div class="alert alert-danger"><?php echo $erreur; ?></div>
<?php } ?>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Client</div>
            <div class="card-body">
                <p class="mb-1"><strong><?php echo htmlspecialchars($commande['prenom'] . ' ' . $commande['nom']); ?></strong></p>
                <p class="mb-1">Email : <?php echo htmlspecialchars($commande['email']); ?></p>
                <p class="mb-1">Téléphone : <?php echo htmlspecialchars($commande['telephone']); ?></p>
                <p class="mb-0">Adresse : <?php echo htmlspecialchars($commande['adresse']); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">Commande</div>
            <div class="card-body">
                <p class="mb-1">Date : <?php echo date('d/m/Y à H:i', strtotime($commande['date_commande'])); ?></p>
                <p>Statut : <?php require "vues/v_badge_statut.php"; ?></p>
                <p class="mb-3">Montant : <strong><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</strong></p>

                <?php if ($_SESSION['role'] != 'client' && count($lesStatuts) > 0) { ?>
                    <form method="post" action="index.php?uc=gestion_commandes&action=statut" class="d-flex gap-2">
                        <input type="hidden" name="id_commande" value="<?php echo $commande['id_commande']; ?>">
                        <select name="statut" class="form-select">
                            <?php foreach ($lesStatuts as $statut) { ?>
                                <option value="<?php echo $statut; ?>"><?php echo $statut; ?></option>
                            <?php } ?>
                        </select>
                        <button type="submit" class="btn btn-primary text-nowrap">Changer le statut</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<!-- Produits commandés -->
<div class="card carte-tableau">
<div class="table-responsive">
    <table class="table align-middle">
        <thead>
            <tr><th></th><th>Produit</th><th>Conditionnement</th><th>Quantité</th><th>Prix unitaire</th><th>Sous-total</th></tr>
        </thead>
        <tbody>
            <?php foreach ($lesLignes as $ligne) { ?>
                <tr>
                    <td><img class="miniature" src="images/produits/<?php echo $ligne['image']; ?>" alt=""></td>
                    <td><strong><?php echo htmlspecialchars($ligne['nom']); ?></strong></td>
                    <td><?php echo htmlspecialchars($ligne['conditionnement']); ?></td>
                    <td><?php echo $ligne['quantite']; ?></td>
                    <td><?php echo number_format($ligne['prix_unitaire'], 2, ',', ' '); ?> €</td>
                    <td><?php echo number_format($ligne['sous_total'], 2, ',', ' '); ?> €</td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr><th colspan="5" class="text-end">Total</th><th><?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</th></tr>
        </tfoot>
    </table>
</div>
</div>

<?php require "includes/footer.php"; ?>
