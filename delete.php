<?php
include_once("libs/modele.php");
include_once ("libs/maLibUtils.php");
include_once ("libs/maLibSecurisation.php"); 

	if ($chemin = valider("lien")){ 
		//echo unlink($lien); 
		//split ( string $pattern , string $string , int $limit = -1 ) : array
		$lien = explode("PINFV2/",$chemin); 
		tprint($lien);
		echo unlink($lien[1]); 

		echo "SUPRESSION";
	}

?>