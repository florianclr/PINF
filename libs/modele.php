<?php

include_once("maLibSQL.pdo.php");
/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/

function getProduit($id)
{

	$SQL="SELECT * FROM ferrures WHERE id='$id'";
	return parcoursRs(SQLSelect($SQL));
}

function listerCategories()
{

	$SQL="SELECT * FROM catalogue";
	return parcoursRs(SQLSelect($SQL));
}

function listerArticles()
{

	$SQL="SELECT * FROM ferrures";
	return parcoursRs(SQLSelect($SQL));
}

function listerProduits($categorie)
{

	$SQL="SELECT * FROM ferrures WHERE refcategories ='$categorie'";
	return parcoursRs(SQLSelect($SQL));
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

?>
