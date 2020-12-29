<?php
session_start();
include_once("modele.php");
include_once ("maLibUtils.php");
include_once ("maLibSecurisation.php"); 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");

$method = $_SERVER["REQUEST_METHOD"];
$request = false ; 

if ($action = valider("action"))
{

	$request .= $method . "_" . $action ; 
	//die($request); 

	switch($request)
		{	
			case 'GET_Connexion' : 
									if ($login = valider("login"))
									if ($passe = valider("passe")){

									if(verifUser($login,$passe)){

										//die($_SESSION['idUser']); // pour debug !!!!!!
										if (valider("remember")) {
										setcookie("login",$login , time()+60*60*24*30,"/");
										setcookie("passe",$passe, time()+60*60*24*30,"/");
										setcookie("remember",true, time()+60*60*24*30,"/");
										} 
										else {
										setcookie("login","", time()-3600,"/");
										setcookie("passe","", time()-3600,"/");
										setcookie("remember",false, time()-3600,"/");
										//die($_SESSION['idUser']); // pour debug !!!!!!

										}
										// On écrit seulement après cette entête

										echo($_SESSION['idUser']); 
									}
								}
								//die("test");
								
			break ; 

			case 'GET_Deconnexion' :
				if($_SESSION["connecte"] = true){
					$deco=deconnecterUtilisateur($_SESSION['idUser']);
					echo(session_destroy());
				}
			break;

			case 'GET_Categories' :
				$tab=listerCategories();
				echo(json_encode($tab));
			break;

			case 'GET_Articles' :
				$tab=listerArticles();
				echo(json_encode($tab));
			break;

			case 'GET_Produits' :
				if($categorie=valider("categorie"))
				$tab=listerProduits($categorie);
				die($categorie);
				echo(json_encode($tab));
			break;


		}
}

?>
