<?php

include_once("maLibSQL.pdo.php");
/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/


/********* PARTIE 1 : prise en main de la base de données *********/


// inclure ici la librairie faciliant les requêtes SQL


function listerCategories()
{
	$SQL="SELECT * FROM catalogue";
	return parcoursRs(SQLSelect($SQL));
}

function listerArticles($categorie,$nombre)
{

	if($categorie != null && $nombre != null ){
		$SQL="SELECT ferrures.* FROM ferrures,catalogue WHERE ferrures.refcategories=catalogue.id AND catalogue.nomCategorie='$categorie' LIMIT $nombre" ;
	}

	else if($categorie != null && $nombre == null){ 
	$SQL="SELECT ferrures.* FROM ferrures,catalogue WHERE ferrures.refcategories=catalogue.id AND catalogue.nomCategorie='$categorie' " ;
	}
	
	else if($categorie == null && $nombre == null){ 
	$SQL="SELECT ferrures.* FROM ferrures" ;
	}

	return parcoursRs(SQLSelect($SQL));

}

function getProduit($id)
{

    $SQL="SELECT ferrures.*, matiere.nomM, finition.nomF FROM ferrures,finition,matiere WHERE finition.id=ferrures.refFinition AND matiere.id=ferrures.refMatiere AND ferrures.id='$id'";
    return parcoursRs(SQLSelect($SQL));
}

function interdireUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à vrai pour l'utilisateur concerné 
}

function autoriserUtilisateur($idUser)
{
	// cette fonction affecte le booléen "blacklist" à faux pour l'utilisateur concerné 
}

function verifUserBdd($login,$passe)
{
	// Vérifie l'identité d'un utilisateur 
	// dont les identifiants sont passes en paramètre
	// renvoie faux si user inconnu
	// renvoie l'id de l'utilisateur si succès

	$SQL="SELECT id FROM utilisateur WHERE nom='$login' AND mdp='$passe'";

	return SQLGetChamp($SQL);
	// si on avait besoin de plus d'un champ
	// on aurait du utiliser SQLSelect
}


function isAdmin($idUser)
{
	// vérifie si l'utilisateur est un administrateur
	$SQL ="SELECT admin FROM utilisateur WHERE id='$idUser'";
	return SQLGetChamp($SQL); 
}

/********* PARTIE 2 *********/

function mkUser($pseudo, $passe,$admin=false,$couleur="black")
{
	// Cette fonction crée un nouvel utilisateur et renvoie l'identifiant de l'utilisateur créé
}

function connecterUtilisateur($idUser)
{
	// cette fonction affecte le booléen "connecte" à vrai pour l'utilisateur concerné 
	$SQL ="UPDATE utilisateur SET connecte='1' WHERE id='$idUser'"; 
	SQLUpdate($SQL);
}

function deconnecterUtilisateur($idUser)
{
	// cette fonction affecte le booléen "connecte" à faux pour l'utilisateur concerné 
	$SQL ="UPDATE utilisateur SET connecte='0' WHERE id='$idUser'"; 
	SQLUpdate($SQL);
}

function changerPasse($idUser,$passe)
{
	// cette fonction modifie le mot de passe d'un utilisateur
}

function changerPseudo($idUser,$pseudo)
{
	// cette fonction modifie le pseudo d'un utilisateur
}

function promouvoirAdmin($idUser)
{
	// cette fonction fait de l'utilisateur un administrateur
}

function retrograderUser($idUser)
{
	// cette fonction fait de l'utilisateur un simple mortel
}

function getInfo($idUser, $info)
{
	$SQL="SELECT $info FROM utilisateur WHERE id='$idUser'";
	return SQLGetChamp($SQL);
}

function updateInfo($idUser, $info, $value)
{
	$SQL="UPDATE utilisateur SET $info='$value' WHERE id='$idUser'";
	SQLUpdate($SQL);
}

function creerCompte($nom, $prenom, $mdp, $mail, $telephone, $admin)
{
	$SQL="INSERT INTO utilisateur (nom, prenom, mdp, mail, telephone, admin)  VALUES ('$nom', '$prenom', '$mdp', '$mail', '$telephone', '$admin')";
	SQLInsert($SQL);
}

/********* PARTIE 3 *********/

function listerUtilisateursConnectes()
{
	// Liste les utilisteurs connectes
}
?>
