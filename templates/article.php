
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

  var jQuantite = $('<div id="quantite">Quantité = <input type="number" id="qteFerrure" value="1" min="1"/></div>');
  
  var jQuantiteOpt = $('<input type="number" id="qteOpt" value="1" min="1"/>');


  var jCheckBox=$('<td></td>').append($('<input id="checkOpt" type="checkbox"/>').click(function() {
              if ($(this).prop("checked") == true) {
                $(this).parent().append(jQuantiteOpt.clone(true));
                qte = $("#qteFerrure").val();
              $("#qteOpt").attr('max', qte);
              }
              else if ($(this).prop("checked") == false)
                var inputSupr = $(this).parent().find('input[type="number"]'); // on cherche dans le parent un input number 
                $(inputSupr).remove();
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
    // tabelau prix copie
      $("#popUpDevis").append(jclonePrix); 
      $("#popUpDevis").append("<br><br>"); 
    // label tab options
      $("#popUpDevis").append(jLabel.clone(true)); 
    // Tableau options copie
      $("#popUpDevis").append(jcloneOption);
    // pour chaque option on ajoute une checkbox pour l'inclure ou pas ds le prix
      $("#popUpDevis #options tr").each(function(){
        $(this).prepend(jCheckBox.clone(true)); 
      });

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
  
  // CHARGEMENT PAGE
$(document).ready(function(){
    genInfos();
    //genTabPrix();
    //genTabOption();
  });

	function createPopUp(){
	 
}

</script>
<body>

   <br/><br/>
    <div class="container">    
      <div class="product"></div> <!-- TODO:MODIF NOM -->
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

