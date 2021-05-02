<?php

include_once "maLibUtils.php";	// Car on utilise la fonction valider()
include_once "modele.php";	// Car on utilise la fonction connecterUtilisateur()

/**
 * @file login.php
 * Fichier contenant des fonctions de vérification de logins
 */

/**
 * Cette fonction vérifie si le login/passe passés en paramètre sont légaux
 * Elle stocke les informations sur la personne dans des variables de session : session_start doit avoir été appelé...
 * Infos à enregistrer : pseudo, idUser, heureConnexion, isAdmin
 * Elle enregistre l'état de la connexion dans une variable de session "connecte" = true
 * @pre login et passe ne doivent pas être vides
 * @param string $login
 * @param string $password
 * @return false ou true ; un effet de bord est la création de variables de session
 */


function verifUser($login,$password)
{
	$hash = getMdp($login);
	if(password_verify($password,$hash)==1){
		$id = verifUserBdd($login);
	}
	else 
		$id = null ; 

	if (!$id) return false; 
	$tab=getCompte($id, 2);

	// Cas succès : on enregistre pseudo, idUser dans les variables de session 
	// il faut appeler session_start ! 
	// Le controleur le fait déjà !!
	connecterUtilisateur($id);
	$_SESSION["pseudo"] =$tab[0]["nom"]." ".$tab[0]["prenom"];
	$_SESSION["idUser"] = $id;
	$_SESSION["connecte"] = true;
	$_SESSION["heureConnexion"] = date("H:i:s");
	$_SESSION["isAdmin"] = isAdmin($id);
	$_SESSION["mail"] = $login ; 

	return true;	
}

function changeMdp($oldPassword,$newPassword){

	$login = $_SESSION["mail"] ; 
	$hash = getMdp($login);

	if(password_verify($oldPassword,$hash)==1){

		$newHash=password_hash($newPassword,PASSWORD_DEFAULT,['cost' => 12]); 
		updateInfo($_SESSION['idUser'],"mdp", $newHash);
		return true ; 
	}

	else
		return false ; 
}

?>
