<?php
$PARAM_hote='localhost'; // le chemin vers le serveur
$PARAM_port='';
$PARAM_nom_bd='saveol'; // le nom de votre base de donnees
$PARAM_utilisateur='root'; // nom d'utilisateur pour se connecter
$PARAM_mot_passe=''; // mot de passe de l'utilisateur pour se connecter

try
{
    $bdd = new PDO('mysql:host='.$PARAM_hote.';dbname='.$PARAM_nom_bd.';charset=utf8mb4', $PARAM_utilisateur, $PARAM_mot_passe);
    // Affiche les erreurs SQL et renvoie les lignes sous forme de tableau
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}

catch(Exception $e)
{
        echo 'Erreur : '.$e->getMessage().'<br />';
        echo 'No : '.$e->getCode();
}
?>
