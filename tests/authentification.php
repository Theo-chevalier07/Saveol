<?php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
// Base de test en mémoire : aucune modification de la base Saveol.
session_start(['use_cookies' => false, 'cache_limiter' => '']);
define('SAVEOL', true);
$racine = $argv[1] ?? dirname(__DIR__);
$erreurs = [];
$nombre = 0;
function verifier($condition, $message) {
    global $erreurs, $nombre;
    $nombre++;
    if (!$condition) { $erreurs[] = $message; }
}
if (!is_file($racine . '/includes/authentification.php')) {
    fwrite(STDERR, "ECHEC : la protection des accès par rôle est absente.\n");
    exit(1);
}
require $racine . '/includes/authentification.php';
require $racine . '/includes/modele/gestion_bdd.php';
$bdd = new PDO('sqlite::memory:');
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$bdd->exec('CREATE TABLE utilisateur (id_utilisateur INTEGER PRIMARY KEY, nom TEXT, prenom TEXT, email TEXT, mot_de_passe TEXT, role TEXT)');
$insertion = $bdd->prepare('INSERT INTO utilisateur VALUES (?, ?, ?, ?, ?, ?)');
$hash = '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08';
foreach (['client', 'gestionnaire', 'admin', 'inconnu'] as $id => $role) {
    $insertion->execute([$id + 1, 'Test', 'Compte', $role . '@example.test', $hash, $role]);
}
$_SESSION = [];
verifier(!utilisateur_connecte(), 'Un visiteur ne doit pas être connecté.');
verifier(!acces_autorise('panier'), 'Un visiteur ne doit pas accéder au panier.');
foreach (['client' => 'accueil', 'gestionnaire' => 'gestion_commandes', 'admin' => 'gestion_comptes'] as $role => $page) {
    $_SESSION = [];
    $ancienId = session_id();
    verifier(verifier_identification($role . '@example.test', 'test'), 'Connexion SHA-256 : ' . $role);
    verifier(session_id() !== $ancienId, 'La connexion doit renouveler la session.');
    verifier(utilisateur_connecte(), 'La session doit être reconnue.');
    verifier($_SESSION['id'] > 0 && $_SESSION['role'] === $role, 'Identité issue de la base.');
    verifier(!isset($_SESSION['mot_de_passe']), 'Le hash ne doit pas être stocké en session.');
    verifier(page_apres_connexion($role) === $page, 'Espace de destination : ' . $role);
    verifier(acces_autorise('accueil'), 'Accueil accessible après connexion.');
    verifier(acces_autorise('gestion_commandes') === ($role !== 'client'), 'Protection des commandes.');
    verifier(acces_autorise('gestion_catalogue') === ($role !== 'client'), 'Protection du catalogue.');
    verifier(acces_autorise('gestion_comptes') === ($role === 'admin'), 'Protection administrateur.');
    verifier(acces_autorise('panier') === ($role === 'client'), 'Panier réservé au client.');
    verifier(!acces_autorise('../../includes/modele/connexion'), 'Une route arbitraire doit être refusée.');
}
$_SESSION = [];
verifier(!verifier_identification('client@example.test', 'incorrect'), 'Refus du mauvais mot de passe.');
verifier(!utilisateur_connecte(), 'Un échec ne doit pas créer de session connectée.');
verifier(!verifier_identification("' OR 1=1 --", 'test'), 'Refus de l’injection SQL.');
verifier(!verifier_identification('absent@example.test', 'test'), 'Refus du compte absent.');
verifier(!verifier_identification('inconnu@example.test', 'test'), 'Refus des rôles inconnus.');
verifier(!verifier_identification([], 'test'), 'Refus d’un email non scalaire.');
verifier(!verifier_identification('client@example.test', []), 'Refus d’un mot de passe non scalaire.');
$jeton = jeton_csrf();
verifier(strlen($jeton) === 64 && jeton_csrf() === $jeton, 'Jeton stable et aléatoire pour le formulaire.');
verifier(jeton_csrf_valide($jeton), 'Accepter le bon jeton.');
verifier(!jeton_csrf_valide('incorrect') && !jeton_csrf_valide([]) && !jeton_csrf_valide(null), 'Refuser les jetons invalides.');
if ($erreurs) { fwrite(STDERR, implode("\n", $erreurs) . "\n"); exit(1); }
echo $nombre . " vérifications réussies.\n";
