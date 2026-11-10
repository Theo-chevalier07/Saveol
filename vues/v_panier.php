<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$titre = 'Mon panier';
require 'includes/header.php';
?>
<h1>Mon panier</h1>
<?php if ($message !== ''): ?>
    <p class="alert alert-success" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>
<?php if ($erreur !== ''): ?>
    <p class="alert alert-danger" role="alert"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<section class="card p-3 mb-4" aria-labelledby="ajouter-titre">
    <h2 class="h4" id="ajouter-titre">Ajouter un produit</h2>
    <?php if (!$produits): ?>
        <p>Aucun produit n’est disponible pour le moment.</p>
    <?php else: ?>
    <form class="row g-3 align-items-end" method="post" action="index.php?uc=panier&amp;action=ajouter">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
        <div class="col-12 col-md-7">
            <label class="form-label" for="id_produit">Produit et conditionnement</label>
            <select class="form-select" id="id_produit" name="id_produit" required>
                <option value="">Choisir un produit</option>
                <?php foreach ($produits as $produit): $prix = prix_en_centimes($produit['prix']); ?>
                <option value="<?= (int) $produit['id_produit'] ?>" <?= $prix === null ? 'disabled' : '' ?>>
                    <?= htmlspecialchars($produit['nom'] . ' - ' . $produit['conditionnement'], ENT_QUOTES, 'UTF-8') ?>
                    — <?= $prix === null ? 'Sur devis' : number_format($prix / 100, 2, ',', ' ') . ' €' ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-2">
            <label class="form-label" for="quantite">Quantité</label>
            <input class="form-control" id="quantite" type="number" name="quantite" min="1" max="9999" step="1" value="1" required>
        </div>
        <div class="col-6 col-md-3"><button class="btn btn-primary w-100" type="submit">Ajouter au panier</button></div>
    </form>
    <?php endif; ?>
</section>

<?php if (!$recap['lignes']): ?>
    <p>Votre panier est vide.</p>
<?php else: ?>
<div class="table-responsive">
<table class="table align-middle">
    <caption>Récapitulatif de votre panier</caption>
    <thead><tr><th scope="col">Produit</th><th scope="col">Prix unitaire</th><th scope="col">Quantité</th><th scope="col">Total</th><th scope="col">Retirer</th></tr></thead>
    <tbody>
    <?php foreach ($recap['lignes'] as $ligne): ?>
    <tr>
        <th scope="row">
            <?= htmlspecialchars($ligne['nom'], ENT_QUOTES, 'UTF-8') ?>
            <small class="d-block"><?= htmlspecialchars($ligne['conditionnement'], ENT_QUOTES, 'UTF-8') ?></small>
            <?php if (!$ligne['disponible']): ?><small class="text-danger">Indisponible ou sur devis : retirez ce produit pour valider.</small><?php endif; ?>
        </th>
        <td><?= $ligne['prix'] === null ? '—' : number_format($ligne['prix'] / 100, 2, ',', ' ') . ' €' ?></td>
        <td>
            <form class="d-flex gap-2" method="post" action="index.php?uc=panier&amp;action=modifier">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="id_produit" value="<?= $ligne['id_produit'] ?>">
                <label class="visually-hidden" for="quantite-<?= $ligne['id_produit'] ?>">Quantité de <?= htmlspecialchars($ligne['nom'], ENT_QUOTES, 'UTF-8') ?></label>
                <input class="form-control" style="min-width: 5rem" id="quantite-<?= $ligne['id_produit'] ?>" type="number" name="quantite" min="1" max="9999" step="1" value="<?= $ligne['quantite'] ?>" required>
                <button class="btn btn-outline-primary" type="submit">Modifier</button>
            </form>
        </td>
        <td class="text-nowrap"><?= $ligne['disponible'] ? number_format($ligne['total'] / 100, 2, ',', ' ') . ' €' : '—' ?></td>
        <td>
            <form method="post" action="index.php?uc=panier&amp;action=supprimer">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="id_produit" value="<?= $ligne['id_produit'] ?>">
                <button class="btn btn-outline-danger" type="submit" aria-label="Retirer <?= htmlspecialchars($ligne['nom'], ENT_QUOTES, 'UTF-8') ?>">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<p class="fs-4"><strong><?= $recap['valide'] ? 'Total' : 'Sous-total des produits disponibles' ?> : <?= number_format($recap['total'] / 100, 2, ',', ' ') ?> €</strong></p>
<form class="mb-3" method="post" action="index.php?uc=panier&amp;action=valider">
    <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
    <button class="btn btn-primary" type="submit" <?= $recap['valide'] ? '' : 'disabled' ?>>Valider ma commande</button>
</form>
<p>Les prix sont vérifiés à la validation. Votre commande sera enregistrée avec le statut « En attente ».</p>
<?php endif; ?>
<a href="index.php?uc=catalogue">Retour au catalogue</a>
<?php require 'includes/footer.php'; ?>
