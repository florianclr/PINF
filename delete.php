<?php
session_start();
include_once("libs/modele.php");
include_once ("libs/maLibUtils.php");
include_once ("libs/maLibSecurisation.php");


$admin = valider("isAdmin","SESSION");

	if ($chemin = valider("lien"))
	if($admin ==1 || $admin ==2){ 
		echo $chemin;
		echo "string"; 
		echo unlink($chemin);

		echo "SUPRESSION";
	}

?>
