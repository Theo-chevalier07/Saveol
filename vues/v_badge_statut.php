<?php
// Une couleur différente pour chaque statut
$couleurs = array(
    'En attente' => 'statut-attente',
    'Validée' => 'statut-validee',
    'Expédiée' => 'statut-expediee',
    'Terminée' => 'statut-terminee',
    'Refusée' => 'statut-refusee'
);
?>
<span class="statut <?php echo $couleurs[$commande['statut']]; ?>"><?php echo $commande['statut']; ?></span>
