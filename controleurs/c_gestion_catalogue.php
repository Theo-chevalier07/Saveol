<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
require 'includes/modele/gestion_catalogue.php';
$action = $_GET['action'] ?? 'liste';
$actions = ['liste', 'produit', 'enregistrer_produit', 'enregistrer_categorie', 'confirmation', 'supprimer'];
if (!in_array($action, $actions, true)) { http_response_code(404); exit('Action introuvable.'); }
if (in_array($action, ['enregistrer_produit', 'enregistrer_categorie', 'supprimer'], true)) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Allow: POST'); http_response_code(405); exit; }
    if (!jeton_csrf_valide($_POST['csrf'] ?? null)) { http_response_code(403); exit('Formulaire expiré.'); }
}
$erreur = '';
$message = $_SESSION['catalogue_message'] ?? '';
unset($_SESSION['catalogue_message']);
try {
    require 'includes/modele/connexion.php';
    $exec = $bdd->prepare('SELECT role FROM utilisateur WHERE id_utilisateur=?');
    $exec->execute([$_SESSION['id']]);
    if (!in_array($exec->fetchColumn(), ['gestionnaire', 'admin'], true)) { http_response_code(403); exit('Accès réservé.'); }
    $categories = categories_catalogue($bdd);
    $produit = ['nom'=>'', 'description'=>'', 'prix'=>'', 'conditionnement'=>'', 'id_categorie'=>'', 'mis_en_avant'=>0];
    $id = null;
    if (in_array($action, ['produit', 'enregistrer_produit'], true)) {
        $valeur = $action === 'produit' ? ($_GET['id'] ?? '') : ($_POST['id'] ?? '');
        if ($valeur !== '') {
            $id = id_catalogue($valeur);
            $produit = produit_catalogue($bdd, $id);
            if (!$produit) { http_response_code(404); exit('Produit introuvable.'); }
        }
    }
    if ($action === 'confirmation' || $action === 'supprimer') {
        $source = $action === 'supprimer' ? $_POST : $_GET;
        $type = $source['type'] ?? '';
        $id = id_catalogue($source['id'] ?? null);
        if (!in_array($type, ['produit', 'categorie'], true)) { http_response_code(404); exit; }
        $exec = $bdd->prepare('SELECT ' . ($type === 'produit' ? 'nom' : 'libelle') . ' FROM ' . $type . ' WHERE id_' . $type . '=?');
        $exec->execute([$id]);
        $libelle = $exec->fetchColumn();
        if ($libelle === false) { http_response_code(404); exit('Élément introuvable.'); }
    }
    try {
        if ($action === 'enregistrer_produit') {
            // Valider d’abord les champs, avant de copier une image.
            $bdd->beginTransaction();
            enregistrer_produit($bdd, $_POST, $produit['image'] ?? null, $id);
            $idEnregistre = $id ?? (int)$bdd->lastInsertId();
            $image = importer_image_catalogue($_FILES['image'] ?? null, $produit['image'] ?? null);
            if (!$image) { throw new DomainException('Ajoutez une image au nouveau produit.'); }
            $exec = $bdd->prepare('UPDATE produit SET image=? WHERE id_produit=?');
            $exec->execute([$image, $idEnregistre]);
            $bdd->commit();
        } elseif ($action === 'enregistrer_categorie') {
            enregistrer_categorie($bdd, $_POST['libelle'] ?? '', ($_POST['id'] ?? '') === '' ? null : $_POST['id']);
        } elseif ($action === 'supprimer') {
            supprimer_catalogue($bdd, $type, $id);
        }
        if (in_array($action, ['enregistrer_produit', 'enregistrer_categorie', 'supprimer'], true)) {
            $_SESSION['catalogue_message'] = 'Le catalogue a été mis à jour.';
            header('Location: index.php?uc=gestion_catalogue', true, 303); exit;
        }
    } catch (DomainException $e) {
        if ($bdd->inTransaction()) { $bdd->rollBack(); }
        http_response_code(422); $erreur = $e->getMessage();
        if ($action === 'enregistrer_produit') {
            foreach (['nom', 'description', 'prix', 'conditionnement', 'id_categorie'] as $champ) {
                $produit[$champ] = is_string($_POST[$champ] ?? null) ? $_POST[$champ] : '';
            }
            $produit['mis_en_avant'] = isset($_POST['mis_en_avant']) ? 1 : 0;
            $action = 'produit';
        } else { $action = 'liste'; }
    }
    $produits = produits_catalogue($bdd);
    require 'vues/v_gestion_catalogue.php';
} catch (PDOException $e) {
    if (isset($bdd) && $bdd->inTransaction()) { $bdd->rollBack(); }
    http_response_code(503); echo 'Le catalogue est momentanément indisponible.';
} catch (DomainException $e) {
    http_response_code(400); echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
}

