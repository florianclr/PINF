
 <?php
$produit = valider("produit");
?>


<script type="text/javascript">

  var tab = ['mediumblue', 'darkred', 'yellowgreen', 'indigo', 'darkcyan'];

  var produit="<?php echo $produit; ?>";
  var img;
  console.log(produit);

  var jImg=$('<div class="card h-100" id="imgProduct"><img class="card-img-top" alt=""/></div>');

  var jImgPopUp=$('<div class="card h-100" id="imgProductPopUp"><img class="card-img-top" alt=""/></div>');

  var jTitre=$('<div class="card h-100" id="titleProduct"><h4 class="card-title"></h4></div>');

  var jDescription=$('<div class="contenu"><h5>Description</h5><p id="description"></p></div>');
  
  var jTable=$('<div id="T1"><table><tr><td>Matière</td><td id="mat"></td></tr><tr><td >Finition</td><td id="fin"></td></tr><tr><td>N° de plan</td><td id="plan"></td></tr></table></div>');

  var jLien = $('<a></a>');

  var jTable2=$('<div id="T2"><table><tr id="qte"></tr></table></div>');
  var jTable2PopUp=$('<div id="T2PopUp"><table><tr id="qteP"></tr></table></div>');

  var jLabel=$('<br><div id="label">Options possibles :</div>');
  var jLabelPopUp=$('<br><div id="labelPopUp">Options possibles :</div>');

  var jTable3=$('<div id="T3"><table id="options"></table></div>');
  var jTable3PopUp=$('<div id="T3PopUp"><table id="optionsP"></table></div>');
  var jCheckBox=$('<td><input id="checkOpt" type="checkbox"/></td>');

  var jButton = $('<input type="button" id="addDevis" value="Ajouter la ferrure à un devis"/>');
  
  var jPopup = $('<div id="popUpDevis" title="Ajouter la ferrure au devis">');
  
  var jQuantite = $('<div id="quantite">Quantité = <input type="number" id="qteFerrure" value="1" min="1"/></div>');
  
  var jQuantiteOpt = $('<input type="number" id="qteOpt" value="1" min="1"/>');
  
  var compt = 1;
  var popup = 0;

	// T1
	$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    type : "GET",
    success: function(oRep){
      	var couleurFond = tab[(oRep[0].refcategories)-1];
		img = oRep[0].image;

        console.log(oRep);
        console.log(couleurFond);
        
        $(".product").append(jTitre.clone(true).html(oRep[0].titre));
        $("#titleProduct").css("background-color", couleurFond);
        $(".row").append(jImg.clone(true));
        $(".row .card-img-top").attr('src',"./images/"+img);
        $(".row").append(jDescription.clone(true));
        $("#description").html(oRep[0].description);
        
        // T1
        $(".contenu").append(jTable.clone(true));
        $("#mat").html(oRep[0].nomM);
        $("#fin").html(oRep[0].nomF);
        $("#plan").append(jLien.clone(true).attr("href","templates/telecharger.php?pdf="+oRep[0].planPDF).html(oRep[0].numeroPlan))
        
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
    });   
  
  
  	function tab2_1(popup) {
		$.ajax({
		  url: "libs/dataBdd.php",
		  data:{"action":"Dim","idProduit":produit},
		  type : "GET",
		  success: function(oRep){
		    console.log(oRep);
		    
		    // cas insertion dans la page (normal)
		    if (!popup) { 
				$(".contenu").append(jTable2.clone(true)); 
					
				if(oRep[0].dimMin!=null && oRep[0].dimMax!=null ){
					$("#qte").append($('<td></td>').html("PU / Quantité"));
					
				  	for (var i = 0; i < oRep.length; i++) {
				    	$("#T2 tbody").append($('<tr></tr>').append($('<td></td>').html(oRep[i].dimMin+" à "+oRep[i].dimMax+" m")).attr("id",i));
				    	compt++;
				  	}//fin for 
						
				}//fin if

				else {
					$("#qte").append($('<td></td>').html("Quantité"));
				  	$("#T2 tbody").append($('<tr id="0"></tr>').append($('<td></td>').html("PU")));
				  	compt++;
				}
				
				console.log("compt="+compt);
				while (compt > 0) {
					$(".contenu").append($("</br></br>"));
					compt--;	
				}
			}
			
			// -----------------------------------------------------------
			// cas insertion dans la pop-up
			else {
				$("#popUpDevis").append(jTable2PopUp.clone(true)); 
					
				if(oRep[0].dimMin!=null && oRep[0].dimMax!=null ){
					$("#qteP").append($('<td></td>').html("PU / Quantité"));
					
				  	for (var i = 0; i < oRep.length; i++) {
				    	$("#T2PopUp tbody").append($('<tr></tr>').append($('<td></td>').html(oRep[i].dimMin+" à "+oRep[i].dimMax+" m")).attr("id",i+1000));
				    	compt++;
				  	}//fin for 
						
				}//fin if

				else {
					$("#qteP").append($('<td></td>').html("Quantité"));
				  	$("#T2PopUp tbody").append($('<tr id="1000"></tr>').append($('<td></td>').html("PU")));
				  	compt++;
				}
				
				console.log("compt="+compt);
				while (compt > 0) {
					$("#popUpDevis").append($("</br></br>"));
					compt--;	
				}
			
			}

		  },
		  error : function(jqXHR, textStatus) {
		    console.log("erreur");
		  },
		  dataType: "json"
		  });  
    }
    
    function tab2_2(popup) {
		$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"Qte","idProduit":produit},
		type : "GET",
		success: function(oRep){
		    console.log(oRep);
		    
		    // cas insertion dans la page (normal)
		    if (!popup) {
				if(oRep.length != 0){
				
				    for (var i = 0; i<oRep.length; i++) {
				    	if (oRep[i].qteMin == 0)
				    		$("#qte").append($('<td class="qteU"></td>').html("1"));
				    	else
				        	$("#qte").append($('<td class="qteU"></td>').html(" ≥ "+oRep[i].qteMin));

				        console.log(oRep[i].qteMin);
				        console.log(oRep[i].qteMax);

				        $.ajax({
				          url: "libs/dataBdd.php",
				          data:{"action":"Prix","idProduit":produit,"qteMin":oRep[i].qteMin,"qteMax":oRep[i].qteMax},
				          type : "GET",
				          success: function(oRep){
				              console.log(oRep);
				              for(var j=0;j<oRep.length;j++){
				                $('#'+j).append($('<td class="prixUnit"></td>').html(oRep[j].prixU+" €"));
				              }
				          },
				          error : function(jqXHR, textStatus) {
				            console.log("erreur");
				          },
				          dataType: "json"
				          });
				    
				      
				    }//fin for
				    
				  
				}//fin if 
		    }
		    
		    // cas insertion dans la pop-up
		    else {
		    	if(oRep.length != 0){
				
				    for (var i = 0; i<oRep.length; i++) {
				    	if (oRep[i].qteMin == 0)
				    		$("#qteP").append($('<td class="qteU"></td>').html("1"));
				    	else
				        	$("#qteP").append($('<td class="qteU"></td>').html(" ≥ "+oRep[i].qteMin));

				        $.ajax({
				          url: "libs/dataBdd.php",
				          data:{"action":"Prix","idProduit":produit,"qteMin":oRep[i].qteMin,"qteMax":oRep[i].qteMax},
				          type : "GET",
				          success: function(oRep){
				              console.log(oRep);
				              for(var j=1000;j<(oRep.length+1000);j++){
				                $('#'+j).append($('<td class="prixUnit"></td>').html(oRep[j-1000].prixU+" €")); // BUG
				              }
				          },
				          error : function(jqXHR, textStatus) {
				            console.log("erreur");
				          },
				          dataType: "json"
				          });
				    
				      
				    }//fin for
				    
				  
				}//fin if 
		    }
		},
		error : function(jqXHR, textStatus) {
		  console.log("erreur");
		},
		dataType: "json"
		});//fin 1er requete
    }

	function tab3(popup) {
		$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"Options","idProduit":produit},
		type : "GET",
		success: function(oRep){
		    console.log(oRep);
		    var qte;
		    compt = 0;
		    
		    // cas insertion dans la page (normal)
		    if (!popup) {
				if(oRep.length!=0){
				  
				  $(".contenu").append(jLabel.clone(true));
				  $(".contenu").append(jTable3.clone(true));
				  
				  
				  for (var i = 0; i< oRep.length; i++) {
				     $("#options").append($('<tr></tr>').attr("id",oRep[i].id));
				     $("#"+oRep[i].id).append($('<td></td>').html(oRep[i].nom)).append($('<td class="prixOpt"></td>').html(oRep[i].prix+" €"));
				     compt++;
				  }
				  
				  while (compt > 0) {
				  	$(".contenu").append($("</br></br>"));
					compt--;
				  }
				}
		    }
		    
		    // cas insertion dans la pop-up
		    else {
		    	if(oRep.length != 0){
				  
				  $("#popUpDevis").append(jLabelPopUp.clone(true));
				  $("#popUpDevis").append(jTable3PopUp.clone(true));
				  
				  for (var i = 1000; i < (oRep.length+1000); i++) {
				     $("#optionsP").append($('<tr></tr>').attr("id",oRep[i-1000].id+1000));
				     $("#"+oRep[i-1000].id+1000).append(jCheckBox.clone(true).attr("id",oRep[i-1000].id+2000))
				     		 .append($('<td></td>').html(oRep[i-1000].nom))
				     		 .append($('<td class="prixOpt"></td>').html(oRep[i-1000].prix+" €"));
				     		 
				     $("#checkOpt").attr('id', "checkOpt"+oRep[i-1000].id);
				     		 
				     $("#checkOpt"+oRep[i-1000].id).click(function() {
				     	if ($(this).prop("checked") == true) {
				     		$(this).parent().append(jQuantiteOpt.clone(true));
				     		qte = $("#qteFerrure").val();
							console.log(qte);
							$("#qteOpt").attr('max', qte);
				     	}
				     	else if ($(this).prop("checked") == false)
				     		$("#qteOpt").remove();
				     });
				     		 
				     compt++;
				  }
				  
				  while (compt > 0) {
				  	$("#popUpDevis").append($("</br></br>"));
					compt--;
				  }
				}
		    }
		},
		error : function(jqXHR, textStatus) {
		  console.log("erreur");
		},
		dataType: "json"
		});
	}
	
	tab2_1(popup);
	tab2_2(popup);
	tab3(popup);
    
	$(document).ready(function(){
		var hauteur;
		
		$(".contenu").append(jButton.clone(true).click(function() {
			$(".contenu").append(jPopup.clone(true));
			$("#popUpDevis").dialog({
				 modal: true, // permet de rendre le reste de la page inaccesible tant que la pop up est ouverte
				 height: 800,
				 width: 1000,
				 buttons: { // on ajoute des boutons à la pop up 
				     "Ajouter au devis": function(){
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
			
			// Réaffichage de l'image de la ferrure
			$("#popUpDevis").append(jImgPopUp.clone(true));
			$(".card-img-top").attr('src',"./images/"+img);
			hauteur = $(".card-img-top").height();
			$("#imgProductPopUp").css("max-height", hauteur);
			
			// Quantité choisie
			$("#popUpDevis").append(jQuantite.clone(true));
			
			
			// Réaffichage des tableaux des prix + options
			popup = 1;
			tab2_1(popup);
			tab2_2(popup);
			tab3(popup);
			
			
		}));
	});

</script>
<body>

   <br/><br/>
    <div class="container">    
      <div class="product"></div> <!-- TODO:MODIF NOM -->
      <div class="row"></div>
    <br/><br/><br/><br/><br/><br/><br/>
    </div>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>

</body>

</html>

