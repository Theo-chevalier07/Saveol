</main>

<?php if (!isset($pleinEcran)) { ?>
<!-- Pied de page -->
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <p class="titre-footer"><span class="logo-rond">S</span>Savéol</p>
                <p>Coopérative de producteurs de tomates, fraises et mâche en Bretagne et dans le Val Nantais. Vente aux grandes surfaces et aux grossistes.</p>
            </div>
            <div class="col-6 col-md-3">
                <p class="titre-footer">Coordonnées</p>
                <p>Savéol<br>29470 Plougastel-Daoulas<br>Finistère, France</p>
                <p>Tél. : 02 98 00 00 00<br><a href="mailto:contact@saveol.fr">contact@saveol.fr</a></p>
            </div>
            <div class="col-6 col-md-2">
                <p class="titre-footer">Horaires</p>
                <p>Du lundi au vendredi<br>8h - 17h30</p>
            </div>
            <div class="col-md-3">
                <p class="titre-footer">Liens</p>
                <p>
                    <a href="index.php?uc=accueil">Accueil</a><br>
                    <a href="index.php?uc=catalogue">Catalogue</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'client') { ?>
                        <br><a href="index.php?uc=compte">Mon compte</a>
                    <?php } ?>
                </p>
            </div>
        </div>
        <hr>
        <p class="text-center small">&copy; <?php echo date('Y'); ?> Savéol</p>
    </div>
</footer>
<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_SESSION['ouvrir_panier'])) { ?>
<!-- On rouvre le petit panier après une modification -->
<script>
    new bootstrap.Offcanvas('#miniPanier').show();
</script>
<?php unset($_SESSION['ouvrir_panier']); ?>
<?php } ?>
</body>
</html>
