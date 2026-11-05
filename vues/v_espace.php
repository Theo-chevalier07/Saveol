<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
require 'includes/header.php';
?>
<h1><?= htmlspecialchars($titre, ENT_QUOTES, 'UTF-8') ?></h1>
<p>Bienvenue <?= htmlspecialchars($_SESSION['prenom'], ENT_QUOTES, 'UTF-8') ?>.</p>
<p>Cette fonctionnalité sera ajoutée dans un prochain lot.</p>
<?php require 'includes/footer.php'; ?>
