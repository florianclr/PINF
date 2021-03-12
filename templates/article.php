
 <?php
$produit = valider("produit");
?>


<script type="text/javascript">

  var tab = [];

  $.ajax({
	url: "libs/dataBdd.php",
    	data:{"action":"Categories"},
    	type : "GET",
    	success: function(oRep){
      		console.log(oRep);
      		for (var i = 0; i < oRep.length; i++) {
        		tab.push(oRep[i].couleur);
      		}
    	},
    error : function(jqXHR, textStatus) {
      	console.log("erreur");
    },
    dataType: "json"
  });

  var prixTot = 0;
  var prixDisplay = 0;
  var qte;
  var ancienCoeff = 1;

  var produit="<?php echo $produit; ?>";
  console.log(produit);

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
  
  				if (parseInt($("#qteOpt").val(), 10) <= 0)
      				console.log("VALEUR NEGATIVE");
      				
      			if (parseInt($("#qteOpt").val(), 10) > parseInt(qte, 10))
      				console.log("VALEUR TROP GRANDE");
      			else {
        			prixTot = parseInt($(this).parent().parent().find('td').eq(2).html(), 10);
              		nouveauCoeff = $(this).val();
              		console.log(nouveauCoeff);
              		console.log(ancienCoeff);
              		
              		// augmente quantité
              		if (nouveauCoeff > ancienCoeff) {
              			console.log("tes+");
              			prixDisplay += prixTot*(nouveauCoeff-ancienCoeff);
              		}
                	
					// diminue quantité	
                	if (nouveauCoeff < ancienCoeff) {
                		console.log("tes-");
                		prixDisplay -= prixTot*(ancienCoeff-nouveauCoeff);
              		}
              		ancienCoeff = nouveauCoeff;
              		
              		console.log("maj prix avec un input :");
                	console.log(prixDisplay);
      			}
  });

  var jQuantite = $('<div id="quantite">Quantité = <input type="number" id="qteFerrure" value="1" min="1"/></div>').keyup(function() {
  
  				if ($("#qteFerrure").val() <= 0)
      				console.log("VALEUR NEGATIVE");
      			else {
        			qte = $("#qteFerrure").val();
        			console.log(qte);
      			}
  });

  var jCheckBox=$('<td></td>').append($('<input id="checkOpt" type="checkbox"/>').click(function() {

              if ($(this).prop("checked") == true) {
                $(this).parent().append(jQuantiteOpt.clone(true));
              	$("#qteOpt").attr('max', qte);
              	
              	prixTot = parseInt($(this).parent().parent().find('td').eq(2).html(), 10);	
              	prixDisplay += prixTot;
              	ancienCoeff = 1;
              	/*$(this).parent().find('input[type="number"]').change(function() {
              		nouveauCoeff = $(this).parent().find('input[type="number"]').val();
              		
              	});*/
              	
              }
              else if ($(this).prop("checked") == false) {
                var inputSupr = $(this).parent().find('input[type="number"]'); // on cherche dans le parent un input number 
                nouveauCoeff = $(this).parent().find('input[type="number"]').val();
                $(inputSupr).remove();
                
                prixTot = nouveauCoeff*parseInt($(this).parent().parent().find('td').eq(2).html(), 10);
              
                prixDisplay -= prixTot;
                console.log("prix total :");
                console.log(prixDisplay);
              }
              
  }));

  var compt = 1;

  var jButton = $('<div class="buttonsCenter"><input type="button" id="addDevis" value="Ajouter la ferrure à un devis"/></div>').click(function(){

    // COPIES
      var hauteur, largeur;
      var jclonePrix = $("#prix").clone(true);
      var jcloneOption = $("#options").clone(true);
      var jcloneImg = $(".card-img-top").clone(true);
      console.log(jcloneImg);
      //console.log(jclonePrix); 
      $(".contenu").prepend(jPopup.clone(true));
    // Réaffichage de l'image de la ferrure
      $("#popUpDevis").append(jcloneImg);
      hauteur = $("#imgProduct").height();
      largeur = $("#imgProduct").width();
	  $(".card-img-top").css("max-height", hauteur);
	  $(".card-img-top").css("max-width", largeur);
    // Quantité choisie
      $("#popUpDevis").append(jQuantite.clone(true));
      // prix correspondant
    // tabelau prix copie
      $("#popUpDevis").append(jclonePrix); 
      $("#popUpDevis").append("<br><br>"); 
    // label tab options
      $("#popUpDevis").append(jLabel.clone(true)); 
    // Tableau options copie
      $("#popUpDevis").append(jcloneOption);
      $("#popUpDevis").append($('<div id="indic">Appuyez sur ENTREE dès que vous saisissez une quantité au clavier</div>'));
    // pour chaque option on ajoute une checkbox pour l'inclure ou pas ds le prix
      $("#popUpDevis #options tr").each(function(){
        $(this).prepend(jCheckBox.clone(true)); 
      });
      listerDimensions();
      listerCouleurs();

      $("#popUpDevis").dialog({
         modal: true, // permet de rendre le reste de la page inaccesible tant que la pop up est ouverte
         height: 800,
         width: 800,
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
    });
  
	// T1

function genInfos() {
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Produit","idProduit":produit},
    //data:{"action":"Prix","idProduit":produit},
    //data:{"action":"Options","idProduit":produit},
    type : "GET",
    success: function(oRep){
        var couleurFond = tab[(oRep[0].refcategories)-1];
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

          oRep.sort(function(a,b){ // on trie le tabeleau des paragraphes en fonction des ordres 
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
        
        $("#popUpDevis").append($('<div id="titleDim">Dimensions :</div>'));
        $("#popUpDevis").append($('<div id="dimFond"></div>'));
        
        for (var i = 0; i < oRep.length; i++) {
        
             $("#dimFond").append($('<div id="dim"></div>').html(oRep[i].nom+' = '));
             $("#dim").attr("id", "dim"+(i+1));
             $("#dim"+(i+1)).css("margin-right", "100px");
             if (i != 0)
             	$("#dim"+(i+1)).css("margin-top", "10px");
             	
             $("#dim"+(i+1)).append($('<input type="number" id="choixDim"/>'));
             $("#choixDim").attr("id", "choixDim"+(i+1)); 
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
    	}
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
             $("#choixCol"+(i+1)).attr("value", "choixCol"+(i+1));
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
	var prix;
	$("#popUpDevis").append($('<div id="titlePrix">PRIX TOTAL HT :</div>'));
	// TODO: faire le calcul du prix ici et l'afficher, mis à jour à chaque changement
			
		// prix = 
		$("#titlePrix").append("€");
	
	
	// TODO: afficher un menu déroulant de tous les devis pour en sélectionner un
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

