<?php
session_start();
include_once("modele.php");
include_once ("maLibUtils.php");
include_once ("maLibSecurisation.php"); 


if ($login = valider("login"))
if ($passe = valider("passe")){

	if(verifUser($login,$passe)){
		echo $_SESSION['idUser'];
		if (valider("remember")) {
		setcookie("login",$login , time()+60*60*24*30);
		setcookie("passe",$passe, time()+60*60*24*30);
		setcookie("remember",true, time()+60*60*24*30);
		} 
		else {
		setcookie("login","", time()-3600);
		setcookie("passe","", time()-3600);
		setcookie("remember",false, time()-3600);
		}
	}
return $_SESSION['idUser'];
}




?>
