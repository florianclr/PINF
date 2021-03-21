
<?php
	$produit = valider("produit");
	$couleurCat=valider("categorie");
	$idUser = valider("idUser","SESSION"); 
	
	if (!valider("connecte","SESSION")) {
  		header("Location:index.php?view=connexion");
  		die("");
	}
?>

<link href="css/produit.css" rel="stylesheet"> 
<script type="text/javascript">

  var produit="<?php echo $produit; ?>";
  var couleurCat="<?php echo $couleurCat; ?>";
  var idUser = "<?php echo $idUser; ?>";
  var couleurFond;
  var tab = [];

  $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"CouleurCategories","categorie":couleurCat},
      type : "GET",
      success: function(oRep){
        console.log(oRep);
        couleurFond=oRep[0].couleur;

      },
    error : function(jqXHR, textStatus) {
        console.log("erreur");
    },
    dataType: "json"
  });

  var prixTot = 0, prixDisplay = 0;
  var prixTemp = 0;
  var qte = 1;
  var dim = 0;
  var ancienCoeff = 1;
  var isPrixInclude = '';
  var areOpt = 1;

  var jImg=$('<div class="card h-100" id="imgProduct"><img class="card-img-top" alt=""/></div>');

  var jTitre=$('<div class="card h-100" id="titleProduct"><h4 class="card-title"></h4></div>');

  var jDescription=$('<h5>Description</h5><p id="description"></p>');
  
  var jTable=$('<div id="T1"><table><tr><td>Matière</td><td id="mat"></td></tr><tr><td >Finition</td><td id="fin"></td></tr><tr><td>N° de plan</td><td id="plan"></td></tr></table></div><br>');

  var jLien = $('<a></a>');

  var jTablePrix=$('<table id="prix"><tr id="qte"></tr></table>');

  var jLabel=$('<div id="label">Options possibles :</div>');

  var jTable3=$('<table id="options"></table>');

  var jPopup = $("<div id='popUpDevis' title='Ajouter la ferrure au devis'>");
  
  var jQuantiteOpt = $('<input type="number" id="qteOpt" value="1" min="1"/>').change(function() {
  
  				var nouveauCoeff;
  				console.log("ancienCoeff="+ancienCoeff);
  
  				if (parseInt($(this).val(), 10) <= 0) {
      				console.log("VALEUR NEGATIVE");
      				$("#indic").append("<div id='warning'>La quantité de l'option est négative ou nulle</div>");
		  			prixDisplay = prixTemp;
		  			$('input:checkbox[id="checkOpt"]').prop("checked", false);
        			$('input:checkbox[id="checkOpt"]').each(function() {
        				$("#qteOpt").remove();
        			});
        			$("#majPrix").html(prixDisplay);
      			}
      				
      			if (parseInt($(this).val(), 10) > parseInt(qte, 10)) {
      				console.log("VALEUR TROP GRANDE");
      				$("#indic").append("<div id='warning'>La quantité de l'option est supérieure à la quantité globale choisie</div>");
		  			prixDisplay = prixTemp;
		  			$('input:checkbox[id="checkOpt"]').prop("checked", false);
        			$('input:checkbox[id="checkOpt"]').each(function() {
        				$("#qteOpt").remove();
        			});
        			$("#majPrix").html(prixDisplay);
      			}
      			
      			else if (parseInt($(this).val(), 10) <= parseInt(qte, 10) && parseInt($(this).val(), 10) > 0) {
        			prixTot = parseInt($(this).parent().parent().find('td').eq(2).html(), 10);
              		nouveauCoeff = $(this).val();
              		console.log(nouveauCoeff);
              		console.log(ancienCoeff);
              		
              		// augmente quantité
              		if (nouveauCoeff > ancienCoeff) {
              			console.log("tes+");
              			if (prixDisplay == prixTemp) {
              				prixDisplay += prixTot*nouveauCoeff;
		          			// prixDisplay += prixTot*(nouveauCoeff-ancienCoeff);
		          			$("#majPrix").html(prixDisplay);
		          		}
		          		else
		          			prixDisplay += prixTot*(nouveauCoeff-ancienCoeff);
		          			$("#majPrix").html(prixDisplay);
              		}
                	
					// diminue quantité	
                	if (nouveauCoeff < ancienCoeff) {
                		console.log("tes-");
                		prixDisplay -= prixTot*(ancienCoeff-nouveauCoeff);
                		$("#majPrix").html(prixDisplay);
              		}
              		ancienCoeff = nouveauCoeff;
              		
              		console.log("maj prix avec un input :");
                	console.log(prixDisplay);
      			}
  });

  var jQuantite = $('<div id="quantite">Quantité = <input type="number" id="qteFerrure" value="1" min="1"/></div>').change(function() {
  
  				if ($("#qteFerrure").val() <= 0) {
  					console.log("VALEUR NEGATIVE");
  					$('input:checkbox[id="checkOpt"]').prop("checked", false);
        			$("#qteOpt").remove();
  					$("#warning").remove();
      				$("#quantite").append("<div id='warning'>La quantité est négative ou nulle</div>");
      				$("#majPrix").html(0);
      			}
      			else {
        			qte = $("#qteFerrure").val();
        			$('input:checkbox[id="checkOpt"]').prop("checked", false);
        			$("#qteOpt").remove();
        			$("#warning").remove();
        			calculPrix();
      			}
  });

  var jCheckBox=$('<td></td>').append($('<input id="checkOpt" type="checkbox"/>').click(function() {
  
  			  var inputSupr;

              if ($(this).prop("checked") == true) {
              	$("#warning").remove();
                $(this).parent().append(jQuantiteOpt.clone(true));
              	$("#qteOpt").attr('max', qte);
              	
              	prixTot = parseInt($(this).parent().parent().find('td').eq(2).html(), 10);	
              	prixDisplay += prixTot;
              	console.log(prixDisplay);
              	ancienCoeff = 1;
              	$("#majPrix").html(prixDisplay);
              	
              }
              else if ($(this).prop("checked") == false) {
                inputSupr = $(this).parent().find('input[type="number"]'); // on cherche dans le parent un input number 
                nouveauCoeff = $(this).parent().find('input[type="number"]').val();
                $(inputSupr).remove();
                
                if (nouveauCoeff <= qte && nouveauCoeff > 0) {
                	prixTot = nouveauCoeff*parseInt($(this).parent().parent().find('td').eq(2).html(), 10);
                	prixDisplay -= prixTot;
                	console.log("prix total :");
                	console.log(prixDisplay);
                	$("#majPrix").html(prixDisplay);
                }
                	
              }
              
  }));
  
  var jDimension = $('<input type="number" id="choixDim"/>').change(function() {

  			$("#warning").remove();
  			
  			if (parseFloat($(this).val()) <= parseFloat($(this).prop("max")) && parseFloat($(this).val()) >= parseFloat($(this).prop("min"))) {
  				if (isPrixInclude != '') {
			  		dim = $("."+isPrixInclude).val();
			  		calculPrix();
			  	}
		  	}
		  	else {
		  		if (isPrixInclude != '')
		  			$("#majPrix").html(0);
		  		$("#dimFond").append("<div id='warning'>La dimension n'est pas inclue dans l'intervalle possible</div>");
		  	}
  		
  });
  
  var jDevis = $('<select name="devis" id="listeDevis"></select>');

  var compt = 1;

  var jButton = $('<div class="buttonsCenter"><input type="button" id="addDevis" value="Ajouter la ferrure à un devis"/></div>').click(function(){

    // COPIES
      var hauteur, largeur;
      var jclonePrix = $("#prix").clone(true);
      var jcloneOption = $("#options").clone(true);
      var jcloneImg = $(".card-img-top").clone(true);
      $(".contenu").prepend(jPopup.clone(true));
    // Réaffichage de l'image de la ferrure
      $("#popUpDevis").append(jcloneImg);
      hauteur = $("#imgProduct").height();
      largeur = $("#imgProduct").width();
	  $(".card-img-top").css("max-height", hauteur);
	  $(".card-img-top").css("max-width", largeur);
    // Quantité choisie
      $("#popUpDevis").append(jQuantite.clone(true));
      $("#popUpDevis").append('<div id="indicQ">Appuyez sur ENTREE dès que vous saisissez une quantité au clavier</div>');
      // prix correspondant
    // tabelau prix copie
      $("#popUpDevis").append(jclonePrix);
      $("#popUpDevis").append("<br><br>");
    // label tab options
    	if (areOpt == 1) {
      		$("#popUpDevis").append(jLabel.clone(true)); 
    		// Tableau options copie
      		$("#popUpDevis").append(jcloneOption);
      		$("#popUpDevis").append('<div id="indic">Appuyez sur ENTREE dès que vous saisissez une quantité au clavier</div>');
    		// pour chaque option on ajoute une checkbox pour l'inclure ou pas ds le prix
      		$("#popUpDevis #options tr").each(function(){
        		$(this).prepend(jCheckBox.clone(true));
      		});
      }
      listerDimensions();
      listerCouleurs();

      $("#popUpDevis").dialog({
         modal: true, // permet de rendre le reste de la page inaccesible tant que la pop up est ouverte
         height: 1200,
         width: 800,
         buttons: { // on ajoute des boutons à la pop up 
             "Ajouter au devis": function(){
             	var flagA = 1, flagB = 1, flagC = 1;
             	var flag = 1;
             	var dimA, dimB, dimC;
             	var couleurF;
             
             	qte = $("#qteFerrure").val();
				if ($("#listeDevis option:selected").text() != '--' && qte > 0) {
					/*
					console.log($("#listeDevis option:selected").val());
					console.log("prixDisplay="+prixDisplay);
					console.log("qte="+qte);
					console.log("produit="+produit);
					console.log("isPrixInclude="+isPrixInclude);
					*/
					
					// vérif des dimensions
					if ($(".a").val() != undefined && $(".a").val() != '' && parseFloat($(".a").val()) >= parseFloat($(".a").prop("min")) && parseFloat($(".a").val()) <= parseFloat($(".a").prop("max")))
						dimA = $(".a").val();
					else {
						if (parseFloat($(".a").val()) < parseFloat($(".a").prop("min")) || parseFloat($(".a").val()) > parseFloat($(".a").prop("max"))) {
							// a en dehors de l'intervalle
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagA = 0;
						}
						else if ($(".a").length != 0) {
							// a = champ vide
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagA = 0;
						}
						else
							dimA = -1;
					}
						
					if ($(".b").val() != undefined && $(".b").val() != '' && parseFloat($(".b").val()) >= parseFloat($(".b").prop("min")) && parseFloat($(".b").val()) <= parseFloat($(".b").prop("max")))
						dimB = $(".b").val();
					else {
						if (parseFloat($(".b").val()) < parseFloat($(".b").prop("min")) || parseFloat($(".b").val()) > parseFloat($(".b").prop("max"))) {
							// b en dehors de l'intervalle
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagB = 0;
						}
						else if ($(".b").length != 0) {
							// b = champ vide
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagB = 0;
						}
						else
							dimB = -1;
					}
						
					if ($(".c").val() != undefined && $(".c").val() != '' && parseFloat($(".c").val()) >= parseFloat($(".c").prop("min")) && parseFloat($(".c").val()) <= parseFloat($(".c").prop("max")))
						dimC = $(".c").val();
					else {
						if (parseFloat($(".c").val()) < parseFloat($(".c").prop("min")) || parseFloat($(".c").val()) > parseFloat($(".c").prop("max"))) {
							// c en dehors de l'intervalle
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagC = 0;
						}
						else if ($(".c").length != 0) {
							// c = champ vide
							$("#warning2").remove();
							$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
							flagC = 0;
						}
						else
							dimC = -1;
					}
					
					console.log(dimA);
					console.log(dimB);
					console.log(dimC);
					
					// vérif des couleurs
					if ($("input[type=radio]:checked").val() != undefined)
						couleurF = $("input[type=radio]:checked").val();
					else {
						// couleur non renseignée
						$("#warning2").remove();
						$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
						flag = 0;
					}
				}
				else {
					// devis non renseigné ou qte < 0
					$("#warning2").remove();
					$("#popUpDevis").append("<div id='warning2'>Impossible d'ajouter la ferrure à un devis</div>");
					flag = 0;
				}
				
				if (flag == 1 && flagA == 1 && flagB == 1 && flagC == 1) {
					$.ajax({
						url: "libs/dataBdd.php",
						data:{"action":"AjouterAuDevis","idUser":idUser, "refFerrures":produit, "refDevis":$("#listeDevis option:selected").val(), "quantite":qte, "a":dimA, "b":dimB, "c":dimC, "prix": prixDisplay, "couleur":couleurF},
						type : "POST",
						success: function(oRep){
							console.log(oRep);
                			$("#popUpDevis").remove(); // supprime la pop up
							$("#ajoutOK").remove();
                			$(".contenu").append('<div id="ajoutOK">La ferrure a bien été ajoutée au devis</div>');
                			prixTemp = 0;
                			prixTot = 0;
                			prixDisplay = 0;
						},
						error : function(jqXHR, textStatus) {
						  console.log("erreur");  
						},
						dataType: "json"
					});
				}
					
             },
             "Quitter": function() {
                $(this).dialog("close"); // ferme la pop up 
                $(this).remove(); // supprime la pop up
             },
         },
         close: function() { // lorsqu'on appuie sur la croix pour fermer la pop-up 
            console.log("Fermeture du pop-up");
            $(this).remove(); // supprime la pop up 
         }
      }); 
    });
  
	// T1

function genInfos() {
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        console.log(couleurFond);
        
        $(".product").append(jTitre.clone(true).html(oRep[0].titre));
        $("#titleProduct").css("background-color", couleurFond);
        $(".row").prepend(jImg.clone(true));
        $(".row .card-img-top").attr('src',"./images/"+oRep[0].image);
        $(".contenu").append(jDescription.clone(true));
        $("#description").html(oRep[0].description);
        
        // T1
        $(".contenu").append(jTable.clone(true));
        $("#mat").html(oRep[0].nomM);
        $("#fin").html(oRep[0].nomF);
        $("#plan").append(jLien.clone(true).attr("href","templates/telecharger.php?pdf="+oRep[0].planPDF).html(oRep[0].numeroPlan));
        $(".contenu").append(jTablePrix.clone(true));  
        $(".contenu").append(jLabel.clone(true));
        $(".contenu").append(jTable3.clone(true));
        $(".contenu").append(jButton.clone(true)); 
        genTabPrix();
        return; 
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });   
}
	   
// TABLEAU PRIX //
function genTabPrix(){
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"TabPrix","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        var compt = 0;
        var qteStock = oRep[0].qteMin; 
        var nbqte = 0 ;
        var nbDim = 0 ; 

         if(oRep[0].dimMin!=null && oRep[0].dimMax!=null ){

         $("#qte").append($('<td></td>').html("PU / Quantité"));
      
            for (var i = 0; i <oRep.length; i++) {
                if(qteStock != oRep[i].qteMin)
                  break;
                else 
                  qteStock = oRep[i].qteMin ; 
                $("#prix tbody").append($('<tr></tr>').append($('<td></td>').html(oRep[i].dimMin+" à "+oRep[i].dimMax+" m")).attr("id",i));
                compt++;
            }//fin for 
        }//fin if

         else {
            $("#qte").append($('<td></td>').html("Quantité"));
            $("#prix tbody").append($('<tr id="0"></tr>').append($('<td></td>').html("PU")));
            compt++;
        }
        nbDim = compt; 
        console.log("compt="+compt);
        while (compt > 0) {
          $("#prix").after($("</br>"));
          compt--;  
        }
        
          $("#description").html(oRep[0].description);
          
        
            for (var i = 0; i<oRep.length; i+=nbDim) {
              if (oRep[i].qteMin == 0)
                $("#qte").append($('<td class="qteU"></td>').html("1"));
              else
                  $("#qte").append($('<td class="qteU"></td>').html(" ≥ "+oRep[i].qteMin));  
              nbqte ++ ; 
            }//fin for

          oRep.sort(function(a,b){ // on trie le tableau des paragraphes en fonction des ordres 
            return parseFloat(a.dimMin) - parseFloat(b.dimMin) ; 
         });  
          console.log(oRep);
          var ligne = 0 ; 
          for(var j=0;j<oRep.length;j+=nbqte){
                        ligne = j/nbqte ;
                        console.log(ligne);
                        for(var k= j;k<j+nbqte;k++ )
                          $('#'+ligne).append($('<td class="prixUnit"></td>').html(oRep[k].prixU+" €"));
                      }
        genTabOption();
        return; 
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");
    },
    dataType: "json"
    });
  
}
  
  // OPTION

function genTabOption(){
   $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Options","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        compt = 0;
        
        if(oRep.length!=0){
          	for (var i = 0; i< oRep.length; i++) {
		         $("#options").append($('<tr></tr>').attr("id",oRep[i].id));
		         $("#"+oRep[i].id).append($('<td></td>').html(oRep[i].nom)).append($('<td class="prixOpt"></td>').html(oRep[i].prix+" €"));
		         compt++;
        	}
          
		    console.log("compt="+compt);
		    while (compt > 0) {
			   	$("#options").after($("</br>"));
			  	compt--;  
     	 	}
        }
        else {
        	areOpt = 0;
        	$("#label").remove();
        }
        	
        return ; 
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");
    },
    dataType: "json"
    });
    return;   
}

function listerDimensions() {
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"listerDimensionsFerrure","idProduit":produit},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        
		if (oRep.length != 0) {
			$("#popUpDevis").append($('<div id="titleDim">Dimensions :</div>'));
			$("#popUpDevis").append($('<div id="dimFond"></div>'));
			
			for (var i = 0; i < oRep.length; i++) {
				 if (oRep[i].incluePrix == 1) {
				 	isPrixInclude = oRep[i].nom;
				 }
			
				 $("#dimFond").append($('<div id="dim"></div>').html(oRep[i].nom+' = '));
				 $("#dim").attr("id", "dim"+(i+1));
				 $("#dim"+(i+1)).css("margin-right", "100px");
				 if (i != 0)
				 	$("#dim"+(i+1)).css("margin-top", "10px");
				 	
				 $("#dim"+(i+1)).append(jDimension.clone(true));
				 $("#choixDim").attr("id", "choixDim"+(i+1)); 
				 $("#choixDim"+(i+1)).addClass(oRep[i].nom);
				 $("#choixDim"+(i+1)).attr("value", oRep[i].min);
				 $("#choixDim"+(i+1)).attr("min", oRep[i].min);
				 $("#choixDim"+(i+1)).attr("max", oRep[i].max);
				 $("#choixDim"+(i+1)).css("width", "70px");
				 $("#choixDim"+(i+1)).css("margin-right", "5px");
				 if (i != 0)
				 	$("#choixDim"+(i+1)).css("margin-top", "10px");
				 
				 $("#dim"+(i+1)).append("mm");
				 $("#dim"+(i+1)).css("display", "inline");
				 
				 // affichage valeurs min et max pour guider l'utilisateur
				 $("#dimFond").append($('<div id="dimIndic"></div>'));
				 $("#dimIndic").attr("id", "dimIndic"+(i+1));
				 $("#dimIndic"+(i+1)).append("[");
				 $("#dimIndic"+(i+1)).append(oRep[i].min);
				 $("#dimIndic"+(i+1)).append(" ; ");
				 $("#dimIndic"+(i+1)).append(oRep[i].max);
				 $("#dimIndic"+(i+1)).append("]</br>");
				 $("#dimIndic"+(i+1)).css("display", "inline");
				 if (i != 0)
				 	$("#dimIndic"+(i+1)).css("margin-top", "10px");
				 	
				 if (i == oRep.length-1)
				 	$("#popUpDevis").append('<div id="indic">Appuyez sur ENTREE dès que vous saisissez une quantité au clavier</div>');
			}
			console.log(isPrixInclude);
		}
			
		calculPrix();
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });   
}

function listerCouleurs() {
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"listerCouleursFerrure"},
    type : "GET",
    success: function(oRep){
        console.log(oRep);
        $("#popUpDevis").append($('<div id="titleCol">Couleur choisie :</div>'));
        $("#popUpDevis").append($('<div id="colFond"></div>'));
        
        for (var i = 0; i < oRep.length; i++) {
             $("#colFond").append($('<input type="radio" id="choixCol" name="couleur"/>'));
             $("#choixCol").attr("id", "choixCol"+(i+1)); 
             $("#choixCol"+(i+1)).attr("value", oRep[i].id);
             if (i != 0)
             	$("#choixCol"+(i+1)).css("margin-left", "40px");
             	
             $("#colFond").append($('<label id="labelCol"></label>'));
             $("#labelCol").attr("id", "labelCol"+(i+1)); 
             $("#labelCol"+(i+1)).attr("for", "choixCol"+(i+1));
             $("#labelCol"+(i+1)).css("margin-left", "5px");
             $("#labelCol"+(i+1)).html(oRep[i].couleur);
    	}
    	finirCommande();
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });   
}

function finirCommande() {
	$("#popUpDevis").append('<div id="titlePrix">PRIX TOTAL HT : <div id="majPrix"></div></div>');
	$("#titlePrix").append(" €");
	
	// select devis
	$("#popUpDevis").append('<label for="devis" id="titleDev">Choisissez un devis préexistant : </label>');
	$("#popUpDevis").append(jDevis.clone(true));
	
	$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"DevisUser","idUser":idUser},
		type : "GET",
		success: function(oRep){
			console.log(oRep);
			
			// label par défaut
			$("#listeDevis").append($("<option selected='selected'></option>").text("--"));
		
			for (var i = 0; i < oRep.length; i++) {
				$("#listeDevis").append($("<option></option>").attr("value", oRep[i].id).text(oRep[i].nomProjet));
			}
		},
		error : function(jqXHR, textStatus) {
			console.log("erreur");
		},
		dataType: "json"
	});	
}

function calculPrix() {
	if (isPrixInclude != '') {
		if (dim == 0)
			dim = $("."+isPrixInclude).val();
		
		$.ajax({
			url: "libs/dataBdd.php",
			data:{"action":"calculerPrix","idProduit":produit,"quantite":qte,"dimension":dim},
			type : "GET",
			success: function(oRep){
				prixDisplay -= prixTemp;
				prixTemp = parseInt(oRep,10)*qte;
				prixDisplay += parseInt(oRep,10)*qte;
				console.log(prixDisplay);
				$("#majPrix").html(prixDisplay);
			},
			error : function(jqXHR, textStatus) {
			  console.log("erreur");
			},
			dataType: "json"
		});
	}
	else {
		// aucune dimension inclue dans le prix
		$.ajax({
			url: "libs/dataBdd.php",
			data:{"action":"calculerPrix","idProduit":produit,"quantite":qte,"dimension":"0"},
			type : "GET",
			success: function(oRep){
				prixDisplay -= prixTemp;
				prixTemp = parseInt(oRep,10)*qte;
				prixDisplay += parseInt(oRep,10)*qte;
				console.log(prixDisplay);
				$("#majPrix").html(prixDisplay);
			},
			error : function(jqXHR, textStatus) {
			  console.log("erreur");
			},
			dataType: "json"
		});
	}
}
  
  // CHARGEMENT PAGE
$(document).ready(function(){
    genInfos();
});


</script>
<body>

   <br/><br/>
    <div class="container">    
      <div class="product"></div>
      <div class="row">
      <div class="contenu"></div>  
      </div>
    
    </div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>

</body>

</html>

