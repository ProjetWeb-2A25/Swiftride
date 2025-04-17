<?php

include("C:/xampp/htdocs/Projet 2A25/MVC/Controller/VehiculeC.php");
include("C:/xampp/htdocs/Projet 2A25/MVC/Controller/LocationC.php");

$vc = new VehiculeC();
$vc->deleteVehicule($_GET["ID_Voiture"]);

$lc = new LocationC();
$lc->deleteLocation($_GET["ID_Location"]);

header("Location: Back_end/elbackend/backend.php");
exit();

?>
