<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }

function texte_catalogue($valeur, $maximum, $obligatoire = true) {
    if (!is_string($valeur)) { throw new DomainException('Saisie incorrecte.'); }
    $valeur = trim($valeur);
    if (($obligatoire && $valeur === '') || !preg_match('/^.{0,' . $maximum . '}$/usD', $valeur) || strpos($valeur, "\0") !== false) {
        throw new DomainException('Un champ est vide ou dépasse la longueur autorisée.');
    }
    return $valeur;
}

function id_catalogue($valeur) {
    if ((!is_string($valeur) && !is_int($valeur)) || filter_var($valeur, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1]]) === false) {
        throw new DomainException('Identifiant incorrect.');
    }
    return (int)$valeur;
}

function categories_catalogue($bdd) {
    return $bdd->query('SELECT id_categorie, libelle FROM categorie ORDER BY libelle')->fetchAll(PDO::FETCH_ASSOC);
}

function produits_catalogue($bdd) {
    return $bdd->query('SELECT p.*, c.libelle FROM produit p JOIN categorie c ON c.id_categorie = p.id_categorie ORDER BY p.nom')->fetchAll(PDO::FETCH_ASSOC);
}

function produit_catalogue($bdd, $id) {
    $exec = $bdd->prepare('SELECT * FROM produit WHERE id_produit = ?');
    $exec->execute([id_catalogue($id)]);
    return $exec->fetch(PDO::FETCH_ASSOC);
}

function enregistrer_produit($bdd, $donnees, $image, $id = null) {
    $nom = texte_catalogue($donnees['nom'] ?? '', 100);
    $description = texte_catalogue($donnees['description'] ?? '', 16000, false);
    $conditionnement = texte_catalogue($donnees['conditionnement'] ?? '', 50);
    $categorie = id_catalogue($donnees['id_categorie'] ?? null);
    $prix = $donnees['prix'] ?? '';
    if (!is_string($prix)) { throw new DomainException('Prix incorrect.'); }
    $prix = str_replace(',', '.', trim($prix));
    if ($prix !== '' && !preg_match('/^\d{1,6}(\.\d{1,2})?$/D', $prix)) {
        throw new DomainException('Prix attendu : de 0 à 999999,99 €, avec au maximum deux décimales. Laissez vide pour un devis.');
    }
    $exec = $bdd->prepare('SELECT id_categorie FROM categorie WHERE id_categorie = ?');
    $exec->execute([$categorie]);
    if (!$exec->fetchColumn()) { throw new DomainException('Choisissez une catégorie existante.'); }
    $valeurs = [$nom, $description, $prix === '' ? null : $prix, $conditionnement, $image, isset($donnees['mis_en_avant']) ? 1 : 0, $categorie];
    if ($id === null) {
        $exec = $bdd->prepare('INSERT INTO produit(nom, description, prix, conditionnement, image, mis_en_avant, id_categorie) VALUES(?, ?, ?, ?, ?, ?, ?)');
    } else {
        $valeurs[] = id_catalogue($id);
        $exec = $bdd->prepare('UPDATE produit SET nom=?, description=?, prix=?, conditionnement=?, image=?, mis_en_avant=?, id_categorie=? WHERE id_produit=?');
    }
    $exec->execute($valeurs);
}

function enregistrer_categorie($bdd, $libelle, $id = null) {
    $libelle = texte_catalogue($libelle, 50);
    $exec = $bdd->prepare($id === null ? 'INSERT INTO categorie(libelle) VALUES(?)' : 'UPDATE categorie SET libelle=? WHERE id_categorie=?');
    $exec->execute($id === null ? [$libelle] : [$libelle, id_catalogue($id)]);
}

function supprimer_catalogue($bdd, $type, $id) {
    $id = id_catalogue($id);
    if (!in_array($type, ['produit', 'categorie'], true)) { throw new DomainException('Élément incorrect.'); }
    $sql = $type === 'produit' ? 'SELECT 1 FROM ligne_commande WHERE id_produit=?' : 'SELECT 1 FROM produit WHERE id_categorie=?';
    $exec = $bdd->prepare($sql);
    $exec->execute([$id]);
    $message = $type === 'produit' ? 'Ce produit figure dans une commande et doit être conservé.' : 'Cette catégorie contient encore des produits.';
    if ($exec->fetchColumn()) { throw new DomainException($message); }
    // La clé étrangère protège aussi contre une commande créée pendant la suppression.
    try {
        $exec = $bdd->prepare('DELETE FROM ' . $type . ' WHERE id_' . $type . '=?');
        $exec->execute([$id]);
    } catch (PDOException $e) {
        if ($e->getCode() === '23000') { throw new DomainException($message); }
        throw $e;
    }
}

function importer_image_catalogue($fichier, $ancienne = null) {
    if (!$fichier || ($fichier['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { return $ancienne; }
    if (($fichier['error'] ?? null) !== UPLOAD_ERR_OK || !is_string($fichier['tmp_name'] ?? null)
        || !is_uploaded_file($fichier['tmp_name'])) { throw new DomainException('Import de l’image impossible.'); }
    if (filesize($fichier['tmp_name']) > 2 * 1024 * 1024) { throw new DomainException('Image limitée à 2 Mo.'); }
    $info = @getimagesize($fichier['tmp_name']);
    $formats = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/webp'=>'webp'];
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($fichier['tmp_name']);
    if (!$info || !isset($formats[$mime]) || ($info['mime'] ?? '') !== $mime || $info[0] > 8000 || $info[1] > 8000) {
        throw new DomainException('Choisissez une image JPEG, PNG ou WebP de 8000 pixels maximum par côté.');
    }
    $dossier = dirname(__DIR__, 2) . '/images/produits';
    if (!is_dir($dossier) && !mkdir($dossier, 0755, true) && !is_dir($dossier)) {
        throw new DomainException('Le dossier des images est indisponible.');
    }
    $nom = bin2hex(random_bytes(16)) . '.' . $formats[$mime];
    if (!move_uploaded_file($fichier['tmp_name'], $dossier . '/' . $nom)) { throw new DomainException('Enregistrement de l’image impossible.'); }
    return 'images/produits/' . $nom;
}

