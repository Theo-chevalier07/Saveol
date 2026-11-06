<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$titre = $action === 'liste' ? 'Gestion des commandes' : 'Commande n°' . $id;
require 'includes/header.php';
?>
<h1><?= htmlspecialchars($titre) ?></h1>
<?php if ($action === 'liste'): ?>
<form method="get" class="mb-3">
    <input type="hidden" name="uc" value="gestion_commandes">
    <label for="statut">Statut</label>
    <select id="statut" name="statut"><option value="">Tous les statuts</option>
    <?php foreach (array_keys($statuts) as $s): ?>
        <option <?= $statut === $s ? 'selected' : '' ?>><?= htmlspecialchars($s) ?></option>
    <?php endforeach; ?></select>
    <button class="btn btn-success">Filtrer</button>
</form>
<?php if (!$commandes): ?><p>Aucune commande pour ce filtre.</p><?php else: ?>
<div class="table-responsive"><table class="table">
<thead><tr><th>Commande</th><th>Client</th><th>Date</th><th>Montant</th><th>Statut</th></tr></thead><tbody>
<?php foreach ($commandes as $c): ?>
<tr><td><a href="index.php?uc=gestion_commandes&amp;action=detail&amp;id_commande=<?= (int)$c['id_commande'] ?>">N° <?= (int)$c['id_commande'] ?></a></td>
<td><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></td><td><?= date('d/m/Y H:i', strtotime($c['date_commande'])) ?></td>
<td><?= number_format($c['montant'], 2, ',', ' ') ?> €</td><td><?= htmlspecialchars($c['statut']) ?></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php endif; else: ?>
<p>Client : <?= htmlspecialchars($commande['prenom'].' '.$commande['nom']) ?> · Statut : <strong><?= htmlspecialchars($commande['statut']) ?></strong></p>
<div class="table-responsive"><table class="table"><thead><tr><th>Produit</th><th>Quantité</th><th>Prix unitaire</th></tr></thead><tbody>
<?php foreach ($lignes as $ligne): ?>
<tr><td><?= htmlspecialchars($ligne['nom']) ?></td><td><?= (int)$ligne['quantite'] ?></td><td><?= number_format($ligne['prix_unitaire'], 2, ',', ' ') ?> €</td></tr>
<?php endforeach; ?></tbody></table></div>
<p>Total : <?= number_format($commande['montant'], 2, ',', ' ') ?> €</p>
<?php if (!empty($statuts[$commande['statut']])): ?>
<form method="post" action="index.php?uc=gestion_commandes&amp;action=modifier&amp;id_commande=<?= $id ?>" class="mb-3">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf()) ?>">
    <input type="hidden" name="ancien" value="<?= htmlspecialchars($commande['statut']) ?>">
    <label for="nouveau">Nouveau statut</label><select name="nouveau" id="nouveau">
    <?php foreach ($statuts[$commande['statut']] as $s): ?><option><?= htmlspecialchars($s) ?></option><?php endforeach; ?>
    </select> <button class="btn btn-success">Enregistrer</button>
</form>
<?php endif; ?><a href="index.php?uc=gestion_commandes">Retour aux commandes</a>
<?php endif; require 'includes/footer.php'; ?>
