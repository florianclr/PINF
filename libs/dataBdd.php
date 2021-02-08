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

			case 'GET_Matieres' :

				$tab=listerMatieres();
				echo(json_encode($tab));
			break;

			case 'GET_Finitions' :

				$tab=listerFinitions();
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
				if ($tel = valider("tel")) {
					$tab = creerCompte($surname, $firstname, $mail, $tel);
					echo(json_encode($tab));
				}
			break;

			case 'GET_Prix' :
                if($idProduit=valider("idProduit"))
                if($qteMax=valider("qteMax"))
                $qteMin=valider("qteMin");
                if($qteMin=="")$qteMin=0;
                        $tab=getPrix($idProduit,$qteMin,$qteMax);
                        echo(json_encode($tab));
            break;

            case 'GET_Options' :
                if($idProduit=valider("idProduit"))
                $tab=getOptions($idProduit);
                echo(json_encode($tab));
            break;

            case 'GET_Rechercher' :
                if($keyword=valider("keyword"))
                $tab=rechercherFerrures($keyword);
                echo(json_encode($tab));
            break;

			case 'GET_Qte' :
                if($idProduit=valider("idProduit"))
                $tab=getQte($idProduit);
                echo(json_encode($tab));
            break;

            case 'GET_Dim' :
                if($idProduit=valider("idProduit"))
                $tab=getDim($idProduit);
                echo(json_encode($tab));
            break;
            
            case 'PUT_Info' :
				if ($value = valider("value"))
				if ($info = valider("info"))

				updateInfo($_SESSION['idUser'], $info, $value);
			break;

			case 'GET_CompteAttente' :
                if($admin=valider("admin")){
                	if($idUser=valider("idUser")){
                		$tab=getCompte($idUser);
                		echo(json_encode($tab));
                	}
                	else{
                		$tab=getCompte(null);
                		echo(json_encode($tab));
                	}
                }

            break;

			case 'GET_CompteAttente' :
                if($admin=valider("admin"))
                $tab=getCompte();
                echo(json_encode($tab));
            break;

			case 'PUT_Accepter' :
                if($admin=valider("admin") && $idUser=valider("idUser")){
                    $promouvoir=valider("promouvoir");
                	$bytes = random_bytes(3);
					$mdp=bin2hex($bytes);
					$tab=accepterCompte($mdp,$idUser,$promouvoir);
                }
                echo(json_encode($tab));
            break;
            
            case 'DELETE_Refuser' :
                if($admin=valider("admin") && $idUser=valider("idUser")){
					$tab=refuserCompte($idUser);
                }
                echo(json_encode($tab));
            break;

            case 'DELETE_Ferrure' : 
            	if($idFerrure=valider("id"))
            		$tab = supprimerFerrures($idFerrure); 
            		echo(json_encode($tab)); 
            break ; 

            case 'POST_Ferrure1' : 
            	if($titre=valider("titre"))
            	if($description=valider("description"))
            	if($tags=valider("tags"))
            	if($refCategorie=valider("categorie"))
            	if($refMatiere=valider("matiere"))
            	if($refFinition=valider("finition")){
            	//creerFerrure1($refMatiere, $refFinition, $refcategories, $description,$titre,$tags)
            		$id = creerFerrure1($refMatiere, $refFinition, $refCategorie, $description,$titre,$tags);
            		echo(json_encode($id));
            	}
            break ; 

            case 'POST_Dimension' : 
                $incluePrix=valider("incluePrix"); 
                if($incluePrix=="")$incluePrix=0;

            	if($nom=valider("nom"))
            	if($min=valider("min"))
            	if($max=valider("max"))
            	if($refFerrure=valider("refFerrure")){
            	//function ajouterDimension($min,$max, $refFerrures, $nom, $incluePrix)
            		$tab =  ajouterDimension($min,$max, $refFerrure, $nom, $incluePrix);
            		echo(json_encode($tab));
            	}
            break ; 

            case 'POST_Option' : 
            	if($nom=valider("nom"))
            	if($prix=valider("prix"))
            	if($refFerrure=valider("refFerrure")){
            	//function ajouterOption($nom, $prix, $refFerrures)
            		$tab = ajouterOption($nom, $prix, $refFerrure);
            		echo(json_encode($tab));
            	}

            case 'POST_Prix' :
            	if($qteMin=valider("qteMin"))
            	if($qteMax=valider("qteMax"))
            	if($prix=valider("prix"))
            	if($refFerrure=valider("refFerrure")){
            		if( ($dimMin=valider("dimMin")) && ($dimMax=valider("dimMax")) ){
            			$tab=ajouterPrix($prix, $refFerrure,$qteMin,$qteMax,$dimMin,$dimMax);
            		}
            		else{
            			$tab=ajouterPrix($prix, $refFerrure,$qteMin,$qteMax,null,null);
            		}
            	echo(json_encode($tab));	
            	}
            break ; 

            case 'PUT_Ferrure2' :
            	if($img=valider("img"))
            	if($pdf=valider("pdf"))
            	if($numPlan=valider("numPlan"))
            	if($refFerrure=valider("refFerrure")){
            	//function creerFerrure2($id,$image, $numeroPlan, $planPDF)
            		$tab = creerFerrure2($refFerrure,$img, $numPlan, $pdf);
            		echo(json_encode($tab));
            	}
            break ; 

		}
}

?>
