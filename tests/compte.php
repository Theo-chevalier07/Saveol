<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
define('SAVEOL', true);
$racine = $argv[1] ?? dirname(__DIR__);
if (!is_file($racine . '/includes/modele/compte.php')) { fwrite(STDERR, "ECHEC : le modèle du compte est absent.\n"); exit(1); }
require $racine . '/includes/modele/compte.php';
$nombre = 0;
function verifier($condition, $message) {
    global $nombre; $nombre++;
    if (!$condition) { throw new Exception($message); }
}
function doit_refuser($action, $message) {
    try { $action(); } catch (DomainException $e) { verifier(true, $message); return; }
    verifier(false, $message);
}
$bdd = new PDO('sqlite::memory:');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bdd->exec('CREATE TABLE utilisateur (id_utilisateur INTEGER PRIMARY KEY, nom TEXT, prenom TEXT, email TEXT UNIQUE COLLATE NOCASE, telephone TEXT, adresse TEXT, role TEXT, mot_de_passe TEXT)');
$bdd->exec("INSERT INTO utilisateur VALUES (1, 'Premier', 'Client', 'premier@example.test', NULL, NULL, 'client', 'hash-conserve'), (2, 'Second', 'Client', 'second@example.test', '', '', 'client', 'autre-hash')");
$bdd->exec('CREATE TABLE produit (id_produit INTEGER PRIMARY KEY, nom TEXT, conditionnement TEXT, prix DECIMAL(8,2))');
$bdd->exec("INSERT INTO produit VALUES (1, 'Tomates', 'Barquette', 99), (2, 'Fraises', 'Barquette', 50)");
$bdd->exec('CREATE TABLE commande (id_commande INTEGER PRIMARY KEY, id_utilisateur INTEGER, date_commande TEXT, statut TEXT)');
$bdd->exec("INSERT INTO commande VALUES (1,1,'2026-11-01 10:00:00','En attente'), (2,2,'2026-11-02 10:00:00','Validée'), (3,1,'2026-11-03 10:00:00','Expédiée')");
$bdd->exec('CREATE TABLE ligne_commande (id_commande INTEGER, id_produit INTEGER, quantite INTEGER, prix_unitaire DECIMAL(8,2))');
$bdd->exec('INSERT INTO ligne_commande VALUES (1,1,2,2.35), (1,2,1,4.10), (2,1,100,2.35), (3,2,1,4.10)');
$_SESSION = ['id'=>1, 'role'=>'client'];
$profil = consulter_compte();
verifier($profil['email']==='premier@example.test', 'Profil du client connecté.');
verifier(!isset($profil['mot_de_passe']), 'Ne jamais retourner le hash au formulaire.');
$donnees = ['nom'=>' Dupont ', 'prenom'=>' Théo ', 'email'=>' theo@example.test ', 'telephone'=>'+33 6 12 34 56 78', 'adresse'=>'12 rue du Test'];
modifier_compte($donnees + ['id_utilisateur'=>2,'role'=>'admin','mot_de_passe'=>'remplace']);
$profil=consulter_compte();
verifier($profil['nom']==='Dupont' && $profil['prenom']==='Théo' && $profil['email']==='theo@example.test', 'Champs validés et espaces retirés.');
verifier($_SESSION['nom']==='Dupont' && $_SESSION['prenom']==='Théo', 'Session actualisée.');
$ligne=$bdd->query('SELECT role,mot_de_passe FROM utilisateur WHERE id_utilisateur=1')->fetch(PDO::FETCH_ASSOC);
verifier($ligne===['role'=>'client','mot_de_passe'=>'hash-conserve'], 'Rôle et hash ignorent les données POST.');
verifier($bdd->query('SELECT nom FROM utilisateur WHERE id_utilisateur=2')->fetchColumn()==='Second', 'Impossible de modifier un autre compte.');
modifier_compte($donnees);
verifier(consulter_compte()['email']==='theo@example.test', 'Sauvegarde inchangée autorisée.');
foreach ([['nom'=>''],['prenom'=>[]],['email'=>'incorrect'],['email'=>'SECOND@example.test'],['telephone'=>str_repeat('1',21)],['adresse'=>str_repeat('a',256)],['nom'=>str_repeat('é',51)]] as $mauvais) {
    doit_refuser(fn()=>modifier_compte(array_replace($donnees,$mauvais)), 'Saisie invalide ou email pris refusé.');
}
verifier(consulter_compte()['nom']==='Dupont', 'Pas de mise à jour partielle après refus.');
modifier_compte(array_replace($donnees,['nom'=>str_repeat('é',50),'telephone'=>'','adresse'=>'']));
verifier(consulter_compte()['nom']===str_repeat('é',50), 'Limite des champs en caractères UTF-8.');
verifier(consulter_compte()['telephone']===null && consulter_compte()['adresse']===null, 'Coordonnées facultatives vides.');
$historique=historique_commandes();
verifier(array_column($historique,'id_commande')===[3,1], 'Seulement ses commandes, les plus récentes en premier.');
verifier((float)$historique[1]['montant']===8.8, 'Montant historique 2×2,35+4,10, indépendamment du catalogue actuel.');
$detail=detail_commande_client('1');
verifier(count($detail['lignes'])===2 && (float)$detail['commande']['montant']===8.8, 'Détail et total de sa commande.');
verifier((float)$detail['lignes'][0]['prix_unitaire']===2.35, 'Prix payé conservé.');
verifier(detail_commande_client(2)===false, 'Commande étrangère inaccessible.');
foreach ([999,0,-1,[],"1 OR 1=1"] as $id) { verifier(detail_commande_client($id)===false, 'Identifiant invalide ou absent inaccessible.'); }
$bdd->exec("UPDATE commande SET statut='Validée' WHERE id_commande=1");
verifier(detail_commande_client(1)['commande']['statut']==='Validée', 'Statut relu en base.');
$_SESSION=['id'=>2,'role'=>'client'];
verifier(count(historique_commandes())===1, 'Isolation du second client.');
$bdd->exec('DELETE FROM commande WHERE id_utilisateur=2');
verifier(historique_commandes()===[], 'Historique vide accepté.');
$_SESSION=['id'=>1,'role'=>'admin'];
doit_refuser(fn()=>consulter_compte(), 'Espace réservé au client.');
$_SESSION=['id'=>999,'role'=>'client'];
doit_refuser(fn()=>consulter_compte(), 'Compte supprimé refusé.');
echo $nombre . " vérifications compte réussies.\n";
