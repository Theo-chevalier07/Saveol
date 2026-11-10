<?php
if (!defined('SAVEOL')) { http_response_code(403); exit; }
$PARAM_hote = 'localhost';
$PARAM_port = '';
$PARAM_nom_bd = 'saveol';
$PARAM_utilisateur = 'root';
$PARAM_mot_passe = '';

try {
    $dsn = 'mysql:host=' . $PARAM_hote . ';dbname=' . $PARAM_nom_bd . ';charset=utf8mb4';
    if ($PARAM_port !== '') { $dsn .= ';port=' . $PARAM_port; }
    $bdd = new PDO($dsn, $PARAM_utilisateur, $PARAM_mot_passe, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    http_response_code(503);
    exit('La base de données est indisponible. Réessayez plus tard.');
}
