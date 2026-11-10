<?php
define('SAVEOL', true);
session_start([
    'use_strict_mode' => true,
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'cookie_secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
]);
require 'includes/authentification.php';
require 'includes/modele/gestion_bdd.php';
require 'controleurs/c_principal.php';
