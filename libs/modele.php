<?php

include_once("maLibSQL.pdo.php");
/*
Dans ce fichier, on définit diverses fonctions permettant de récupérer des données utiles pour notre TP d'identification. Deux parties sont à compléter, en suivant les indications données dans le support de TP
*/

function listerCategories($cat)
{
    if($cat!=null){
        $SQL="SELECT couleur FROM catalogue WHERE nomCategorie='$cat'";
        return parcoursRs(SQLSelect($SQL));
    }
    else {
        $SQL="SELECT * FROM catalogue";
        return parcoursRs(SQLSelect($SQL));
    }
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

function getDims($id)
{
    $SQL="SELECT DISTINCT * FROM dimension WHERE refFerrures='$id'";
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

function getCompte($id,$etat)
{
    if($etat==1){
        $SQL="SELECT * FROM utilisateur WHERE mdp IS NULL";
    	return parcoursRs(SQLSelect($SQL));
    }
    if($etat==2) {
        $SQL="SELECT * FROM utilisateur WHERE id='$id'";
    	return parcoursRs(SQLSelect($SQL));
    }
    if($etat==3) {
        $SQL="SELECT * FROM utilisateur WHERE admin='1' OR admin='2'";
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

function destinataire($idUser,$idUserD)
{
    $SQL="UPDATE utilisateur SET admin='2' WHERE id='$idUserD';
          UPDATE utilisateur SET admin='1' WHERE id='$idUser';";
    return SQLUpdate($SQL);
}

/****************************************************************************/

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

function getInfo($idUser, $info)
{
	$SQL="SELECT $info FROM utilisateur WHERE id='$idUser'";
	return SQLGetChamp($SQL);
}

function updateInfo($idUser, $info, $value)
{
	$SQL="UPDATE utilisateur SET $info='$value' WHERE id='$idUser'";
	return SQLUpdate($SQL);
}

function creerCompte($nom, $prenom, $mail, $telephone)
{
	$SQL="INSERT INTO utilisateur (nom, prenom, mail, telephone)  VALUES ('$nom', '$prenom', '$mail', '$telephone')";
	return SQLInsert($SQL);
} 


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
	
	function listerFerruresDevis($idDevis)
	{
		$SQL="SELECT * FROM ferruresDevis WHERE refDevis='$idDevis'";
		return parcoursRs(SQLSelect($SQL));
	}
	
	function getDevisUser($idUser){
		if($idUser != null)
			$SQL="SELECT * FROM `devis` WHERE refCA='$idUser' ORDER BY etat"; 
		else
			$SQL="SELECT * FROM `devis` ORDER BY etat"; 
		return parcoursRs(SQLSelect($SQL));
	}
	
	function getInfosDevis($idDevis){
		$SQL="SELECT * FROM `devis` WHERE id='$idDevis'"; 
		return parcoursRs(SQLSelect($SQL));
	}
	
	function getNomUsers() {
    	$SQL="SELECT DISTINCT(nom),prenom,utilisateur.id FROM devis, utilisateur WHERE utilisateur.id=devis.refCA";
    	return parcoursRs(SQLSelect($SQL));
	}
	
	function majEtat($etat, $idDevis)
	{
       	$SQL="UPDATE devis SET etat='$etat' WHERE id='$idDevis'";
    	return SQLUpdate($SQL);
	}

    function addCommentaire($commentaire, $idDevis)
	{
       $SQL="UPDATE devis SET commentaire='$commentaire' WHERE id='$idDevis'";
		return SQLUpdate($SQL);
	}

    function majDateLivraison($date, $idDevis)
	{
       $SQL="UPDATE devis SET dateLivraison='$date' WHERE id='$idDevis'";
    	return SQLUpdate($SQL);
	}
	
	function deleteFerrureDevis($idFerrureDevis,$idDevis) {

        $SQL="UPDATE devis SET PrixTotal=PrixTotal-(SELECT prix FROM ferruresDevis WHERE id='$idFerrureDevis') WHERE id='$idDevis';
               DELETE FROM ferruresDevis WHERE id='$idFerrureDevis'";
        return SQLUpdate($SQL); 

    }
	
	/***********************************************************/

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

	function ajouterAuDevis($refFerrures, $refDevis, $quantite, $a, $b, $c, $prix,$couleur)
	{
		$SQL="INSERT INTO ferruresDevis (refFerrures,refDevis,quantite,a,b,c,prix,couleur) VALUES ('$refFerrures','$refDevis','$quantite','$a','$b','$c','$prix','$couleur');
			UPDATE devis SET PrixTotal=PrixTotal+$prix WHERE id='$refDevis'";
		
		return SQLUpdate($SQL);
	}
	
	/***********************************************************/
	
	function getDevisEnAttente() {
		$SQL="SELECT * FROM devis WHERE dateLivraison IS NULL AND etat IN('COMMANDE_VALIDÉE','EN_FABRICATION','FABRIQUÉ')";
		return parcoursRs(SQLSelect($SQL));
	}

	function getDevisPlanifies() {
		$SQL="SELECT * FROM devis WHERE dateLivraison IS NOT NULL";
		return parcoursRs(SQLSelect($SQL));
	}

	function planifierDevis($id, $date) {
		$SQL="UPDATE devis SET dateLivraison='$date' WHERE id='$id'";
		return SQLUpdate($SQL);
	}

    function annulerDevis($idDevis) {
   		$SQL="UPDATE devis SET dateLivraison=NULL WHERE id='$idDevis'";
		return SQLDelete($SQL);
	}	

    function getMailClient($idDevis) {
        $SQL="SELECT mail 
        FROM utilisateur, devis 
        WHERE utilisateur.id = devis.refCA
        	AND devis.id='$idDevis'";
        return SQLGetChamp($SQL);
    }

?>
