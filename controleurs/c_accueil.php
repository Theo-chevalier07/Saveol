<?php
if (!defined('SAVEOL')) {
    exit;
}

if (!isset($_REQUEST['action'])){
    $action = 'afficher';
}
else {
    $action = $_REQUEST['action'];
}

switch($action) {
    case "afficher": {
        $lesProduitsEnAvant = produitsEnAvant();
        require "vues/v_accueil.php";
        break;
    }
}
?>