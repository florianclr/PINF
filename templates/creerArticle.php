<?php
$idArticle = valider("idArticle"); // TODO : rendre un peu plus securiser
$idCategorie = valider("idCategorie")
?>

	 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link href="css/createArticle.css" rel="stylesheet">  
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript">

//************************** VAR GLOBALE **************************//
	var DimIncluPrix = 0 ;  // boolean pour savoir si le prix est inclue 
  var stopAjout = 0 ;  // boolean afin de savoir si il faut stopper l'ajout d'une ferrure ds la bdd
  var preview = 0;  // boolean indiquant si une preview de l'image de la ferrure est présente 
//************************** MODELE JQUERY DONNÉES FERRURES**************************//

 	var jInputTitre = $('<label for="titre"> Titre: </label><br><input type="text" id="titre"> <br><br>'); 

 	var jInputOption = $('<label for="option">Option:</label> <input type="text" id="option"><img class="icon" src="./ressources/plus2.png""><br>'); 

 	var jInputNumber = $('<input type="number" min="1">');

 	var jInputDimensions = $('<input type="checkbox">'); 

 	var jTextareaDescription = $('<label for="description">Description: </label>' +'<br>'
 								+'<textarea id="description"></textarea><br>');

 	var jTextareaMotCles = $('<label for="motsCles">Mot clés: </label>' +'<br>' 
 							+'<textarea id="motsCles"></textarea>' +'<br>'
 							+'<span>&#9888; veuillez séparer chaque mots clés par une point virgule</span><br><br>');

 	var jSelectCateg = $('<br><label for="categorie">Catégorie: </label> <select id="categorie"> </select><br>'); 

 	var jSelectMatiere = $('<br><label for="matiere">Matière: </label> <select id="matiere"> </select><br>'); 

 	var jSelectFinition = $('<br><label for="finition">Finition: </label> <select id="finition"> </select><br>'); 

  var jInputFile = $('<form method="post" action="" enctype="multipart/form-data" id="formPdf">')
                  .append($('<label for="file"> Importer plan PDF: </label> <input id="file" name="file" type="file"><br>'));

  var jImg =$('<form method="post" action="" enctype="multipart/form-data" id="formImg">')
            .append($('<label for="imgImport"> Importer une image: </label><br>' 
                    +'<img id="imgImport" src="./ressources/image.png"><br>'))
            .append($('<input type="file" id="file" name="file"/><br><br>').change(function(){
                    
                    if(preview == 1){ // supprime l'ancienne image 
                      console.log("supression img..."); 
                      var lien= $("#imgImport").prop("src");
                      requestSuprImg(lien); 
                    }
                    requestUploadFile("preview"); 
                    //requestAddFilesFerrures();
            })); 

 	  // SOURCE IMPORTER IMAGE : https://makitweb.com/how-to-upload-image-file-using-ajax-and-jquery/ + w3School

 	  //------------------------PARTIE PRIX ET DIMENSIONS -------------------------//

 	var jOptionSelect = $('<option></option>'); 

 	var jSubmit = $('<input type="submit">');

 	var jTableauPrix = $('<br><label for="tabPrix"> Tableau des prix: </label>'
 						+'<table id="tabPrix" class="tabAjout w3-table-all">' 
					    +'<tr class="nomCol">'
					    +'<th>Quantite minimale</th>'
					    +'<th>Quantite maximale</th>'
					    +'<th>Prix unitaires</th>'
              +'<th style="text-align: center;"><img class="icon ajoutLignetabPrix" src="./ressources/plus2.png""></th>'
					    +'</tr>'
					    +'</table>'
					    +'<br><br><br><br><br><br>'); 

 	var jLigneTableauPrix = $('<tr class="ligne">'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
                 +'<td style="text-align: center;"><img class="icon suprLigneTabPrix" src="./ressources/moins.png""></td>'
						     +'</tr>');
 	// source image : https://pixabay.com/fr/vectors/icône-symbole-plus-rouge-croix-1970474/

 	var jTableauOption = $('<br><label for="tabOption">Tableau des options: </label>'
 						+'<table id="tabOption" class="tabAjout w3-table-all">' 
					    +'<tr>'
					    +'<th>Nom</th>'
					    +'<th>Prix unitaire</th>'
              +'<th style="text-align: center;"><img class="icon ajoutLignetabOptions" src="./ressources/plus2.png""></th>'
					    +'</tr>'
					    +'</table>'
					    +'<br><br><br><br><br><br>');

 	// tabelau des options // 
 	var jLigneTableauOption = $('<tr>'
						     +'<td><input type="text"></td>'
						     +'<td><input type="number"></td>'
                 +'<td style="text-align: center;"><img class="icon suprLigneTabOptions" src="./ressources/moins.png""></td>'
						     +'</tr>');

 	// tabelau des dimensions // 
 	var jTableauDimensions = $('<label for="tabDims"> Tableau des dimensions: </label>'
 						+'<table id="tabDims" align="right" class="tabAjout w3-table-all">' 
					    +'<tr>'
					    +'<th>Dimension</th>'
					    +'<th>Min</th>'
					    +'<th>Max</th>'
					    +'</tr>'
					    // DIM 1 //
					    +'<tr>'
						     +'<td><input type="checkbox" class="addDim" id="dim1" name="a" value="a">'
  							 +'<label for="dim1"> a </label></td>'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
						     //+'<td  style="text-align: center;"><img class="iconAjout2 ajoutLignetabDims" src="./ressources/plus2.png""></td>'
						+'</tr>'
						// DIM 2 //
						+'<tr>'
						     +'<td><input type="checkbox" class="addDim" id="dim2" name="b" value="b">'
  							 +'<label for="dim2"> b </label></td>'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
						     //+'<td  style="text-align: center;"><img class="iconAjout2 ajoutLignetabDims" src="./ressources/plus2.png""></td>'
						+'</tr>'

						// DIM 3 //
						+'<tr>'
						     +'<td><input type="checkbox" class="addDim" id="dim3" name="c" value="c">'
  							 +'<label for="dim3"> c </label></td>'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
						     //+'<td  style="text-align: center;"><img class="iconAjout2 ajoutLignetabDims" src="./ressources/plus2.png""></td>'
						+'</tr>'

					    +'</table>'
					    +'<br><br><br><br><br><br><br><br><br>'); 

 	var jIconSupr = $('<img src="./ressources/moins.png">');


 	var jCheckB = $('<input type="checkbox" class="nomDim" name="b" value="b">'); 
 	var jCheckC = $('<input type="checkbox" class="nomDim" name="b" value="b">');

 	var jInputRadioDimPrix = $('<div id="a"><label for"dimensionPrix"> a </label> <input type="radio" name="dimensionPrix" value="a"></div>'); 
 	var jInputRadioDimPrix2 = $('<div id="b"><label for"dimensionPrix"> b </label> <input type="radio" name="dimensionPrix" value="b"></div>'); 
 	var jInputRadioDimPrix3 = $('<div id="c"><label for"dimensionPrix"> c </label> <input type="radio" name="dimensionPrix" value="c"></div>'); 

 	
 	var jBtnModePrix = $('<br><br><br><input type="button" value="Inclure une dimension dans le prix">').click(function(){
 		 if($(this).val() == "Inclure une dimension dans le prix"){
 		  DimIncluPrix = 1 ; 
 			$(this).val("Ne plus inclure la dimension dans le prix");
 			$("#modeDimensionsPrix").show();  
 			$("#tabPrix .nomCol").prepend('<th class="modeDimPrix">Dim Min</th><th class="modeDimPrix">Dim Max</th>'); 
 			$("#tabPrix .ligne").prepend('<td class="modeDimPrix"><input type="number"></td><td class="modeDimPrix"><input type="number"></td>'); 	
 		}

 		else if(($(this).val()) == "Ne plus inclure la dimension dans le prix"){
 			DimIncluPrix = 0 ; 
 			$("#modeDimensionsPrix").hide();
 			$(this).val("Inclure une dimension dans le prix"); 
 			$("#tabPrix .modeDimPrix").remove(); 
 		}
 	});

 	var jDimPrix =  $('<div id="modeDimensionsPrix">')	
 					.append("<h6>Le prix dépende de :</h6>")
 					.append('<div id="a"><label for"dimensionPrix"> a </label> <input type="radio" name="dimensionPrix" value="a"></div>')
 					.append('<div id="b"><label for"dimensionPrix"> b </label> <input type="radio" name="dimensionPrix" value="b"></div>')
 					.append('<div id="c"><label for"dimensionPrix"> c </label> <input type="radio" name="dimensionPrix" value="c"></div>'); 

 	var jBrTabOption = $('<div class="jBrTabOption"></br></br></div>');
 	var jBrTabPrix = $('<div class="jBrTabPrix"></br></br></div>');

//************************** VALIDATION **************************//

 	var jInputCreerFer = $('</br><input class="create" type="submit" value="CREER">').click(function(){
 		// 1 requête par table 
    resetErreur(); 
 		requestCreerFerrure(); // qui appelle : 
 		 //requestCreateDimsFerrures(); OK
 		 //requestCreatePrixFerrures(); OK
 		 //requestCreteOptionsFerrures(); OK
 		 //requestAddImgFerrure(); 
 		//requestAddPdfFerrures();  
 		// TODO : si erreur annuler  l'insertion et supprimer de la bdd tout ce qui a été supprimer 
 	}); 

//************************** GÉNÉRATIONS DES MODÈLES **************************//

 	 $(document).ready(function(){ // Lorsque le document est chargé 

 	 	$(".contenuArticle1").append(jInputTitre.clone(true)); 
 	 	$(".contenuArticle1").append(jTextareaDescription.clone(true)); 
 	 	$(".contenuArticle1").append(jTextareaMotCles.clone(true)); 
 	 	$(".contenuArticle1").append(jInputFile.clone(true)); 
 	 	$(".contenuArticle1").append(jSelectCateg.clone(true));
 	 	// création select avec les categorie
 	 	$.ajax({
              url: "libs/dataBdd.php",
      				data:{"action":"Categories"},
      				type : "GET",

                    success:function (oRep){ 
                    	for(var i = 0 ; i <oRep.length ; i++){
                    		$("#categorie").append(jOptionSelect.clone(true)
                                          .val(oRep[i].id)
                    											.html(oRep[i].nomCategorie));
                    	}                          
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });

 	 	// création select avec les matières
 	 	$(".contenuArticle1").append(jSelectMatiere.clone(true));
 	 	$.ajax({
                     url: "libs/dataBdd.php",
      				data:{"action":"Matieres"},
      				type : "GET",

                    success:function (oRep){ 
                    	for(var i = 0 ; i <oRep.length ; i++){
                    		console.log(oRep); 
                    		$("#matiere").append(jOptionSelect.clone(true)
                    											.html(oRep[i].nomM)
                    											.val(oRep[i].id)); 
                    	}                          
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });

 	 	// création select avec les finitions
 	 	$(".contenuArticle1").append(jSelectFinition.clone(true));
 	 	$.ajax({
                     url: "libs/dataBdd.php",
      				data:{"action":"Finitions"},
      				type : "GET",

                    success:function (oRep){ 
                    	for(var i = 0 ; i <oRep.length ; i++){
                    		$("#finition").append(jOptionSelect.clone(true)
                    											.html(oRep[i].nomF)
                    											.val(oRep[i].id)); 
                    	}                          
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });

 	 	$(".contenuArticle1").append(jImg.clone(true));
    requestSuprImg("http://localhost/PINF/PINFV2/images/preview");// TODO : changer , supprime l'ancinne image de preview  
 	 	$(".contenuArticle2").append(jTableauDimensions.clone(true));
 	 	$(".contenuArticle2").append(jBtnModePrix.clone(true));
 	 	$(".contenuArticle2").append(jDimPrix.clone(true));
 	 	$(".contenuArticle2").append(jTableauPrix.clone(true));
 	 	$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true));  
 	  $(".contenuArticle2").append(jTableauOption.clone(true));
 	 	$(".contenuArticle2 #tabOption").append(jLigneTableauOption.clone(true));  
 	 	$(".contenuArticle2").append(jInputCreerFer.clone(true));   
 	 });

//************************** GESTIONNAIRE D'EVENEMENT **************************//
	
	$(document).on("click", "table img", function() {

 	 		var classeImg = $(this).prop("class"); 
 	 		var classImgSplit  = classeImg.split(" "); 

 	 		var classIcon = classImgSplit[0];
 	 		var action = classImgSplit[1];
 	 		console.log(action);  

 	 		switch(action){
 	 			
 	 			case 'ajoutLignetabOptions':
 	 				
 	 				$(".contenuArticle2 #tabOption tbody").append(jLigneTableauOption.clone(true)); 
 	 				$("input[value='CREER']").before(jBrTabOption.clone(true)); 

 	 				var first = $(".contenuArticle2 #tabOption tr").last().offset().top; //first element distance from top
        			var second = $("input[value='CREER']").offset().top;  //second element distance from top
        			var distance = parseInt(first) - parseInt(second) ;	//distance between elements
        			//console.log(distance); 
        			if(distance > -70)
        				$(".jBrTabOption").last().append("</br>");
 	 			break; 

 	 			case 'ajoutLignetabPrix':

 	 				if(DimIncluPrix == 0)// var globale indiquant si une dimension est inclue dans le prix 
 	 					$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true)); 
 	 				else
 	 					$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true).prepend('<td class="modeDimPrix"><input type="number"></td><td class="modeDimPrix"><input type="number"></td>')); 

 	 				$("label[for='tabOption']").before(jBrTabPrix.clone(true));

 	 				var first = $(".contenuArticle2 #tabPrix tr").last().offset().top; //distance du première élement à partir du haut 
        			var second = $("label[for='tabOption']").offset().top;  //distance du second élement à partir du haut 
        			var distance = parseInt(first) - parseInt(second) ;	//distance entre les deux éléments

        			if(distance > -70)
        				$(".jBrTabPrix").last().append("</br>");   
 	 			break; 

 	 			case 'suprLigneTabOptions':
 	 				$(this).parent().parent().remove();
 	 				$(".jBrTabOption").last().remove(); 
 	 			break; 

 	 			case 'suprLigneTabPrix':
 	 				$(this).parent().parent().remove();
 	 				$(".jBrTabPrix").last().remove(); 
 	 			break;
 	 		} 
	});

 	 $(document).ready(function(){ // Lorsque le document est chargé 

 	 	$(".addDim").change(function(){ 
 	 		var nomDim = $(this).val(); 
 	 		$('input:radio[value='+nomDim+']').prop("checked", false); // décoche le cb pour éviter les erreurs
 	 		$("#"+nomDim).toggle(); 
 	 	});
 	 });

 	 //************************** FONCTION DE REQUETES **************************//
    // COULEUR : https://developer.mozilla.org/fr/docs/Web/HTML/Element/Input/color
    // todo couleur create ferrues
 	 function requestCreerFerrure(){
 	 	//var titre = $("#titre").val(); 
 	 	var titre,description,tags,categorie ; 
 	 	if( titre = infosManquantesImportantes("titre"))
    if( description = infosManquantesImportantes("description"))
    if( tags = infosManquantesImportantes("motsCles"))
 	 	{ 
 	 		var categorie = $("#categorie").val();
      var finition = $("#finition").val();
      var matiere =  $("#matiere").val();
 	 		console.log(titre + description + tags + categorie + matiere + finition);
      /* Requete AJax : */  
      requestAjaxCreerFerrure(titre,description,categorie,tags,matiere,finition);  
 	 	}
 	} 

 	function requestUploadFile(mode){

    var fd = new FormData();

                if(mode=="img"||mode=="preview")
                  var files = $('#formImg #file')[0].files;
                else if(mode=="pdf")
                  var files = $('#formPdf #file')[0].files;

                console.log(files); 
                // Check file selected or not
                if(files.length > 0 ){
                    fd.append('file',files[0]);

                    $.ajax({
                        url: "upload.php?mode="+mode,
                        type:"POST",
                        data:fd,
                        contentType: false,
                        processData: false,
                        success:function(response){
                            if(mode =="preview"){
                                console.log(response);
                                preview = 1; 
                                $("#imgImport").attr("src",response);
                                //  $("#imgImport").show();
                            }
                              //alert('File not uploaded');
                            return;
                        }
                    });
                } else{ 
                    alert("Please select a file.");
                    displayErreur("Selectionner une image"); 
                    return;
                }
 	}

 	function requestCreateDimsFerrures(idFerrure){
    console.log(idFerrure); 
 		var min,max,incluePrix ;  
 		var tab = $("#tabDims td input"); 
 		console.log(tab);

 		for(var i =0 ; i < tab.length ; i+=3){
 			var ligne = i/3 + 1 ; 

 			if(tab[i].checked == true){
 				var nom = tab[i].value;
 				if(min = verifInputTableau(tab[i+1].value,"Tableau des dimensions","dimention minimale",ligne,"number",1))
 					if(max = verifInputTableau(tab[i+2].value,"Tableau des dimensions","dimention maximale",ligne,"number",1)){
 						if($('input:radio[value='+nom+']').prop("checked") == true)
              incluePrix = 1 ;
            else 
              incluePrix = 0 ;  
            //requestAjaxAddDimFerrure(min,max,nom,incluxPrix,refFerrure)
            console.log(min+max+nom+incluePrix+idFerrure); 
            requestAjaxAddDimFerrure(min,max,nom,incluePrix,idFerrure);
 					}
        if(stopAjout == 1){
          requestDeleteFerrure(idFerrure); 
          return ;
        } 
 			}
 		} requestCreteOptionsFerrures(idFerrure); 
 	} 
 	
 	function requestCreatePrixFerrures(idFerrure){

 		var qteMin,qteMax,prix,dimPrix,dimMax,dimPrix; 
 		var tab = $("#tabPrix td input"); 
 		console.log(tab);
    console.log("DIMPRIX=>"+DimIncluPrix); 
 		if(DimIncluPrix == 0) var suiv = 3 ; 
 		else suiv = 5 ; 

 		for(var i =0 ; i < tab.length ; i+= suiv){
 			
 			var ligne = parseInt(i/suiv + 1) ; 

 			if(DimIncluPrix ==0){ 
 				if(qteMin = verifInputTableau(tab[i].value,"Tableau des prix","quantite minimale",ligne,"number",1))
 					if(qteMax = verifInputTableau(tab[i+1].value,"Tableau des prix","quantite maximale",ligne,"number",1))
 						if(prix = verifInputTableau(tab[i+2].value,"Tableau des prix","prix",ligne,"number",0)){
 							console.log("Ajout ligne "+ ligne + " dans la bdd"+qteMin+qteMax+prix+idFerrure); 
              requestAjaxAddPrixFerrure(qteMin,qteMax,prix,idFerrure,0,0)
 						}
 			}

 			else{
 				dimPrix = $("input[name='dimensionPrix']:checked").val(); 
 				//console.log(dimPrix); 

 				if(dimMin = verifInputTableau(tab[i].value,"Tableau des prix","dimension minimale",ligne,"number",1))
 					if(dimMax = verifInputTableau(tab[i+1].value,"Tableau des prix","dimension maximale",ligne,"number",1))
 						if(qteMin = verifInputTableau(tab[i+2].value,"Tableau des prix","quantite minimale",ligne,"number",0))
 							if(qteMax = verifInputTableau(tab[i+3].value,"Tableau des prix","quantite maximale",ligne,"number",1))
 								if(prix = verifInputTableau(tab[i+4].value,"Tableau des prix","prix",ligne,"number",1))
 									if(dimPrix != undefined){
 										console.log("Ajout ligne "+ ligne + " dans la bdd"+prix); 
                    requestAjaxAddPrixFerrure(qteMin,qteMax,prix,idFerrure,dimMin,dimMax)
 									}
 									
 									else{
                    msg = "veuillez sélectionner une dimensions dans le prix" ; 
 										console.log(msg); 
                    displayErreur(msg); 
                    requestDeleteFerrure(idFerrure); 
                    return ; 
 									}
 			}
      if(stopAjout == 1){
                    console.log("out!!!");
                    requestDeleteFerrure(idFerrure); 
                    return ; 
                    }
 		}console.log("TEST:"+displayErreur);
    requestAddFilesFerrures(idFerrure);
 	}

 	function requestCreteOptionsFerrures(idFerrure){
 		var nom,prix ;  
 		var tab = $("#tabOption td input"); 
 		console.log(tab);
 	
 		for(var i =0 ; i < tab.length ; i+=2){
 			var ligne = i/2 + 1; 

 			if(nom = verifInputTableau(tab[i].value,"Tableau des options","nom",ligne,"text"))
 		    if(prix = verifInputTableau(tab[i+1].value,"Tableau des options","prix",ligne,"number",0)){
 				  console.log(nom+prix+idFerrure); 
          requestAjaxAddOptionFerrure(nom,prix,idFerrure); 		
 			}
      if(stopAjout ==1){
        requestDeleteFerrure(idFerrure); 
        return ; 
      }
 		}requestCreatePrixFerrures(idFerrure);
 	}

 	function requestAddFilesFerrures(idFerrure){
    requestUploadFile("img"); 
    requestUploadFile("pdf");
    var lienImg = $("#formImg #file").val(); 
    var lienPdf = $("#formPdf #file").val(); 
    var tabLien = lienImg.split("\\"); 
    var img = tabLien[tabLien.length - 1] ; 
    tabLien = lienPdf.split("\\");
    var pdf = tabLien[tabLien.length - 1] ; 
    //console.log("TEST"+img+pdf); 
    requestAjaxCreerFerrure2(img,pdf,pdf,idFerrure);
 	}

 	//************************** FONCTION ERREURS **************************//

 	function infosManquantesImportantes(idElement){
 		contenu = $("#"+idElement).val(); 
 		if(contenu.trim() == ""){ // La méthode trim() permet de retirer les blancs en début et fin de chaîne. 
 		//Les blancs considérés sont les caractères d'espacement (espace, tabulation, espace insécable, etc.) 
 		//ainsi que les caractères de fin de ligne (LF, CR, etc.).
        var msg = idElement + " vide !!" ; 
    		console.log(msg);
        displayErreur(msg); 
    		return null ; 
		}
		else
			return contenu ; 
 	}

 	function verifInputTableau(valueInput,nomTab,nomCol,numLigne,typeInput,valMin){
 	// valueInput : valeur de la case input
 	// nom du tableau dans lequel l'input est contenu 
 	// nomcol : nom du champ input
 	// numLigne : nuemro de ligne ou l'input se situe
 	// typeInput : type de l'input à vérifier
 	// valMin : valeur à tester pour l'input
 		if(valueInput ==""){
 			var msg = nomCol + " manquante dans le " + nomTab + " à la ligne " + numLigne ; 
 			console.log(msg);
      displayErreur(msg);  
 			return null ;  
 		}

 		if(typeInput="number" && valueInput < valMin){
 			var msg = nomCol + " érroné dans le " + nomTab + " à la ligne " + numLigne ; 
 			console.log(msg); 
      displayErreur(msg);
 			return null;  
 		} 

    if(type="text"){
      if(valueInput.trim() == ""){ 
        var msg = nomCol + " invalide dans le " + nomTab + " à la ligne " + numLigne ;  
        console.log(msg);
        displayErreur(msg); 
        return null ; 
      }
    }
 			return valueInput ; 
 	}

  function displayErreur(msg){
    $("#erreur").html(msg).show();
    stopAjout = 1 ; 
  }

  function resetErreur(){
     $("#erreur").hide();
     stopAjout = 0; 
  }

/////////////////////////////////////// AJAX ////////////////////////////////////

  function requestAjaxCreerFerrure(titre,description,categorie,tags,matiere,finition){
 
     $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"Ferrure1","titre":titre , "description":description , "tags":tags , 
            "categorie": categorie , "matiere" : matiere ,"finition" : finition },
            type : "POST",
            success : function(oRep){
              console.log("Ferrure créer , id => "); console.log(oRep); 
              requestCreateDimsFerrures(oRep); 
            },
            dataType: "json"
          });
  }


  function requestAjaxCreerFerrure2(img,pdf,numPdf,refFerrure){

      $.ajax({
            url: "libs/dataBdd.php?action=Ferrure2&img=" + img + "&pdf=" + pdf + "&numPlan=" + numPdf + "&refFerrure="+refFerrure,
            type : "PUT",
            success : function(oRep){
              console.log("Image et plan importés!"); console.log(oRep); 
              return; 
            },
            error : function(oRep){
              console.log("error"); 
            },
            dataType: "json"
          });
  } 

  function requestAjaxAddDimFerrure(min,max,nom,incluePrix,refFerrure){

    $.ajax({
            url: "libs/dataBdd.php?action=Dimension&nom="+ nom + "&min=" + min + 
            "&max=" + max + "&incluePrix=" + incluePrix + "&refFerrure=" + refFerrure,
            type : "POST",
            success : function(oRep){
              console.log("dim ajouté "); console.log(oRep); 
              return ; 
            },
            error : function(oRep){
              console.log("error");
            },
            dataType: "json"
          });
   
  }

  function requestAjaxAddOptionFerrure(nom,prix,idFerrure){
     $.ajax({
            url: "libs/dataBdd.php?action=Option&nom="+ nom + "&prix=" + prix + "&refFerrure=" + idFerrure,  
            type : "POST",
            success : function(oRep){
              console.log("option ajouté "); console.log(oRep); 
              return ; 
            },
            error : function(oRep){
              console.log("error");
            },
            dataType: "json"
          });
  }

  function requestAjaxAddPrixFerrure(qteMin,qteMax,prix,idFerrure,dimMin,dimMax){
     $.ajax({
            url: "libs/dataBdd.php?action=Prix&qteMin="+ qteMin + "&qteMax=" + qteMax +"&prix=" 
            + prix + "&refFerrure=" + idFerrure + "&dimMin=" + dimMin + "&dimMax=" + dimMax,  
            type : "POST",
            success : function(oRep){
              console.log("prix ajouté "); console.log(oRep); 
              return ; 
            },
            error : function(oRep){
              console.log("error");
            },
            dataType: "json"
          });
  }

  function requestDeleteFerrure(idFerrure)
  {
    console.log("supression F" + idFerrure); 
    $.ajax({
            url: "libs/dataBdd.php?action=Ferrure&id="+idFerrure,
            type : "DELETE",
            success : function(oRep){
              console.log("Ferrure supprimée!!");
              console.log(oRep);  
            },
            error : function(oRep){
              console.log("ERREUR"); 
            },
            dataType: "json"
          });
  }

  function requestSuprImg(lien){
    console.log("supression"); 
    console.log(lien); 

    $.ajax({
            url:"delete.php",
            data:{"lien":lien},
            type : "POST",
            success : function(oRep){
              console.log(oRep); 
            },
            dataType: "json"
          });
  }

 </script>

 <body>
 	<div class="container">

     <br><br><div id="erreur"></div>

 		<div class="row" style="text-align: center;">
 		<h2 id="titlePage">Ajouter un Produit</h2><br><br><br>
 		</div>

 		<div class="row">
	 		<div class="column left-side">
	 			<div class="contenuArticle1">	
				</div>
			</div>		

			<div class="column right-side">
				<div class="contenuArticle2">	
				</div>	
			</div>		
		</div>
 		</div>
 </body>