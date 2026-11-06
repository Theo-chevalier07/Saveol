<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$titre = 'Gestion du catalogue';
require 'includes/header.php';
?>
<h1><?= $titre ?></h1>
<?php if ($erreur): ?><p class="alert alert-danger" role="alert"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
<?php if ($message): ?><p class="alert alert-success" role="status"><?= htmlspecialchars($message) ?></p><?php endif; ?>
<?php if ($action === 'produit'): ?>
<h2><?= $id ? 'Modifier le produit' : 'Ajouter un produit' ?></h2>
<form method="post" enctype="multipart/form-data" action="index.php?uc=gestion_catalogue&amp;action=enregistrer_produit">
<input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf()) ?>">
<input type="hidden" name="id" value="<?= $id ?? '' ?>">
<div class="mb-3"><label for="nom" class="form-label">Nom</label>
<input class="form-control" id="nom" name="nom" maxlength="100" required value="<?= htmlspecialchars($produit['nom']) ?>"></div>
<div class="mb-3"><label for="description" class="form-label">Description</label>
<textarea class="form-control" id="description" name="description" maxlength="16000" rows="4"><?= htmlspecialchars($produit['description'] ?? '') ?></textarea></div>
<div class="mb-3"><label for="prix" class="form-label">Prix (€) — laisser vide pour un devis</label>
<input class="form-control" id="prix" name="prix" type="number" min="0" max="999999.99" step="0.01" value="<?= htmlspecialchars($produit['prix'] ?? '') ?>"></div>
<div class="mb-3"><label for="conditionnement" class="form-label">Conditionnement</label>
<input class="form-control" id="conditionnement" name="conditionnement" maxlength="50" required value="<?= htmlspecialchars($produit['conditionnement']) ?>"></div>
<div class="mb-3"><label for="categorie" class="form-label">Catégorie</label>
<select class="form-select" id="categorie" name="id_categorie" required><option value="">Choisir une catégorie</option>
<?php foreach ($categories as $c): ?><option value="<?= (int)$c['id_categorie'] ?>" <?= (string)$c['id_categorie'] === (string)$produit['id_categorie'] ? 'selected' : '' ?>><?= htmlspecialchars($c['libelle']) ?></option><?php endforeach; ?>
</select></div>
<div class="mb-3"><label for="image" class="form-label">Image (JPEG, PNG ou WebP, 2 Mo maximum)</label>
<input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" <?= $id ? '' : 'required' ?>>
<?php if ($id): ?><p>Sans nouvelle image, l’image actuelle est conservée.</p><?php endif; ?></div>
<div class="form-check mb-3"><input class="form-check-input" id="avant" name="mis_en_avant" type="checkbox" value="1" <?= $produit['mis_en_avant'] ? 'checked' : '' ?>>
<label class="form-check-label" for="avant">Mettre en avant sur l’accueil</label></div>
<button class="btn btn-success">Enregistrer</button> <a href="index.php?uc=gestion_catalogue">Annuler</a>
</form>
<?php elseif ($action === 'confirmation'): ?>
<h2>Confirmer la suppression</h2>
<p>Supprimer <?= htmlspecialchars($libelle) ?> ?</p>
<form method="post" action="index.php?uc=gestion_catalogue&amp;action=supprimer">
<input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf()) ?>">
<input type="hidden" name="id" value="<?= $id ?>">
<input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">
<button class="btn btn-danger">Confirmer la suppression</button> <a href="index.php?uc=gestion_catalogue">Annuler</a>
</form>
<?php else: ?>
<p><a class="btn btn-success" href="index.php?uc=gestion_catalogue&amp;action=produit">Ajouter un produit</a></p>
<div class="table-responsive"><table class="table align-middle"><thead><tr><th>Produit</th><th>Catégorie</th><th>Prix</th><th>Mis en avant</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($produits as $p): ?>
<tr><td><?= htmlspecialchars($p['nom']) ?></td><td><?= htmlspecialchars($p['libelle']) ?></td>
<td><?= $p['prix'] === null ? 'Sur devis' : number_format($p['prix'], 2, ',', ' ') . ' €' ?></td>
<td><?= $p['mis_en_avant'] ? 'Oui' : 'Non' ?></td><td>
<a href="index.php?uc=gestion_catalogue&amp;action=produit&amp;id=<?= (int)$p['id_produit'] ?>">Modifier</a> ·
<a href="index.php?uc=gestion_catalogue&amp;action=confirmation&amp;type=produit&amp;id=<?= (int)$p['id_produit'] ?>">Supprimer</a>
</td></tr><?php endforeach; ?></tbody></table></div>
<h2>Catégories</h2>
<form class="mb-4" method="post" action="index.php?uc=gestion_catalogue&amp;action=enregistrer_categorie">
<input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf()) ?>">
<label for="nouvelle-categorie">Nouvelle catégorie</label>
<input id="nouvelle-categorie" name="libelle" maxlength="50" required>
<button class="btn btn-success">Ajouter</button>
</form>
<?php foreach ($categories as $c): ?>
<form class="d-flex flex-wrap gap-2 mb-3" method="post" action="index.php?uc=gestion_catalogue&amp;action=enregistrer_categorie">
<input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf()) ?>">
<input type="hidden" name="id" value="<?= (int)$c['id_categorie'] ?>">
<label for="cat-<?= (int)$c['id_categorie'] ?>">Catégorie</label>
<input id="cat-<?= (int)$c['id_categorie'] ?>" name="libelle" maxlength="50" required value="<?= htmlspecialchars($c['libelle']) ?>">
<button class="btn btn-outline-success">Modifier</button>
<a href="index.php?uc=gestion_catalogue&amp;action=confirmation&amp;type=categorie&amp;id=<?= (int)$c['id_categorie'] ?>">Supprimer</a>
</form>
<?php endforeach; endif; require 'includes/footer.php'; ?>

