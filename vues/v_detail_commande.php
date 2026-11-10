<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$commande = $detail['commande'];
$titre = 'Commande n°' . (int) $commande['id_commande'];
require 'includes/header.php';
?>
<h1><?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?></h1>
<p>Passée le <?= htmlspecialchars(date('d/m/Y à H:i', strtotime($commande['date_commande'])), ENT_QUOTES, 'UTF-8') ?></p>
<p>Statut : <strong><?= htmlspecialchars($commande['statut'], ENT_QUOTES, 'UTF-8') ?></strong></p>
<div class="table-responsive">
    <table class="table align-middle">
        <caption>Produits et prix enregistrés lors de votre commande</caption>
        <thead><tr><th scope="col">Produit</th><th scope="col">Conditionnement</th><th scope="col">Quantité</th><th scope="col">Prix unitaire</th><th scope="col">Montant</th></tr></thead>
        <tbody>
        <?php foreach ($detail['lignes'] as $ligne): ?>
            <tr>
                <th scope="row"><?= htmlspecialchars($ligne['nom'], ENT_QUOTES, 'UTF-8') ?></th>
                <td><?= htmlspecialchars($ligne['conditionnement'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= (int) $ligne['quantite'] ?></td>
                <td class="text-nowrap"><?= number_format((float) $ligne['prix_unitaire'], 2, ',', ' ') ?> €</td>
                <td class="text-nowrap"><?= number_format((float) $ligne['montant'], 2, ',', ' ') ?> €</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<p class="fs-4"><strong>Total : <?= number_format((float) $commande['montant'], 2, ',', ' ') ?> €</strong></p>
<a href="index.php?uc=compte">Retour à mon compte</a>
<?php require 'includes/footer.php'; ?>
