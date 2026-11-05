<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
define('SAVEOL', true);
$racine = $argv[1] ?? dirname(__DIR__);
if (!is_file($racine . '/includes/modele/panier.php')) {
    fwrite(STDERR, "ECHEC : le modèle du panier est absent.\n"); exit(1);
}
require $racine . '/includes/modele/panier.php';
$nombre = 0;
function verifier($condition, $message) {
    global $nombre;
    $nombre++;
    if (!$condition) { throw new Exception($message); }
}
function doit_refuser($action, $message) {
    try { $action(); } catch (DomainException $e) { verifier(true, $message); return; }
    verifier(false, $message);
}
$bdd = new PDO('sqlite::memory:');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bdd->exec('PRAGMA foreign_keys = ON');
$bdd->exec('CREATE TABLE utilisateur (id_utilisateur INTEGER PRIMARY KEY, role TEXT)');
$bdd->exec("INSERT INTO utilisateur VALUES (1, 'client'), (2, 'gestionnaire')");
$bdd->exec('CREATE TABLE produit (id_produit INTEGER PRIMARY KEY, nom TEXT, prix DECIMAL(8,2), conditionnement TEXT)');
$bdd->exec("INSERT INTO produit VALUES (1, 'Tomates', 2.35, 'Barquette 250 g'), (2, 'Fraises', 4.10, 'Barquette 500 g'), (3, 'Sur devis', NULL, 'Palette')");
$bdd->exec("CREATE TABLE commande (id_commande INTEGER PRIMARY KEY AUTOINCREMENT, id_utilisateur INTEGER REFERENCES utilisateur, statut TEXT DEFAULT 'En attente', date_commande TEXT DEFAULT CURRENT_TIMESTAMP)");
$bdd->exec('CREATE TABLE ligne_commande (id_commande INTEGER REFERENCES commande, id_produit INTEGER REFERENCES produit, quantite INTEGER, prix_unitaire DECIMAL(8,2), PRIMARY KEY(id_commande,id_produit))');
$_SESSION = ['id' => 1, 'role' => 'client'];
verifier(prix_en_centimes('2.35') === 235 && prix_en_centimes('4.1') === 410, 'Calcul monétaire exact.');
verifier(prix_en_centimes(null) === null, 'Un prix NULL ne devient pas zéro.');
verifier(prix_en_centimes('0.00') === 0, 'Un prix nul explicite est valide.');
verifier(count(produits_a_ajouter()) === 3, 'Choix de produits pour tester sans la fiche du binôme.');
ajouter_au_panier('1', '2');
ajouter_au_panier(1, 1);
verifier($_SESSION['panier'] === [1 => 3], 'Ajouts cumulés, une ligne par produit.');
modifier_quantite_panier('1', '4');
verifier($_SESSION['panier'][1] === 4, 'Modification de quantité.');
ajouter_au_panier(2, 1);
$recap = recapitulatif_panier();
verifier(count($recap['lignes']) === 2 && $recap['total'] === 1350 && $recap['valide'], 'Total 4 × 2,35 + 4,10 = 13,50.');
foreach ([0, -1, '1.5', '1e2', [], null, true, 10000] as $qte) {
    doit_refuser(fn() => ajouter_au_panier(1, $qte), 'Quantité invalide refusée.');
}
doit_refuser(fn() => ajouter_au_panier(999, 1), 'Produit inconnu refusé.');
doit_refuser(fn() => ajouter_au_panier([], 1), 'Identifiant tableau refusé.');
doit_refuser(fn() => ajouter_au_panier(3, 1), 'Produit sur devis refusé.');
doit_refuser(fn() => modifier_quantite_panier(3, 2), 'Impossible de modifier un produit absent du panier.');
verifier($_SESSION['panier'] === [1 => 4, 2 => 1], 'Les refus ne changent pas le panier.');
supprimer_du_panier(2);
verifier($_SESSION['panier'] === [1 => 4], 'Suppression de ligne.');
ajouter_au_panier(2, 1);
$bdd->exec('UPDATE produit SET prix = 2.50 WHERE id_produit = 1');
$id = valider_commande();
$commande = $bdd->query('SELECT * FROM commande')->fetch(PDO::FETCH_ASSOC);
verifier((int)$commande['id_utilisateur'] === 1 && $commande['statut'] === 'En attente', 'Commande du client connecté, en attente.');
verifier((int)$id === (int)$commande['id_commande'], 'Numéro renvoyé au client.');
$ligne = $bdd->query('SELECT * FROM ligne_commande WHERE id_produit=1')->fetch(PDO::FETCH_ASSOC);
verifier((int)$ligne['quantite'] === 4 && (string)$ligne['prix_unitaire'] === '2.5', 'Prix relu en base à la validation.');
verifier((int)$bdd->query('SELECT COUNT(*) FROM ligne_commande')->fetchColumn() === 2, 'Toutes les lignes enregistrées.');
verifier($_SESSION['panier'] === [], 'Panier vidé après réussite seulement.');
doit_refuser(fn() => valider_commande(), 'Panier vide ou double validation refusé.');
verifier((int)$bdd->query('SELECT COUNT(*) FROM commande')->fetchColumn() === 1, 'Aucune commande en double.');
$bdd->exec('UPDATE produit SET prix=99 WHERE id_produit=1');
verifier((string)$bdd->query('SELECT prix_unitaire FROM ligne_commande WHERE id_produit=1')->fetchColumn() === '2.5', 'Historique des prix préservé.');
ajouter_au_panier(2, 1);
$bdd->exec('UPDATE produit SET prix=NULL WHERE id_produit=2');
verifier(!recapitulatif_panier()['valide'], 'Prix devenu indisponible signalé.');
doit_refuser(fn() => valider_commande(), 'Prix devenu NULL bloque la commande.');
verifier($_SESSION['panier'] === [2 => 1], 'Panier conservé si produit sur devis.');
$bdd->exec('UPDATE produit SET prix=4.10 WHERE id_produit=2');
$bdd->exec("CREATE TRIGGER refuser_ligne BEFORE INSERT ON ligne_commande BEGIN SELECT RAISE(ABORT, 'test rollback'); END");
try { valider_commande(); verifier(false, 'La panne doit remonter.'); } catch (PDOException $e) { verifier(true, 'Panne simulée.'); }
verifier(!$bdd->inTransaction(), 'Transaction fermée après erreur.');
verifier((int)$bdd->query('SELECT COUNT(*) FROM commande')->fetchColumn() === 1, 'Rollback de la commande entière.');
verifier($_SESSION['panier'] === [2 => 1], 'Panier conservé après panne.');
$bdd->exec('DROP TRIGGER refuser_ligne');
$_SESSION['panier'] = [999 => 2];
$recap = recapitulatif_panier();
verifier(!$recap['valide'] && count($recap['lignes']) === 1, 'Produit supprimé affiché pour permettre son retrait.');
doit_refuser(fn() => valider_commande(), 'Produit supprimé bloque la commande.');
supprimer_du_panier(999);
verifier($_SESSION['panier'] === [], 'Produit supprimé retirable du panier.');
$_SESSION = ['id' => 2, 'role' => 'gestionnaire', 'panier' => [1 => 1]];
doit_refuser(fn() => valider_commande(), 'Seul un client peut commander.');
echo $nombre . " vérifications panier réussies.\n";
