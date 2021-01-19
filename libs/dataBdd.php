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

			case 'GET_Info' :
				if ($value = valider("value"))
				if ($info = valider("info"))
					
				updateInfo($_SESSION['idUser'], $info, $value);
			break;

			case 'GET_Categories' :

				$tab=listerCategories();
				echo(json_encode($tab));
			break;

			case 'GET_Articles' :

			if($categorie = valider("categorie") && $nombre = valider("nombre")){ 
				$tab=listerArticles($categorie,$nombre);
			}

			else if ($categorie = valider("categorie")){
				$tab=listerArticles($categorie,null);
			}

			else
				$tab = listerArticles(null,null); // on veut toutes les ferures
			

				echo(json_encode($tab));
			break;

			case 'GET_Produit' :
                if($idProduit=valider("idProduit"))
                $tab=getProduit($idProduit);
                echo(json_encode($tab));
            break;

            case 'POST_Compte' :
            	if ($surname = valider("surname"))
				if ($firstname = valider("firstname"))
				if ($mail = valider("mail"))
				if ($tel = valider("tel"))
				if ($admin = valider("admin"))

				creerCompte($surname, $firstname, "str", $mail, $tel, $admin);

			break;



		}
}

?>
