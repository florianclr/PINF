<?php
	$idArticle = valider("idFerrure");
	$categorie = valider("categorie");
  $admin = valider("isAdmin","SESSION"); 
  $msg= valider("msg"); 

	if (!valider("connecte","SESSION") || $admin == 0 ) {
  		header("Location:index.php?view=connexion");
  		die("");
	}
?>
   <style type="text/css">
     .error{
      background-color : indianred; 
     }
   </style>
	 <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
   <link href="css/createArticle.css" rel="stylesheet">  
   <link href="css/imagesZomm.css" rel="stylesheet">  
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>

    <script type="text/javascript">

//************************** VAR GLOBALE **************************//
	var DimIncluPrix = 0 ;  // boolean pour savoir si le prix est inclue 
  var stopAjout = 0 ;  // boolean afin de savoir si il faut stopper l'ajout d'une ferrure ds la bdd
  var preview = 0;  // boolean indiquant si une preview de l'image de la ferrure est présente 
  var admin ="<?php echo $admin; ?>";
  var categorie = "<?php echo $categorie; ?>";
  var idArticle = "<?php echo $idArticle; ?>";
  var chgt = 0 ; // boolean pour savoir s'il y a eu des changements lors de la modification et donc en consequence exécuter une requetes de modif ou non
  var msgLoad = "<?php echo $msg; ?>";
  var modeEdition=0
  if(idArticle != null && idArticle != "")
    modeEdition=1; 

//************************** MODELE JQUERY DONNÉES FERRURES**************************//

 	var jInputTitre = $('<label for="titre">Titre :</label><br><input type="text" id="titre"> <br><br>'); 

 	var jInputOption = $('<label for="option">Option :</label> <input type="text" id="option"><img class="icon" src="./ressources/plus2.png""><br>'); 

 	var jInputNumber = $('<input type="number" min="1">');

 	var jInputDimensions = $('<input type="checkbox">'); 

 	var jTextareaDescription = $('<label for="description">Description :</label>' +'<br>'
 								+'<textarea id="description"></textarea><br>');

 	var jTextareaMotCles = $('<label for="motsCles">Mots-clés :</label>' +'<br>'
 							+'<textarea id="motsCles"></textarea>' +'<br>'
 							+'<span>&#9888; Veuillez séparer chaque mot-clé par un point-virgule</span><br>'
 							+'<span>Le titre, la catégorie, la matière et la finition figureront automatiquement parmi les tags, en plus des mots-clés saisis</span>'+'<br><br>');

 	var jSelectCateg = $('<br><label for="categorie">Catégorie :</label> <select id="categorie"> </select><br>'); 

 	var jSelectMatiere = $('<br><label for="matiere">Matière :</label> <select id="matiere"> </select><br>'); 

 	var jSelectFinition = $('<br><label for="finition">Finition :</label> <select id="finition"> </select><br>'); 

  var jInputFile = $('<form method="post" action="" enctype="multipart/form-data" id="formPdf">')
                  .append($('<label for="file">Importer un plan PDF :</label>'))
                  .append($('<input id="file" name="file" type="file"><br>').change(function(){
                    $("#erreur").empty().hide();
                    requestUploadFile("testPdf"); 
                  }) );

  var jImg =$('<form method="post" action="" enctype="multipart/form-data" id="formImg">')
            .append($('<label for="imgImport">Importer une image :</label><br>' 
                    +'<img id="imgImport" src="./ressources/image.png"><br>'))
            .append($('<span>Cliquez sur l\'image pour zoomer</span><br><br>'))
            .append($('<input type="file" id="file" name="file"/><br><br>').change(function(){
                    
                    $("#erreur").empty().hide();
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

 	var jTableauPrix = $('<br><label id="labelTabPrix" for="tabPrix">Tableau des prix :</label>'
 						+'<table id="tabPrix" class="tabAjout w3-table-all">' 
					    +'<tr class="nomCol">'
					    +'<th>Quantité minimale</th>'
					    +'<th>Quantité maximale</th>'
					    +'<th>Prix unitaire</th>'
              +'<th style="text-align: center;"><img class="icon ajoutLignetabPrix" src="./ressources/plus2.png""></th>'
					    +'</tr>'
					    +'</table>'
					    +'<br>'); 

 	var jLigneTableauPrix = $('<tr class="ligne">'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
						     +'<td><input type="number"></td>'
                 +'<td style="text-align: center;"><img class="icon suprLigneTabPrix" src="./ressources/moins.png""></td>'
						     +'</tr>');
 	// source image : https://pixabay.com/fr/vectors/icône-symbole-plus-rouge-croix-1970474/

 	var jTableauOption = $('<br><label for="tabOption">Tableau des options :</label>'
 						+'<table id="tabOption" class="tabAjout w3-table-all">' 
					    +'<tr>'
					    +'<th>Nom</th>'
					    +'<th>Prix unitaire</th>'
              +'<th style="text-align: center;"><img class="icon ajoutLignetabOptions" src="./ressources/plus2.png""></th>'
					    +'</tr>'
					    +'</table>'
					    +'<br><br>');

 	// tabelau des options // 
 	var jLigneTableauOption = $('<tr class="ligne">'
						     +'<td><input type="text"></td>'
						     +'<td><input type="number"></td>'
                 +'<td style="text-align: center;"><img class="icon suprLigneTabOptions" src="./ressources/moins.png""></td>'
						     +'</tr>');

 	// tabelau des dimensions // 
 	var jTableauDimensions = $('<label for="tabDims"> Tableau des dimensions :</label>'
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

 	
 	var jBtnModePrix = $('<br><br><br><input id="btnModePrix" type="button" value="Inclure une dimension dans le prix">').click(function(){
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
 					.append("<h6>Le prix dépend de :</h6>")
 					.append('<div id="a"><label for"dimensionPrix"> a </label> <input type="radio" name="dimensionPrix" value="a"></div>')
 					.append('<div id="b"><label for"dimensionPrix"> b </label> <input type="radio" name="dimensionPrix" value="b"></div>')
 					.append('<div id="c"><label for"dimensionPrix"> c </label> <input type="radio" name="dimensionPrix" value="c"></div>'); 

 	var jBrTabOption = $('<div class="jBrTabOption"></br></div>');
 	var jBrTabPrix = $('<div class="jBrTabPrix"></br></div>');

//************************** VALIDATION **************************//

 	var jInputCreerFer = $('</br><input class="create" type="submit" value="CRÉER">').click(function(){
 		// 1 requête par table 
    resetErreur(); 
    requestCreerFerrure(); // qui appelle : 
 		 //requestCreateDimsFerrures(); OK
 		 //requestCreatePrixFerrures(); OK
 		 //requestCreteOptionsFerrures(); OK
 		 //requestAddImgFerrure(); OK
 	}); 

  var jInputModifFer = $('</br><input class="create" type="submit" value="ENREGISTRER LES MODIFICATIONS">').click(function(){
    resetErreur(); 
    requestCreerFerrure(1); 
    if(stopAjout ==0)
    $("#uploadOK").html("Modification(s) enregistrée(s)").show();
    else
      $("#uploadOK").html("Toutes les  modifications ont été enregistrées exeptées celles en rouges").show();
  }); 

//************************** GÉNÉRATIONS DES MODÈLES **************************//

 	 $(document).ready(function(){ // Lorsque le document est chargé 

 	 	$(".contenuArticle1").append(jInputTitre.clone(true)); 
 	 	$(".contenuArticle1").append(jTextareaDescription.clone(true)); 
 	 	$(".contenuArticle1").append(jTextareaMotCles.clone(true)); 
 	 	$(".contenuArticle1").append(jInputFile.clone(true)); 
 	 	$(".contenuArticle1").append(jSelectCateg.clone(true));
 	 	// création select avec les categories
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
                         if( categorie != null){ 
                         $('#categorie option:contains('+ categorie +')').prop('selected', true);
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
 	 	//$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true));  
 	  $(".contenuArticle2").append(jTableauOption.clone(true));
 	 	//$(".contenuArticle2 #tabOption").append(jLigneTableauOption.clone(true));  
 	 
    if (idArticle != null && idArticle !=""){
      $(".contenuArticle2").append(jInputModifFer.clone(true));
      remplirInfosFerrure(idArticle); 
    }
    else
      $(".contenuArticle2").append(jInputCreerFer.clone(true));
 	 });

//************************* GESTIONNAIRE D'EVENEMENT **************************//
	
	$(document).on("click", "table img", function() {

 	 		var classeImg = $(this).prop("class"); 
 	 		var classImgSplit  = classeImg.split(" "); 

 	 		var classIcon = classImgSplit[0];
 	 		var action = classImgSplit[1];
 	 		console.log(action);  

 	 		switch(action){
 	 			
 	 			case 'ajoutLignetabOptions':
 	 				
 	 				$(".contenuArticle2 #tabOption tbody").append(jLigneTableauOption.clone(true)); 
 	 				
 	 				var first = $(".contenuArticle2 #tabOption tr").last().offset().top; //first element distance from top
        			var second = $("input[value='CRÉER']").offset().top;  //second element distance from top
        			var distance = parseInt(first) - parseInt(second) ;	//distance between elements
        			//console.log(distance); 
        			if(distance > -70)
                $("input[value='CRÉER']").before(jBrTabOption.clone(true)); 
        				//$(".jBrTabOption").last().append("</br>");
 	 			break; 

 	 			case 'ajoutLignetabPrix':

 	 				if(DimIncluPrix == 0)// var globale indiquant si une dimension est inclue dans le prix 
 	 					$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true)); 
 	 				else
 	 					$(".contenuArticle2 #tabPrix").append(jLigneTableauPrix.clone(true).prepend('<td class="modeDimPrix"><input type="number"></td><td class="modeDimPrix"><input type="number"></td>')); 

 	 				var first = $(".contenuArticle2 #tabPrix tr").last().offset().top; //distance du première élement à partir du haut 
        			var second = $("label[for='tabOption']").offset().top;  //distance du second élement à partir du haut 
        			var distance = parseInt(first) - parseInt(second) ;	//distance entre les deux éléments

              console.log(distance); 
        			if(distance > -70)
                $("label[for='tabOption']").before(jBrTabPrix.clone(true));
        				//$(".jBrTabPrix").last().append("</br>");   
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

    resetErreur(); 

 	 	  $(".addDim").change(function(){ 
 	 		var nomDim = $(this).val(); 
 	 		$('input:radio[value='+nomDim+']').prop("checked", false); // décoche le cb pour éviter les erreurs
 	 		$("#"+nomDim).toggle(); 
 	 	});

// ZOOM image 
// SOURCE : https://www.w3schools.com/howto/howto_css_modal_images.asp
    $("#imgImport").click(function(){
      var modal = document.getElementById("myModal");
      var img = document.getElementById("myImg");
      var modalImg = document.getElementById("img01");
      var captionText = document.getElementById("caption");
      modal.style.display = "block";
      modalImg.src = this.src;
      captionText.innerHTML = this.alt ;
    });

    $(".close").click(function(){
       var modal = document.getElementById("myModal");
      modal.style.display = "none";
    });
 	 });

 	 //************************* FONCTION DE REQUETES **************************//

   function remplirInfosFerrure(idArticle){
    console.log(idArticle); 
    requestAjaxRemplirInfos(idArticle); 
   }

 	 function requestCreerFerrure(modif=0){
 	 	//var titre = $("#titre").val(); 
 	 	var titre,description,tags,categorie ; 
 	 	if( titre = infosManquantesImportantes("titre"))
    if( description = infosManquantesImportantes("description"))
 	 	{ 
			var tags = $("#motsCles").val();
 	 		var categorie = $("#categorie").val();
      var finition = $("#finition").val();
      var matiere =  $("#matiere").val();
 	 		console.log(titre + description + tags + categorie + matiere + finition);
      /* Requete AJax : */  
      if(modif==0){
        tags = tags + ";" + titre + ";" + $("#categorie").find('option:selected').html() + ";" + $("#matiere").find('option:selected').html() + ";" 
        + $("#finition").find('option:selected').html() ; 
	if( $("#formPdf file") != ""){
          var lienPdf = $("#formPdf #file").val(); 
          var tabLien = lienPdf.split("\\");
          var planPdf = tabLien[tabLien.length - 1] ;
          planPdf = planPdf.split(".");
          tags = tags + ";" + planPdf[planPdf.length-2]; 
        }
        requestAjaxCreerFerrure(titre,description,categorie,tags,matiere,finition); 
      }

      else if(modif ==1){
        checkModif(titre,$("#titre").data("initial"),$("#titre"));
        checkModif(description,$("#description").data("initial"),$("#description"));
        checkModif(tags,$("#motsCles").data("initial"),$("#motsCles")) ;
        checkModif(categorie,$("#categorie").data("initial"),$("#categorie"));
        checkModif(matiere,$("#matiere").data("initial"),$("#matiere"));
        checkModif(finition,$("#finition").data("initial"),$("#matiere"));
        if(chgt==1){
          console.log("changement !!! ==> "+idArticle); 
          requestAjaxModifierFerrure(titre,description,categorie,tags,matiere,finition,idArticle);
      }
      else{
        console.log("pas de chgt !!"); 
      }
      if(modif ==1){ 
        chgt=0; 
        requestCreateDimsFerrures(idArticle,1)
        }
      }
 	 	}
 	} 

 	function requestUploadFile(mode){

    var fd = new FormData();

                if(mode=="img"||mode=="preview")
                  var files = $('#formImg #file')[0].files;
                else if(mode=="pdf" || mode=="testPdf")
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
                            if(mode =="preview" && response ==1){
                                console.log(response);
                                preview = 1; 
                                $("#imgImport").attr("src","./images/preview");
                                var nomImg= $('#formImg #file')[0].files[0].name;
                                $("#imgImport").attr("alt",nomImg);
                                //  $("#imgImport").show();
                            }
                            if (response != 1){
                              //console.log("errreur import "+ mode + response); 
                              displayErreur(response);
                              if(mode=="pdf" || mode=="testPdf") 
                                $("#formPdf input").val("");
                              else{
                                $("#imgImport").attr("src","./ressources/image.png"); // si une image était déjà chargé 
                                $("#formImg input").val("");
                              }
                            }
                            return;
                        }
                    });
                } // fin if (files.length > 0)

		else{
                    // if(mode=="pdf"){  
                    // alert("Plan manquant pour le pdf");
                    // displayErreur("Pdf manquant"); 
                    // }
                    if (mode=="img"){
                      alert("Image manquante");
                      displayErreur("Selectionner une image"); 
                    }
                    return;
                }
 	}

 	function requestCreateDimsFerrures(idFerrure,modif=0){ // param modif => booelean afin de savoir si on est en mode création ou édition 
    console.log(idFerrure); 
 		var min,max,incluePrix ;  
 		var tab = $("#tabDims td input"); 
 		console.log(tab);

 		for(var i =0 ; i < tab.length ; i+=3){
 			var ligne = i/3 + 1 ; 

 			if(tab[i].checked == true){
 				var nom = tab[i].value;
 				if(min = verifInputTableau(tab[i+1].value,"Tableau des dimensions","dimention minimale",ligne,"number",1,tab[i+1]))
 					if(max = verifInputTableau(tab[i+2].value,"Tableau des dimensions","dimention maximale",ligne,"number",1,tab[i+2])){
 						if($('input:radio[value='+nom+']').prop("checked") == true)
              incluePrix = 1 ;
            else 
              incluePrix = 0 ; 

            if (modif ==1){
              var incluePrixInit = $('input:radio[value='+nom+']').data("checked");
              if (incluePrix != incluePrixInit)
                chgt=1; 
            } 

            console.log(min+max+nom+incluePrix+idFerrure); 
            console.log($(tab[i]).data("checked"));

            if(modif ==0 || $(tab[i]).data("checked") == undefined){ 
              console.log("AJOUT "+nom); 
              requestAjaxAddDimFerrure(min,max,nom,incluePrix,idFerrure);
              if(modif == 1){
                $(tab[i]).data("checked","true");
                $(tab[i+1]).data("initial",min);
                $(tab[i+2]).data("initial",max);
              }
            }
            
            else if(modif==1 && $(tab[i]).data("checked") == "true"){
              checkModif(min,$(tab[i+1]).data("initial"),$(tab[i+1]));
              checkModif(max,$(tab[i+2]).data("initial"),$(tab[i+2]));
              if(chgt==1){
                chgt=0;
                console.log("MODIF dimsss!!"+nom);
                var idLigne=$(tab[i]).parent().parent().data("id");
                console.log("ligne=>"+idLigne);
                requestAjaxModifierDimFerrure(min,max,nom,incluePrix,idLigne); 
              }
              else
                console.log("pas modif Dims"+nom);
            }
 					}
	console.log("AJOUT=>"+stopAjout);
        if(stopAjout == 1){
          if(modif==0){ 
            requestDeleteFerrure(idFerrure); 
            return ; // en edition on quitte juste la fonction pour ne pas mettre à jour la ferrues, on ne supprime sourtout pas la ferrure
          }
        } 
 			}
       if(modif==1 && tab[i].checked == false && $(tab[i]).data("checked") == "true"){
        console.log("SUPRIMER DIM !!!"+tab[i].value); 
        var idLigne=$(tab[i]).parent().parent().data("id");
        requestAjaxSuprDim(idLigne,idArticle); 
      }
 		}
    if(modif==1){ 
      chgt=0; 
      requestCreteOptionsFerrures(idFerrure,1); 
    }
    else
      requestCreteOptionsFerrures(idFerrure); 
 	} 
 	
 	function requestCreatePrixFerrures(idFerrure,modif=0){

 		var qteMin,qteMax,prix,dimPrix,dimMax,dimPrix; 
 		var tab = $("#tabPrix td input"); 
 		console.log(tab);
    console.log("DIMPRIX=>"+DimIncluPrix); 
 		if(DimIncluPrix == 0) var suiv = 3 ; 
 		else suiv = 5 ; 

 		for(var i =0 ; i < tab.length ; i+= suiv){
 			
 			var ligne = parseInt(i/suiv + 1) ; 

 			if(DimIncluPrix ==0){ 
 				if(qteMin = verifInputTableau(tab[i].value,"Tableau des prix","quantite minimale",ligne,"number",1,tab[i]))
 					if(qteMax = verifInputTableau(tab[i+1].value,"Tableau des prix","quantite maximale",ligne,"number",1,tab[i+1]))
 						if(prix = verifInputTableau(tab[i+2].value,"Tableau des prix","prix",ligne,"number",0,tab[i+2])){
 							//console.log("Ajout ligne "+ ligne + " dans la bdd"+qteMin+qteMax+prix+idFerrure); 

              if(modif==0 || $(tab[i]).data("initial")==undefined){
                console.log("Ajout ligne"+ligne);
                requestAjaxAddPrixFerrure(qteMin,qteMax,prix,idFerrure,0,0)
                if(modif==1){
                  $(tab[i]).data("initial",qteMin);
                  $(tab[i+1]).data("initial",qteMax);
                  $(tab[i+2]).data("initial",prix);
                }
              }

              else if(modif==1){
                checkModif(qteMin,$(tab[i]).data("initial"),$(tab[i]));
                checkModif(qteMax,$(tab[i+1]).data("initial"),$(tab[i+1]));
                checkModif(prix,$(tab[i+2]).data("initial"),$(tab[i+2]));
                if (chgt == 1){ 
                  chgt=0;
                  console.log("Modif ligne"+ligne);
                  var idLigne = $(tab[i]).parent().parent().data("id"); 
                  requestAjaxModifierPrixFerrure(idLigne,qteMin,qteMax,prix); 
                }
                 else
                  console.log("Pas de modif pour la ligne "+ ligne);
              }
 						}
 			}

 			else{
 				dimPrix = $("input[name='dimensionPrix']:checked").val(); 
 				//console.log(dimPrix); 

 				if(dimMin = verifInputTableau(tab[i].value,"Tableau des prix","dimension minimale",ligne,"number",0,tab[i]))
 					if(dimMax = verifInputTableau(tab[i+1].value,"Tableau des prix","dimension maximale",ligne,"number",0,tab[i+1]))
 						if(qteMin = verifInputTableau(tab[i+2].value,"Tableau des prix","quantite minimale",ligne,"number",0,tab[i+2]))
 							if(qteMax = verifInputTableau(tab[i+3].value,"Tableau des prix","quantite maximale",ligne,"number",1,tab[i+3]))
 								if(prix = verifInputTableau(tab[i+4].value,"Tableau des prix","prix",ligne,"number",1,tab[i+4]))
 									if(dimPrix != undefined){
 										//console.log("Ajout ligne "+ ligne + " dans la bdd"+prix); 
                    if(modif==0 || $(tab[i]).data("initial")==undefined){
                    console.log("ajout ligne "+ligne);
                    requestAjaxAddPrixFerrure(qteMin,qteMax,prix,idFerrure,dimMin,dimMax)
                    }
                    else if(modif ==1){
                       checkModif(dimMin,$(tab[i]).data("initial"),$(tab[i]));
                       checkModif(dimMax,$(tab[i+1]).data("initial"),$(tab[i+1]));
                       checkModif(qteMin,$(tab[i+2]).data("initial"),$(tab[i+2]));
                       checkModif(qteMax,$(tab[i+3]).data("initial"),$(tab[i+3]));
                       checkModif(prix,$(tab[i+4]).data("initial"),$(tab[i+4]));
                       if (chgt == 1){ 
                        chgt=0;
                        console.log("Modif ligne"+ligne);
                        var idLigne = $(tab[i]).parent().parent().data("id"); 
                        requestAjaxModifierPrixFerrure(idLigne,qteMin,qteMax,prix,dimMin,dimMax);
                      }
                      else
                        console.log("Pas de modif pour la ligne "+ ligne);
                    }
 									}
 		
 									else{
                    msg = "Veuillez sélectionner une dimension dans le prix" ; 
 										console.log(msg); 
                    displayErreur(msg); 
                    $("#modeDimensionsPrix h6").addClass("error");
                    if(modif==0)
                      requestDeleteFerrure(idFerrure); 
                    return ; 
 									}
 			}
      if(stopAjout == 1){
                    console.log("supression!!"+modif); 
                    requestDeleteFerrure(idFerrure); 
                    return ; 
                    }
 		}
    if(modif==1){ 
      chgt=0; 
      requestAddFilesFerrures(idFerrure,1);
    }
    else
      requestAddFilesFerrures(idFerrure);
 	}

 	function requestCreteOptionsFerrures(idFerrure,modif=0){
 		var nom,prix ;  
 		var tab = $("#tabOption td input"); 
 		console.log(tab);
 	
 		for(var i =0 ; i < tab.length ; i+=2){
 			var ligne = i/2 + 1; 

 			if(nom = verifInputTableau(tab[i].value,"Tableau des options","nom",ligne,"text",0,tab[i]))
 		    if(prix = verifInputTableau(tab[i+1].value,"Tableau des options","prix",ligne,"number",0,tab[i+1])){
 				  console.log(nom+prix+idFerrure); 

          if(modif ==0 || $(tab[i]).data("initial")==undefined){
            console.log("ajout !!"+nom)
            requestAjaxAddOptionFerrure(nom,prix,idFerrure); 	
            if(modif ==1){
              $(tab[i]).data("initial",nom);
              $(tab[i+1]).data("initial",prix);
            }	
          }

          else if(modif ==1){
            checkModif(nom,$(tab[i]).data("initial"),$(tab[i]));
            checkModif(prix,$(tab[i+1]).data("initial"),$(tab[i+1]));
            if(chgt==1){
              chgt=0;
              console.log("modif pour option "+nom)
              var idLigne=$(tab[i]).parent().parent().data("id");
              requestAjaxModifierOptionFerrure(idLigne,nom,prix);
            }
            else 
              console.log("pas de modif pour option" + nom)
          }
 			}
      if(stopAjout ==1){
        if(modif ==0){ 
          requestDeleteFerrure(idFerrure); 
          return ; 
        }
      }
 		}
    if(modif==1){
      chgt=0;
      requestCreatePrixFerrures(idFerrure,1);
    }
    else
      requestCreatePrixFerrures(idFerrure);
 	}


 	function requestAddFilesFerrures(idFerrure,modif=0){

    if(modif==0){ 
      requestUploadFile("img"); 
      requestUploadFile("pdf");
    }
    var lienImg = $("#formImg #file").val(); 
    var lienPdf = $("#formPdf #file").val(); 
    var tabLien = lienImg.split("\\"); 
    var img = tabLien[tabLien.length - 1] ; 
    tabLien = lienPdf.split("\\");
    var pdf = tabLien[tabLien.length - 1] ;

    if (modif==1){
      console.log("act img=>"+img + " init img=>"+$("#formImg #file").data("file"));
      if(img != "" && img != $("#formImg #file").data("file")){
        console.log("changement image !!"); 
        chgt=1;
        var lien= "images/"+$("#formImg #file").data("file"); 
        requestSuprImg(lien);
        requestUploadFile("img");
        $("#formImg #file").data("file",img);
        requestAJaxModifierImg(idFerrure,img);
      }

      if(pdf != "" && pdf != $("#formPdf #file").data("file")){
        chgt=1;
        console.log("changement pdf !!"); 
        var lien= "plan/"+$("#formPdf #file").data("file"); 
        console.log("supression ==>" + lien); 
        requestSuprImg(lien);
        requestUploadFile("pdf");
        $("#formPdf #file").data("file",pdf);
        requestAJaxModifierPdf(idFerrure,pdf,pdf);
      }
    }

    if(modif==0 && (lienImg =="")){ // insinue que l'image n'est pas bon
        requestDeleteFerrure(idFerrure); 
        return ;
      } 
    else{
      if (modif ==0){
        if(lienPdf != "")
          requestAjaxCreerFerrure2(img,pdf,pdf,idFerrure);
        else
          requestAjaxCreerFerrure2(img,null,null,idFerrure);

          //requestAddPdfFerrures();  OK
      if(stopAjout ==0){ 
        window.alert("Ferrure ajoutée dans la base");
        location.reload();
      }
      }
      if (modif==1 && chgt==1){
        console.log("changement img ou pdf !!"); 
      } 
      else{
        console.log("pas de chgt pour img ou pdf")
      }
    }
 	}

 	//************************** FONCTION ERREURS ************************//

  function checkModif(valTetser,valInitial,ref){
    console.log("init=>"+valInitial + " test=>" + valTetser);
   if(valInitial == valTetser)
    return 0;
  else {
    $(ref).data("initial",valTetser);
    chgt=1; 
    return 1 
  }
}

 	function infosManquantesImportantes(idElement){
 		contenu = $("#"+idElement).val(); 
 		if(contenu.trim() == ""){ // La méthode trim() permet de retirer les blancs en début et fin de chaîne. 
 		//Les blancs considérés sont les caractères d'espacement (espace, tabulation, espace insécable, etc.) 
 		//ainsi que les caractères de fin de ligne (LF, CR, etc.).
        var msg = idElement + " vide !!" ; 
        $("#"+idElement).addClass("error");
    		console.log(msg);
        displayErreur(msg); 
    		return null ; 
		}
		else
			return contenu ; 
 	}

 	function verifInputTableau(valueInput,nomTab,nomCol,numLigne,typeInput,valMin,refError){
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
      $(refError).addClass("error"); 
 			return null ;  
 		}

 		if(typeInput="number" && valueInput < valMin){
 			var msg = nomCol + " érroné dans le " + nomTab + " à la ligne " + numLigne ; 
 			console.log(msg); 
      displayErreur(msg);
      $(refError).addClass("error"); 
 			return null;  
 		} 

    if(type="text"){
      if(valueInput.trim() == ""){ 
        var msg = nomCol + " invalide dans le " + nomTab + " à la ligne " + numLigne ;  
        console.log(msg);
        displayErreur(msg); 
        $(refError).addClass("error"); 
        return null ; 
      }
    }
 			return valueInput ; 
 	}

  function displayErreur(msg){
    if(modeEdition ==0){ 
	stopAjout = 1;
	console.log("STOP");
      $("#erreur").html(msg).show(); 
    }
    else{
      msgAct=$("#erreur").html(); 
      $("#erreur").html(msgAct+"<br>"+msg).show();
    }
  }

  function resetErreur(){
     $(".error").removeClass("error"); 
     $("#erreur").empty().hide();
     $("#uploadOK").empty().hide();
     stopAjout = 0;
     chgt=0; 
  }

  function reloadPage(msg){
    var url = window.location.href;    
    url += "&msg="+msg ; 
    window.location.href = url;
  }
/////////////////////////////////////// AJAX ////////////////////////////////////
  
  function requestAjaxRemplirInfos(idArticle){
 
     $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"Produit", "idProduit":idArticle },
            type : "GET",
            success : function(oRep){
              console.log(oRep); 
              $("#titre").val(oRep[0].titre).data("initial",oRep[0].titre);
              $("#description").val(oRep[0].description).data("initial",oRep[0].description);
              $("#motsCles").val(oRep[0].tags).data("initial",oRep[0].tags);
              $('#categorie  option[value="' + oRep[0].refcategories +'"]').prop('selected', true);
              $('#matiere  option[value="' + oRep[0].refMatiere +'"]').prop('selected', true);
              $('#finition option[value="' + oRep[0].refFinition +'"]').prop('selected', true);

              $("#categorie").data("initial",oRep[0].refcategories);
              $("#matiere").data("initial",oRep[0].refMatiere);
              $("#finition").data("initial",oRep[0].refFinition);

              var img = "./images/"+oRep[0].image ;
              var pdf = "./plan/"+oRep[0].planPDF ;
              //console.log($("#formPdf input")); 
              $("#imgImport").attr("src",img);
              $("#imgImport").attr("alt",oRep[0].image); 
              $("#formImg label").html("Changer d'image :");

              $("#formPdf").append("<b>PDF originel => </b>").append($("<a id='planPdfActuel' target='blank'>"+oRep[0].planPDF+"</a>").attr("href",pdf));
              requestAjaxRemplirDimension(idArticle); 

              $("#formImg input").data("file",oRep[0].image);
              $("#formPdf input").data("file",oRep[0].planPDF);
            },
            error : function(oRep){
              console.log("error ger infos devis"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxRemplirDimension(idArticle){
    $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"listerDimensionsFerrure", "idProduit":idArticle },
            type : "GET",
            success : function(oRep){
              console.log(oRep); 
              for(var i=0 ; i<oRep.length ; i++){
                var refNom = $('#tabDims input[value="'+ oRep[i].nom +'"]').prop('checked', true).data("checked","true");
                refNom=$(refNom).parent(); 
                $(refNom).parent().data("id",oRep[i].id); 
                $(refNom).next().children().val(oRep[i].min).data("initial",oRep[i].min);
                $(refNom).next().next().children().val(oRep[i].max).data("initial",oRep[i].max);
                $("#"+oRep[i].nom).toggle();

                if( oRep[i].incluePrix ==1){
                  $('input:radio[value='+oRep[i].nom+']').prop("checked", true).data("checked","true"); // décoche le cb pour éviter les erreurs
                  DimIncluPrix = 1 ; 
                  $("#btnModePrix").val("Ne plus inclure la dimension dans le prix");
                  $("#modeDimensionsPrix").show();  
                  $("#tabPrix .nomCol").prepend('<th class="modeDimPrix">Dimension min</th><th class="modeDimPrix">Dimension max</th>'); 
                  $("#tabPrix .ligne").prepend('<td class="modeDimPrix"><input type="number"></td><td class="modeDimPrix"><input type="number"></td>'); 
                } 

              }
              requestAjaxRemplirPrix(idArticle);
            },
            error : function(oRep){
              console.log("error ger dims devis"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxRemplirPrix(idArticle){
    $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"TabPrix","idProduit":idArticle },
            type : "GET",
            success : function(oRep){
              console.log(oRep); 
              for(var i=0 ; i<oRep.length ; i++){

                var jLigneAux = jLigneTableauPrix.clone(true); 
                jLigneAux.data("id",oRep[i].id); 
                jLigneAux.find("td img").click(function(){
                  var idLigne=$(this).parent().parent().data("id");
                  console.log("SUPRESSIONS LIGNE PRIX ===>"+idLigne); 
                  requestAjaxSuprPrix(idLigne,idArticle); 
                });
                jLigneAux.find("td input").eq(0).val(oRep[i].qteMin).data("initial",oRep[i].qteMin);
                jLigneAux.find("td input").eq(1).val(oRep[i].qteMax).data("initial",oRep[i].qteMax);
                jLigneAux.find("td input").eq(2).val(oRep[i].prixU).data("initial",oRep[i].prixU);

                if(DimIncluPrix == 1){// var globale indiquant si une dimension est inclue dans le prix 
                  jLigneAux.prepend('<td class="modeDimPrix"><input type="number"></td><td class="modeDimPrix"><input type="number"></td>')
                  jLigneAux.find("td input").eq(0).val(oRep[i].dimMin).data("initial",oRep[i].dimMin);
                  jLigneAux.find("td input").eq(1).val(oRep[i].dimMax).data("initial",oRep[i].dimMax);
                }

                $(".contenuArticle2 #tabPrix").append(jLigneAux.clone(true)); 
  
              var first = $(".contenuArticle2 #tabPrix tr").last().offset().top; //distance du première élement à partir du haut 
              var second = $("label[for='tabOption']").offset().top;  //distance du second élement à partir du haut 
              var distance = parseInt(first) - parseInt(second) ; //distance entre les deux éléments

              console.log(distance); 
              if(distance > -70)
                $("label[for='tabOption']").before(jBrTabPrix.clone(true));
              }
              requestAJaxRemplirOptions(idArticle)
            },
            error : function(oRep){
              console.log("error ger prix devis"); 
            },
            dataType: "json"
          });
  }

  function requestAJaxRemplirOptions(idArticle){
    $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"Options", "idProduit":idArticle },
            type : "GET",
            success : function(oRep){
              console.log(oRep); 
               for(var i=0 ; i<oRep.length ; i++){

                jLigneAux=jLigneTableauOption.clone(true);
                jLigneAux.data("id",oRep[i].id);
                jLigneAux.find("td img").click(function(){
                  var idLigne=$(this).parent().parent().data("id");
                  console.log("SUPRESSIONS LIGNE OPTION"+idLigne); 
                  requestAjaxSuprOPtion(idLigne,idArticle); 
                });
                jLigneAux.find("td input").eq(0).val(oRep[i].nom).data("initial",oRep[i].nom); 
                jLigneAux.find("td input").eq(1).val(oRep[i].prix).data("initial",oRep[i].prix); 

                $(".contenuArticle2 #tabOption tbody").append(jLigneAux.clone(true)); 
                var first = $(".contenuArticle2 #tabOption tr").last().offset().top; //first element distance from top
                var second = $("input[value='ENREGISTRER LES MODIFICATIONS']").offset().top;  //second element distance from top
                var distance = parseInt(first) - parseInt(second) ; //distance between elements
                if(distance > -70)
                  $("input[value='ENREGISTRER LES MODIFICATIONS']").before(jBrTabOption.clone(true));
              }
            },
            error : function(oRep){
              console.log("error ger options devis"); 
            },
            dataType: "json"
          });

  }

  function requestAjaxModifierFerrure(titre,description,categorie,tags,matiere,finition,refFerrure){
    $.ajax({
            url: "libs/dataBdd.php?action=Ferrure" + "&titre=" + titre + "&description=" + description + "&tags=" + tags + "&categorie=" + categorie
            + "&matiere=" + matiere + "&finition=" + finition + "&idF=" + refFerrure,
            type : "PUT",
            success : function(oRep){
             console.log("Ferrure modifiée , id => "); console.log(oRep); 
             //requestCreateDimsFerrures(oRep,1);
            },
            error : function(oRep){
              console.log("error modif"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxModifierDimFerrure(min,max,nom,incluePrix,idLigne){
    console.log("infos=>"+min+max+nom+incluePrix+idLigne);
    $.ajax({
            url: "libs/dataBdd.php?action=Dimension" + "&nom=" + nom + "&min=" + min + "&max=" + max + "&incluePrix=" + incluePrix +
            "&idLigne=" + idLigne,
            type : "PUT",
            success : function(oRep){
             console.log("Dim modifiée , id => "); console.log(oRep); 
             return;
            },
            error : function(oRep){
              console.log("error modif dim"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxModifierOptionFerrure(idLigne,nom,prix){
      console.log("INFOS=>"+idLigne+nom+prix);
    $.ajax({
            url: "libs/dataBdd.php?action=Option" + "&nom=" + nom + "&prix=" + prix + "&idLigne=" + idLigne,
            type : "PUT",
            success : function(oRep){
             console.log("Option modifiée , id => "); console.log(oRep); 
             return;
            },
            error : function(oRep){
              console.log("error modif options"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxModifierPrixFerrure(idLigne,qteMin,qteMax,prix,dimMin=0,dimMax=0){
     $.ajax({
            url: "libs/dataBdd.php?action=Prix" + "&qteMin=" + qteMin + "&qteMax=" + qteMax + "&id=" + idLigne
            + "&prixU=" + prix + "&dimMin=" + dimMin + "&dimMax=" + dimMax,
            type : "PUT",
            success : function(oRep){
             console.log("Prix modifiée , id => "); console.log(oRep); 
             return;
            },
            error : function(oRep){
              console.log("error modif prix"); 
            },
            dataType: "json"
          });
  }

  function requestAJaxModifierImg(idFerrure,img){
    $.ajax({
            url: "libs/dataBdd.php?action=Image" + "&idF=" + idFerrure + "&nom=" + img,
            type : "PUT",
            success : function(oRep){
             console.log("Prix modifiée , id => "); console.log(oRep); 
             return;
            },
            error : function(oRep){
              console.log("error modif img"); 
            },
            dataType: "json"
          });
  }

  function requestAJaxModifierPdf(idFerrure,nom,plan){
    $.ajax({
            url: "libs/dataBdd.php?action=Pdf" + "&idF=" + idFerrure + "&nom=" + nom + "&plan=" + plan,
            type : "PUT",
            success : function(oRep){
             console.log("Pdf modifiée , id => "); console.log(oRep); 
             return;
            },
            error : function(oRep){
              console.log("error modif pdf"); 
            },
            dataType: "json"
          });
  }

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
      console.log("TESTEND=>"+stopAjout+$("#formPdf input").val());
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
            url:"delete.php?lien="+lien,
            type : "DELETE",
            success : function(oRep){
              console.log(oRep); 
            },
            dataType: "json"
          });
  }

  function requestAjaxSuprDim(idLigne,idArticle){
    $.ajax({
            url: "libs/dataBdd.php?action=Dimension&id="+idLigne,
            type : "DELETE",
            success : function(oRep){
              console.log("Dim supprimée!!");
              console.log(oRep);  
            },
            error : function(oRep){
              console.log("ERREUR"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxSuprPrix(idLigne,idArticle){
    $.ajax({
            url: "libs/dataBdd.php?action=Prix&id="+idLigne,
            type : "DELETE",
            success : function(oRep){
              console.log("Prix supprimée!!");
              console.log(oRep);  
            },
            error : function(oRep){
              console.log("ERREUR"); 
            },
            dataType: "json"
          });
  }

  function requestAjaxSuprOPtion(idLigne,idArticle){
    $.ajax({
            url: "libs/dataBdd.php?action=Option&id="+idLigne,
            type : "DELETE",
            success : function(oRep){
              console.log("Option supprimée!!");
              console.log(oRep);  
            },
            error : function(oRep){
              console.log("ERREUR"); 
            },
            dataType: "json"
          });
  }

 </script>

 <body>
 	<div class="container">

     <br><br><div id="erreur"></div>
     <br><div id="uploadOK"></div>

 		<div class="row" style="text-align: center;">
 		<h2 id="titlePage">Ajouter un produit</h2><br><br><br>
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

    <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>
 </body>
