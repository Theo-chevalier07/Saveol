<?php
// Une couleur différente pour chaque statut
$couleurs = array(
    'En attente' => 'warning',
    'Validée' => 'primary',
    'Expédiée' => 'info',
    'Terminée' => 'success',
    'Refusée' => 'danger'
);
?>
<span class="badge bg-<?php echo $couleurs[$commande['statut']]; ?>"><?php echo $commande['statut']; ?></span>
