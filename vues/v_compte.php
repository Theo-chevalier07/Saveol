<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$titre = 'Mon compte';
require 'includes/header.php';
?>
<h1>Mon compte</h1>
<?php if ($message !== ''): ?><p class="alert alert-success" role="status"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<?php if ($erreur !== ''): ?><p class="alert alert-danger" role="alert"><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
<section class="card p-3 mb-4" aria-labelledby="informations-titre">
    <h2 class="h4" id="informations-titre">Mes informations personnelles</h2>
    <form class="row g-3" method="post" action="index.php?uc=compte&amp;action=modifier">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars(jeton_csrf(), ENT_QUOTES, 'UTF-8') ?>">
        <div class="col-12 col-md-6">
            <label class="form-label" for="nom">Nom</label>
            <input class="form-control" id="nom" name="nom" maxlength="50" autocomplete="family-name" value="<?= htmlspecialchars($compte['nom'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="prenom">Prénom</label>
            <input class="form-control" id="prenom" name="prenom" maxlength="50" autocomplete="given-name" value="<?= htmlspecialchars($compte['prenom'], ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="email">Email de connexion</label>
            <input class="form-control" type="email" id="email" name="email" maxlength="100" autocomplete="email" value="<?= htmlspecialchars($compte['email'], ENT_QUOTES, 'UTF-8') ?>" aria-describedby="email-aide" required>
            <small id="email-aide">Si vous le modifiez, utilisez le nouvel email pour vous connecter.</small>
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label" for="telephone">Téléphone (facultatif)</label>
            <input class="form-control" type="tel" id="telephone" name="telephone" maxlength="20" autocomplete="tel" value="<?= htmlspecialchars($compte['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="col-12">
            <label class="form-label" for="adresse">Adresse (facultative)</label>
            <textarea class="form-control" id="adresse" name="adresse" maxlength="255" rows="3" autocomplete="street-address"><?= htmlspecialchars($compte['adresse'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="col-12"><button class="btn btn-primary" type="submit">Enregistrer mes informations</button></div>
    </form>
</section>
<section aria-labelledby="commandes-titre">
    <h2 id="commandes-titre">Mes commandes</h2>
    <?php if (!$commandes): ?>
        <p>Vous n’avez pas encore passé de commande.</p>
        <a href="index.php?uc=panier">Préparer une commande</a>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <caption>Historique de vos commandes, de la plus récente à la plus ancienne</caption>
            <thead><tr><th scope="col">Numéro</th><th scope="col">Date</th><th scope="col">Montant</th><th scope="col">Statut</th><th scope="col">Détail</th></tr></thead>
            <tbody>
            <?php foreach ($commandes as $commande): ?>
                <tr>
                    <th scope="row">N°<?= (int) $commande['id_commande'] ?></th>
                    <td><?= htmlspecialchars(date('d/m/Y à H:i', strtotime($commande['date_commande'])), ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="text-nowrap"><?= number_format((float) $commande['montant'], 2, ',', ' ') ?> €</td>
                    <td><?= htmlspecialchars($commande['statut'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><a href="index.php?uc=compte&amp;action=detail&amp;id_commande=<?= (int) $commande['id_commande'] ?>">Voir la commande n°<?= (int) $commande['id_commande'] ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</section>
<?php require 'includes/footer.php'; ?>
