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

function listerMatieres()
{
	$SQL="SELECT * FROM matiere";
	return parcoursRs(SQLSelect($SQL));
}

function listerFinitions()
{
	$SQL="SELECT * FROM finition";
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

function getPrix($id,$qteMin,$qteMax)
{
    $SQL="SELECT * FROM prix WHERE refFerrures='$id' 
    AND qteMin='$qteMin' AND qteMax='$qteMax'";
    return parcoursRs(SQLSelect($SQL));
}

function getQte($id)
{
    $SQL="SELECT DISTINCT qteMin,qteMax FROM prix WHERE refFerrures='$id' ORDER BY qteMin ASC";
    return parcoursRs(SQLSelect($SQL));
}

function getDim($id)
{
    $SQL="SELECT DISTINCT dimMin,dimMax FROM prix WHERE refFerrures='$id'";
    return parcoursRs(SQLSelect($SQL));
}

function getOptions($id)
{
    $SQL="SELECT * FROM `option` WHERE refFerrures='$id'";
    return parcoursRs(SQLSelect($SQL));
}

function rechercherFerrures($mot)
{
    $SQL="SELECT * FROM ferrures WHERE tags LIKE '%$mot%'";
    return parcoursRs(SQLSelect($SQL));
}

function getCompte($id)
{
	if($id==null){
		$SQL="SELECT * FROM utilisateur WHERE mdp IS NULL";
    return parcoursRs(SQLSelect($SQL));
	}
	else {
		$SQL="SELECT * FROM utilisateur WHERE id='$id'";
    return parcoursRs(SQLSelect($SQL));
	}

}

function accepterCompte($mdp, $idUser,$promouvoir)
{
   	$SQL="UPDATE utilisateur SET mdp='$mdp',admin='$promouvoir' WHERE id='$idUser'";
	return SQLUpdate($SQL);
}

function refuserCompte($idUser)
{
   	$SQL="DELETE FROM utilisateur WHERE id='$idUser'";
	return SQLDelete($SQL);
}

/****************************************************************************/

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

	$SQL="SELECT id FROM utilisateur WHERE mail='$login' AND mdp='$passe'";

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

function creerCompte($nom, $prenom, $mail, $telephone)
{
	$SQL="INSERT INTO utilisateur (nom, prenom, mail, telephone)  VALUES ('$nom', '$prenom', '$mail', '$telephone')";
	return SQLInsert($SQL);
} 


/********* PARTIE 3 *********/


	function ajouterPrix($prixU, $refFerrures,$qteMin,$qteMax,$dimMin, $dimMax)
	{
	    if($dimMin !=null && $dimMax !=null){
	        $SQL="INSERT INTO prix (dimMin, dimMax, prixU, refFerrures, qteMin,qteMax) VALUES ('$dimMin','$dimMax','$prixU','$refFerrures','$qteMin','$qteMax')";
	    }
	    else {
	        $SQL="INSERT INTO prix (prixU, refFerrures, qteMin, qteMax) VALUES ('$prixU','$refFerrures','$qteMin','$qteMax')";
	    }

	    return SQLInsert($SQL);
	}

	function ajouterDimension($min,$max, $refFerrures, $nom, $incluePrix)
	{
	    $SQL="INSERT INTO dimension (min, max, refFerrures, nom, incluePrix) VALUES ('$min','$max','$refFerrures','$nom','$incluePrix')";
	    return SQLInsert($SQL);
	}

	function ajouterOption($nom, $prix, $refFerrures)
	{
	    $SQL="INSERT INTO `option` (nom, prix, refFerrures) VALUES ('$nom','$prix','$refFerrures')";
	    return SQLInsert($SQL);
	}

	function creerFerrure1($refMatiere, $refFinition, $refcategories, $description,$titre,$tags)
	{
	    $SQL="INSERT INTO ferrures (refMatiere, refFinition, refcategories, description,titre,tags)  VALUES ('$refMatiere', '$refFinition', '$refcategories', '$description','$titre','$tags')";
	    return SQLInsert($SQL);
	}

	function creerFerrure2($id,$image, $numeroPlan, $planPDF)
	{
	    $SQL="UPDATE ferrures SET image='$image', numeroPlan='$numeroPlan', planPDF='$planPDF' WHERE id='$id'";
	    return SQLUpdate($SQL);
	}

	function supprimerFerrures($id)
	{
    $SQL="DELETE FROM dimension WHERE refFerrures='$id';
          DELETE FROM ferruresDevis WHERE refFerrures='$id';
          DELETE FROM `option` WHERE refFerrures='$id';
          DELETE FROM prix WHERE refFerrures='$id';
          DELETE FROM ferrures WHERE id='$id'";
    return SQLDelete($SQL); 
	}
	
	function getTabPrix($id){
        $SQL="SELECT dimMin,dimMax,prixU,qteMin,qteMax FROM `prix` WHERE refFerrures = '$id' ORDER BY qteMin ASC" ; 
        return parcoursRs(SQLSelect($SQL));
    }
    
    function creerCategorie($nomC,$couleur)
	{
		$SQL="INSERT INTO catalogue (nomCategorie,couleur)  VALUES ('$nomC','$couleur')";
		return SQLInsert($SQL);
	}
	
	function creerDevis($numDevis,$refCA,$nomProjet,$nomClient,$dateCreation,$etat)
	{
    	$SQL="INSERT INTO devis (numeroDevis,refCA,nomProjet,nomClient,dateCreation,etat)  VALUES ('$numDevis','$refCA','$nomProjet','$nomClient','$dateCreation','$etat')";
    	return SQLInsert($SQL);
	}
	
	function CommanderDevis($etat,$id)
	{
       	$SQL="UPDATE devis SET etat='$etat' WHERE id='$id'";
    	return SQLUpdate($SQL);
	}
	
	function suppFerrureDevis($idF)
	{
       	$SQL="DELETE FROM ferruresDevis WHERE id='$idF'";
    	return SQLDelete($SQL);
	}
	
	function listerDevis($id)
	{
		if($id!=null)$SQL="SELECT * FROM devis WHERE id='$id'";

		else $SQL="SELECT * FROM devis";

		return parcoursRs(SQLSelect($SQL));
	}
	
	function listerFerruresDevis($idDevis)
	{
		$SQL="SELECT * FROM ferruresDevis WHERE refDevis='$idDevis'";
		return parcoursRs(SQLSelect($SQL));
	}

	function listerDimensionsFerrure($idF) {
		$SQL="SELECT * FROM `dimension` WHERE refFerrures='$idF'"; 
        return parcoursRs(SQLSelect($SQL));
	}
	
	function listerCouleursFerrure() {
		$SQL="SELECT * FROM `couleursFerrures`"; 
        return parcoursRs(SQLSelect($SQL));
	}
	
	function calculerPrix($quantite,$idProduit,$dimension) {
		if ($dimension == null)
			$SQL="SELECT prixU FROM `prix` WHERE refFerrures='$idProduit' AND qteMin <= '$quantite' and qteMax >= '$quantite'";
		else
			$SQL="SELECT prixU FROM `prix` WHERE refFerrures='$idProduit' AND qteMin <= '$quantite' and qteMax >= '$quantite' AND dimMin <= '$dimension' AND dimMax >= '$dimension'";
		
        return SQLGetChamp($SQL);
	}

?>
