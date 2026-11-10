<?php
if (!defined('SAVEOL')) {
    exit;
}
?>
<?php require "includes/header.php"; ?>

<!-- Bienvenue -->
<div class="bienvenue mb-5">
    <div>
        <span class="petit-titre">Coopérative de producteurs</span>
        <h1>Bienvenue sur Savéol, <?php echo htmlspecialchars($_SESSION['prenom']); ?></h1>
        <p>Tomates, fraises et mâche cultivées en Bretagne et dans le Val Nantais, livrées aux grandes surfaces et aux grossistes.</p>
        <a href="index.php?uc=catalogue" class="btn btn-light">Voir le catalogue</a>
    </div>
</div>

<!-- Produits mis en avant -->
<div class="entete-page">
    <h2>Nos produits mis en avant</h2>
    <p>La sélection du moment par nos producteurs.</p>
</div>

<?php if (empty($lesProduitsEnAvant)) { ?>
    <div class="card"><div class="vide"><p class="mb-0">Aucun produit n'est mis en avant pour le moment.</p></div></div>
<?php } else { ?>
    <div class="grille-produits">
        <?php foreach ($lesProduitsEnAvant as $unProduit) { ?>
            <?php include "vues/v_carte_produit.php"; ?>
        <?php } ?>
    </div>
<?php } ?>

<?php require "includes/footer.php"; ?>
