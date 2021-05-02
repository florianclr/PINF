<?php




if (!valider("connecte","SESSION")){
  header("Location:index.php?view=connexion");
  die("");
}
$idUser = valider("idUser","SESSION"); 
$admin = valider("isAdmin","SESSION"); 
$idDevis = valider("idDevis");
$pseudo = valider("pseudo","SESSION");
?>

    <link href="css/devis2.css" rel="stylesheet"> 
    <link href="jquery-ui/jquery-ui.css" rel="stylesheet" />
    <link href="jquery-ui/jquery-ui.structure.css" rel="stylesheet" />
    <link href="jquery-ui/jquery-ui.theme.css" rel="stylesheet" />

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="jquery-ui/jquery-ui.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript">
    // SOURCE : 
 	//https://freefrontend.com/css-tables/
 	// https://codepen.io/wortmann/pen/BNNZLb
 	// https://codepen.io/nikhil8krishnan/pen/WvYPvv

    //-------- VAR GLOBALE ----- //
 	var admin ="<?php echo $admin; ?>";
 	var idUser = "<?php echo $idUser; ?>";
 	var idDevis = "<?php echo $idDevis; ?>";
	var pseudo = "<?php echo $pseudo; ?>"; // TODO : sert plus à rien ??
 	var prixTotal=0;
 	var tab ;
  var categorie, flag = 0;

 	//----- MODELE JQUERY -----//

 	var jTd =$("<div class='st_column _rank'></div>");
	var jTdSmall =$("<div class='st_column _btnDevis'></div>");
 	var jTr =$("<tr></tr>");

  var jButtonChgtDate=$('<input id="mailDate" type="button" value="Informer le propriétaire du changement de date de livraison"></input>').click(function(){
    var date = $("#datepicker").val();
    console.log(date); 
    $(this).remove(); 
    //$("#source").prependTo("#destination");
  });

// TODO : récupérer tout les etats possbiles  depuis la bdd
 	var jSelectEtat=$('<select name="etat" id="etat">' +
    '<option value="EN_CRÉATION">EN_CRÉATION</option>'+
    '<option value="DEMANDE_COMMANDE">DEMANDE_COMMANDE</option>'+
    '<option value="COMMANDE_VALIDÉE">COMMANDE_VALIDÉE</option>'+
    '<option value="EN_FABRICATION">EN_FABRICATION</option>'+
    '<option value="LIVRÉ">LIVRÉ</option>'+
    '<option value="ARCHIVÉ">ARCHIVÉ</option>'+
    '</select>"').change(function(){
        console.log("aaaaa");
        var newEtat = $(this).val(); 
        var select = $(this); 
        var date = $("#datePicker").val(); 
        var oldEtat = $("#etat").data("current");
        console.log("old=>"+oldEtat);
    
        if( (newEtat == 'LIVRÉ' || newEtat == 'EN_FABRICATION') &&  date==null){
          alert("Veuillez sélectionner une date de livraison avant de changer l'état.");
          $(this).val($("#etat").data("current"));
          return; 
        }
        if (newEtat == 'COMMANDE_VALIDÉE'){
          $("#dateLivraison").replaceWith(jDate.clone(true));
          datePicker();
          $("#datepicker").val("");
        }

        if ( newEtat=="EN_CRÉATION" && (oldEtat != "EN_CRÉATION" || oldEtat != "DEMANDE_COMMANDE") ){
          alert("Impossible de revenir dans cet état !");
          return; 
        }

        if ( newEtat =="LIVRÉ" && (oldEtat == "EN_CRÉATION" || oldEtat =="DEMANDE_COMMANDE")){
          alert("Impossible de livrer le devis car il doit d'abord être passé en commande.");
          return; 
        }

        if (oldEtat == "EN_FABRICATION" && newEtat =="COMMANDE_VALIDÉE"){
          console.log("GOOD");
          $("#datepicker").replaceWith($('<p id="datepicker" text-align="left"></p>'));
          annulerDevis(idDevis); 
        }

        $.ajax({
        url: "libs/dataBdd.php?action=MajEtat&idDevis="+idDevis+"&etat="+newEtat,
        type : "PUT",
        success: function(oRep){
            console.log(oRep);
            console.log("success");
            var table = $("h2:contains('" + newEtat+"')").parent().parent();
            var copie = $("#devis"+idDevis); 
            copie.appendTo(table);
            console.log(table); 
            select.data("current",newEtat); 
        },
        error : function(jqXHR, textStatus) {
          console.log("erreur");
        },
        dataType: "json"
    });
      }); 

 	//EN_CRÉATION
 	//DEMANDE_COMMANDE
 	//COMMANDE_VALIDÉE
 	//EN_FABRICATION
 	//LIVRÉ
 	//ARCHIVÉ

  var jTextarea = $('<textarea id="commentaire" name="commentaire" rows="4" cols="50" id="coms"></textarea>');

  var jButtonCom = $('<input type="button" value="Envoyer un mail (état + commentaire)" id="btnCom">').click(function(){
    var commentaire = $("#commentaire").val(); 
    var etat = $("#etat option:selected").val(); 
    var numDevis=$("#numDevis").html();
    var nomProjet=$("#nomProjet").html();
    console.log(commentaire);
    
    $.ajax({
        url: "libs/dataBdd.php?action=MajCommentaire&idDevis="+idDevis+"&commentaire="+commentaire,
        type : "PUT",
        success: function(oRep){
          console.log(oRep);
        },
        error : function(jqXHR, textStatus) {
          console.log("erreur");
        },
        dataType: "json"
    });
    
    var subject="Information sur votre devis ["+numDevis+"] ["+nomProjet+"]";
    if(commentaire=="Aucun commentaire")
      var body="Votre devis est pass&eacute; dans l'&eacute;tat : "+etat;
    else 
    	var body="Votre devis est pass&eacute; dans l'&eacute;tat : "+etat+". L'administrateur a ajout&eacute; le commentaire suivant : "+commentaire;
    mailClient(idDevis, subject, body);
    
  });

  var jDate = $('<input autocomplete="off" type="text" id="datepicker">');

 	var jButton = $('<input type="button" id="commander" value="Passer la commande"/>').click(function () {
  		var btn = $(this); 
  		$.ajax({
		    url: "libs/dataBdd.php?action=Commander&idDevis="+idDevis+"&idUser="+idUser,
		    type : "PUT",
		    success: function(oRep){
		        console.log(oRep);
            $(btn).remove(); 
            $("#etat").html("DEMANDE_COMMANDE");
			$(".imgSuppArtDevis").remove();
			getMail();
		    },
		    error : function(jqXHR, textStatus) {
		      console.log("erreur");
		    },
		    dataType: "json"
		});

  });

var jTitre =$('<div class="card h-100" id="titleProduct"><h4 class="card-title"></h4></div>');

var jTable=$('<table id="FerrureDevis"><tr id="lig0"><td class="tabDevis"></td><td>Ferrure</td><td>Quantité</td><td>Dimension(s)</td><td id="option">Option(s)</td><td>Couleur</td><td>Prix</td></tr></table>');

var jLignePrixTot = $("<tr id='ligPrixTot'><td>Prix total</td><td></td><td></td><td></td><td></td><td></td><td id='prixTot'></td></tr>"); 

var jImg=$('<img  class="imgSuppArtDevis" src="./ressources/moins.png"/>').click(function(){
	
	var prix=parseInt($(this).parent().parent().find('td').eq(6).html());
  	var ancienPrix=parseInt($("#prixTot").html());


  $.ajax({
          url: "libs/dataBdd.php?action=FerrureDevis&idFerrureDevis="+$(this).prop("id")+"&idUser="+idUser+"&idDevis="+idDevis,
          type : "DELETE",
          success: function(oRep){
            console.log(oRep);
            var nvPrix=ancienPrix-prix;
            console.log(nvPrix);
            $("#prixTot").html(nvPrix+"€");
          },
          error : function(jqXHR, textStatus) {
            console.log("erreur");
          },
          dataType: "json"
          });

	$(this).parent().parent().hide('slow', function() { 
              $(this).remove();
    });
});

var jHeaderTab = $('<header class="st_table_header">' +
      '<h2 class="title_tab"></h2>' +
      '<div class="st_row">' +
        '<div class="st_column _rank">Numéro du devis</div>' +
        '<div class="st_column _name">Nom du projet</div>' +
        '<div class="st_column _surname">Nom du client</div>' +
        '<div class="st_column _year">Date de création</div>' +
        '<div class="st_column _section">État</div>' +
        '<div class="st_column _dateLivraison">Date de livraison</div>' +
        '<div class="st_column _btnDevis"></div>' +
      '</div>' +
      '</header>');

var jInfosChrageAff = $('<div class="st_column _dateLivraison">Propriétaire du devis</div>');

var jPopupDevis=$('<div id="newD" title="Création d\'un devis"><div id="nomCli">Nom du client : </div>').append('<input type="text" id="nomClient"/>').append('<div id="numDev">Numéro du devis :</div>').append('<input type="text" id="numD">').append('<div id="nomPro">Nom du projet :</div>').append('<input type="text" id="nomP"/>');

var jAddDevis=$('<div class="buttonsCenter"><input type="button" id="addD" value="Créer un devis"/></div>').click(function(){
    $('body').append(jPopupDevis.clone(true));
    $("#newD").dialog({
      modal: true, 
      height: 380,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "Créer": function(){

        $.ajax({
        url: "libs/dataBdd.php",
        data:{"action":"CreerDevis","nomClient":$('#nomClient').val(),"numD":$('#numD').val(),"nomP":$('#nomP').val(),"refCa":idUser},
        type : "POST",
        success:function (oRep){
          console.log(oRep);
          $("body").prepend("<div id='ajoutDevOK'>Le devis a bien été créé</div>");
           $("#newD").dialog( "close" ); // ferme la pop up
           $("#newC").remove(); // supprime la pop up 
           $(".st_viewport").empty();
			generateTableUser(idUser);	// MAJ tableau

        },// fin succes
        error : function(jqXHR, textStatus) {
          console.log("erreur");
        },

        dataType: "json"
      });// fin requête ajax


        },
        "Annuler": function() {
        $(this).dialog( "close" ); // ferme la pop up 
        $(this).remove(); // supprime la pop up
        },
      },
      close: function() { // lorsque on appui sur la croix pour fermer la pop up 
      console.log("close!!!!");
      $(this).remove(); // supprime la pop up 
      }
    });

  });

var jDisplayArchive=$('<div class="buttonsCenter"><label for="archive">Afficher les devis archivés</label></div>') 
                  .append($('<input type="checkbox" name="archive" id="dispArchive"/>').change(function(){
                    $("#tabDevis").empty();
                    generateTableUser(idUser);
                    console.log("click");
}));

 	// ------ GENERATION TABELAU NON ADMIN ---//

 	function generateTableUser(idUser){
 		var jligne ; 
 		var dateLivraison;
 		var etat ; 
 		var nbTab = 0; 
 		var tabAct ;
 		var archive = 0; 

		console.log("ARCHIVÉ ou pas");
		if ($("#dispArchive").prop("checked") == true)
		  archive=1; 
      
 		$.ajax({
              url: "libs/dataBdd.php?action=DevisUser&idUser="+idUser+"&archive="+archive,
      				//data:{"action":"AllDevis","idUser":idUser},
      				type : "GET",
                    success:function (oRep){ 
                    console.log(oRep);
                    etat = "" ;

                    var jlien=$("<a class='button' href='#'></a>").click(function(e){
                      console.log("test!!!");
                      idDevis = $(this).prop("id");console.log(idDevis);
                      // change l'url 
                      var lien = "index.php?view=devis&idDevis=" + idDevis ; 
                      var stateObj = { foo: "bar" };
                      history.pushState(stateObj, "InfosDevis", lien);

                      genDetailsDevis(idDevis);
                      e.preventDefault();
                      $("#tabDevis").hide();
                      $('.test, html, body').toggleClass('open'); // indique quelle classe cacher/montrer
                    });
                    

              	// INSERTION DEVIS DANS LE TABLEAU
                    for (var i = 0; i < oRep.length; i++) { 
                    	if(oRep[i].dateLivraison == null)
                    		dateLivraison = "indéfini";
                    	else 
                    		dateLivraison = oRep[i].dateLivraison;

                    	if(oRep[i].etat != etat){
                    		etat = oRep[i].etat ;
                    		tabAct = "tab"+ nbTab;
                    		// $(".st_viewport").append($('<div class="st_wrap_table" data-table_id="'+ nbTab +'" id="' + tabAct + '">').clone(true));
                    		$("#tabDevis").append($('<div class="st_wrap_table" data-table_id="'+ oRep[i].etat +'" id="' + tabAct + '">').clone(true));
                    		$("#" + tabAct).append(jHeaderTab.clone(true));
                    		$("#" + tabAct +" .title_tab").html(etat); 
                    		if(admin == 1 || admin == 2){// ajout d'une colone pour afficher le créateur du devis 
                    			$("#" + tabAct + " header .st_row div.st_column._year").before(jInfosChrageAff.clone(true)); 
                    			//.append(jInfosChrageAff).clone(true)
                    		}
                    		nbTab++ ; 
                    		console.log("test");
                    	}

                    	jligne = $('<div class="st_row"></div>').attr("id","devis"+oRep[i].id).append(jTd.clone(true).append(oRep[i].numeroDevis))
                    			.append(jTd.clone(true).append(oRep[i].nomProjet))
                    			.append(jTd.clone(true).append(oRep[i].nomClient))
                
                    			//SI ADMIN INSERER NOM CHARGÉ AFFAIRES 
                    	if(admin ==1 || admin == 2)
                    		jligne = jligne.append(jTd.clone(true).addClass("nomCa").attr("id",oRep[i].refCA)); 


                    	jligne = jligne.append(jTd.clone(true).append(oRep[i].dateCreation))
                    			.append(jTd.clone(true).append(oRep[i].etat))
                    			.append(jTd.clone(true).append(dateLivraison))
                    			.append(jTdSmall.clone(true).append(jlien.html("Voir le devis").attr("id",oRep[i].id).clone(true)));	

                    	var ref = $("#" + tabAct).append(jligne.clone(true)).clone(true);

                    }// fin for
                // AJout des noms des chargés 
                if(admin ==1 || admin == 2){
                	 setPropDevis(); 
                }
                  // CAS OU UN DEVIS EST DÉJÀ CHARGÉ 
                    if(idDevis != ""){
                      var nbH = $(".st_table_header");
                      console.log(nbH);
                      $("#tabDevis").toggle();
                      genDetailsDevis(idDevis);
                      $('.test, html, body').toggleClass('open');
                    }//fin if
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });
 	}

 	function setPropDevis(){
 		$.ajax({
				url: "libs/dataBdd.php",
				data:{"action":"nomUsers","idUser":idUser},
				type : "GET",
				success: function(oRep){
				console.log(oRep);
				$(".nomCa").each(function(){
					console.log($(this)); 
					var refCa = $(this).prop("id");
					for (var i = 0; i < oRep.length; i++) {
						if(oRep[i].id == refCa)
							$(this).append(oRep[i].nom + oRep[i].prenom); 
					}	
				}) 
				},
				error : function(jqXHR, textStatus) {
				console.log("erreur");
				},
				dataType: "json"
			});
 	}

 	
 	// ------ GENERATION DES INFOS DU DEVIS ---//

 	function genDetailsDevis(idDevis){ 
 		$.ajax({
              url: "libs/dataBdd.php",
    		  data:{"action":"Devis","idDevis":idDevis,"idUser":idUser},
      				type : "GET",

                    success:function (oRep){ 
                    console.log(oRep);
                    $("#nomProjet").html(oRep[0].nomProjet);
                    $("#client").html(oRep[0].nomClient);
                    $("#numDevis").html(oRep[0].numeroDevis);
                    $("#dateCreation").html(oRep[0].dateCreation);

                    console.log(oRep[0].dateLivraison); 

                    if(admin==0 || (oRep[0].etat=="ARCHIVÉ" || oRep[0].etat=="EN_CRÉATION" || oRep[0].etat=="DEMANDE_COMMANDE")){
                      $("#datepicker").replaceWith($('<p id="datepicker" text-align="left"></p>').html(oRep[0].dateLivraison));
                    }

					if( (oRep[0].etat=="COMMANDE_VALIDÉE" || oRep[0].etat=="EN_FABRICATION") && (admin ==1 || admin ==2)) {
                      if( oRep[0].dateLivraison != null  ){ 
                        $("#datepicker").replaceWith(jDate.clone(true));
                        datePicker();
                         console.log("TESTDATE2");
                         console.log(oRep[0].dateLivraison); 
                         var date = oRep[0].dateLivraison ; 
                         var tabDate = date.split('-' );
                         console.log(tabDate); 
                         $("#datepicker").datepicker("setDate", new Date(tabDate[0],tabDate[1]-1,tabDate[2]));
                      }
                      else if( oRep[0].dateLivraison == null ){ 
                        console.log("TESTDATE3");
                        $("#dateLivraison").replaceWith(jDate.clone(true));
                        datePicker();
                      	$("#datepicker").val("");
                      }
                    }

                    if(admin == 1 || admin == 2){
                      console.log(oRep[0].etat);
                      if(oRep[0].etat == "ARCHIVÉ"){
                        $("#etat").empty();
                        $("#etat").prepend("ARCHIVÉ");
                      }
                      else{
                        $("#etat").empty();
                      $("#etat").prepend(jSelectEtat.clone(true));
                      $('#etat select option[value="' + oRep[0].etat +'"]').prop('selected', true);
                      console.log("DATAAAA");
                      $("#etat").data("current",oRep[0].etat);
                      }
                    }
                    else {
                    	$("#etat").empty();
                      	$("#etat").html(oRep[0].etat);
                    }

                    if(oRep[0].commentaire == "" || oRep[0].commentaire == null)
                    	$("#coms").html("Aucun commentaire");
                   	else
                   		$("#coms").html(oRep[0].commentaire);

                    if(admin ==1 || admin == 2){
                      var com = $("#coms").html();
                      $("#coms").replaceWith(jTextarea.val(com));
                      $("#btnCom").remove(); 
                      $("#com").append(jButtonCom.clone(true));
                    }

                    if(admin==0 && oRep[0].etat=="EN_CRÉATION"){
                      $("#articles").after(jButton); 
                    }
                    
                    if( (admin == 0 && (oRep[0].etat=="EN_CRÉATION" || oRep[0].etat=="DEMANDE_COMMANDE" ) ) || admin ==2 || admin == 1 )
                      $("#supp").show();

                   	prixTotal = oRep[0].PrixTotal ; 
                   	genFerruresDevis(idDevis);
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });

 	}

 	// ------ GENERATION DES FERRURES DU DEVIS ---//

 	function genFerruresDevis(idDevis){

 		$("#articles").append(jTable.clone(true));
 		$.ajax({
              url: "libs/dataBdd.php",
    		  data:{"action":"listerFerruresDevis","idDevis":idDevis,"idUser":idUser},
      				type : "GET",

                    success:function (oRep){ 
                    console.log(oRep);
 					for (var i =0; i < oRep.length; i++) {


			        	$("#lig0")
			        	.after($('<tr id="lig'+(i+1)+'""><td class="tabDevis" id="img"></td><td class="tabDevis" id="nomF'+i+'"></td><td class="tabDevis" id="qte'+i+'"></td></td><td id="dim'+i+'""><b>dim1 : </b>'+oRep[i].a+'</br><b>dim2 : </b>'+oRep[i].b+'</br><b>dim3 : </b>'+oRep[i].c+'</td><td>'+oRep[i].couleur+'</td><td class="tabDevis"id="prix'+i+'""></tr>'));

			        	console.log($("#etat").text());
			        	if($("#etat").text() =="EN_CRÉATION"  ||( admin==1 || admin==2)){
        					$("#img").prepend(jImg.clone(true).attr("id",oRep[i].id));
        				}

			        	$("#qte"+i).html(oRep[i].quantite);
			        	$("#prix"+i).html(oRep[i].prix + "€");
			        	remplirTab(i,oRep);

                 insererOption(oRep[i].id,i);
			        	//prixTotal+=parseInt(oRep[i].prix);

					}//fin for
					$("#FerrureDevis tbody").append(jLignePrixTot.clone(true)); 
					$("#prixTot").append(prixTotal + "€");
                  
                    },// fin succes
                    error : function(jqXHR, textStatus) {
                    console.log("erreur");  
                    },
                    dataType: "json"
                 });


 		//if($("#etat").text()=="EN_CRÉATION")$("#supp").hide();
 	}


function insererOption(id,i) {

  $.ajax({
                  url: "libs/dataBdd.php",
                  data:{"action":"OptionsFerrure","refFerrureDevis":id},
                  type : "GET",
                  success: function(oRep){
                    console.log(oRep);

                    if(oRep.length!=0){
                      for (var j =0; j < oRep.length; j++){

                        if($("#opt"+i).length >0){

                          var option="- "+oRep[j].nom +"<b> (x"+oRep[j].quantité+")</b></br>";
                          $("#opt"+i).append(option);
                          console.log("exist");
                        }
                        else {
                        // création de la case option si elle n'existe pas
                        $("#dim"+i).after('<td id="opt'+i+'">- '+oRep[j].nom +"<b> (x"+oRep[j].quantité+")</b></br></td>"); 
                      }


                    }
                  }
                  else {

                    $("#dim"+i).after('<td id="opt"></td>'); 

                  }


                  },
                  error : function(jqXHR, textStatus) {
                    console.log("erreur");
                  },
                  dataType: "json"
                });
}


 	function remplirTab(i,oRep) {

	$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"Produit","idProduit" :oRep[i].refFerrures},
			    type : "GET",
			    success: function(oRep){
            console.log("oRep");
            console.log(oRep);

            var lien = "index.php?view=article&produit=" + oRep[0].id + "&categorie=" + oRep[0].nomCategorie ;
            var jlien2 = $("<a></a>").html(oRep[0].titre).attr("href",lien); 
            $("#nomF"+i).append(jlien2.clone(true));
           
			    },
			    error : function(jqXHR, textStatus) {
			      	console.log("erreur");
			    },
			    dataType: "json"
			    });
	}



// ---- FERMETURE ONGLET DEVIS ---//
     $(document).ready(function(){
         $('.close').on('click', function(e) {
      idDevis=""; 
             // change l'url 
             var stateObj = { foo: "bar" };
              history.pushState(stateObj, "Devis", "index.php?view=devis");
             $("#articles").empty();
              e.preventDefault();
              $("#tabDevis").toggle();
              $('.test, html, body, .st_wrap_table').toggleClass('open'); // .detail indique quel classe ouvrir
        });
        datePicker();
        });

function datePicker() {

  $( "#datepicker" ).datepicker({
      showOtherMonths: true,
      selectOtherMonths: true
    });
     $( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd");

      $( "#datepicker" ).change(function(){
        var newDate = $(this).val(); 
        var datepicker = $(this); 
        $.ajax({
        url: "libs/dataBdd.php?action=majDateLivraison&idDevis="+idDevis+"&date="+newDate,
        type : "PUT",
        success: function(oRep){
            console.log(oRep);
            if( $("#mailDate").index() == -1){ 
              $(datepicker).after(jButtonChgtDate.clone(true));
              $("#dateLivraison").after("</br>"); 
              
              $('#etat option[value="EN_FABRICATION"]').prop('selected', true); 
              $("#etat").data("current","EN_FABRICATION");
              alert("Devis passé en fabrication !");
          }
        },
        error : function(jqXHR, textStatus) {
          console.log("erreur");
        },
        dataType: "json"
    });
      })

}

function mailClient(idDevis, subject, body) {

      console.log("Envoi d'un mail au client du devis " + idDevis);

      $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"MailClient","idDevis":idDevis},
              type : "GET",
              success:function (mailClient) {

                console.log(mailClient);

                var expediteur = "decima-ne-pas-repondre";
                var email = "no-reply@decima.fr";

                $.ajax({
                    url: 'PHPMailer/mail.php',
                    method: 'POST',
                    dataType: 'json',

                    data: {
                      name: "decima-ne-pas-repondre",
                      email: email,
                      subject: subject,
                      body: body,
                      mailD: mailClient
                    },

                    success: function(response) {
                    	if (flag == 1)
                    		SupprimerDevis();
                    },

                    error: function(response) { 
                      console.log(response);
                  }
                
                  });
                },

                error: function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });

  }

function getMail() {

    $.ajax({
                url: "libs/dataBdd.php",
                data:{"action":"Mail"},
                type : "GET",
                success:function (oRep){
                 console.log(oRep);
                 sendMail(oRep);

             },
            error : function(jqXHR, textStatus)
            {
                console.log("erreur");

            },
            dataType: "json"
            });

}

function sendMail(mailDest) {


    var expediteur = "decima-ne-pas-repondre";
    var email = "no-reply@decima.fr";
    var subject = "Demande de commande du devis "+$("#numDevis").text();
    var body = "Veuillez valider ou refuser le devis n&ordm;"+$("#numDevis").text()+" du projet "+$("#nomProjet").text();

    $.ajax({
      url: 'PHPMailer/mail.php',
      method: 'POST',
      dataType: 'json',

      data: {
        name: "decima-ne-pas-repondre",
        email: email,
        subject: subject,
        body: body,
        mailD: mailDest, 
      },

      success: function(response) {
          console.log(response);
      },

      error: function(response) { 
        console.log(response);
      }
    });


}
 	// --- CHARGEMENT PAGE -- //
 	$(document).ready(function(){
 		console.log(idUser); 
 		console.log(idDevis);
 		generateTableUser(idUser);
		$(".st_viewport").after(jAddDevis.clone(true));
		$(".st_viewport").before(jDisplayArchive.clone(true));
 	});
 	
function SupprimerDevisInt() {
	var ans = confirm("Confirmer la supression du devis ? Pour prévenir de la suppression, un mail sera envoyé au créateur du devis.");
    if (ans){ 
		flag = 1;
		
		// envoi du mail
		var numDevis=$("#numDevis").html();
		var nomProjet=$("#nomProjet").html();
		var subject = "Suppression du devis du projet " + nomProjet;
		var body = "Votre devis n&ordm;" + numDevis + " du projet "+ nomProjet +" a &eacute;t&eacute; supprim&eacute; par vous-m&eacute;me ou un administrateur du site.";
		mailClient(idDevis, subject, body);
	}
}

function SupprimerDevis() {

   
      $.ajax({
      url: "libs/dataBdd.php?action=SuppDevis&idDevis="+idDevis+"&idUser="+idUser,
            type : "DELETE",
            success: function(oRep){
             console.log(oRep);
              $("#articles").empty();
                $(".st_table_header").toggle();
                $('.test, html, body, .st_wrap_table').toggleClass('open'); // .detail indique quel classe ouvrir
                $('#'+idDevis).parent().parent().remove();
                var stateObj = { foo: "bar" };
                history.pushState(stateObj, "Devis", "index.php?view=devis");
                flag = 0;
            },
            error : function(jqXHR, textStatus) {
                console.log("erreur");
            },
            dataType: "json"
            });
    
}


function annulerDevis(idDevis) {

      console.log("Annulation du devis " + idDevis);

      $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"AnnulerDevis","idDevis":idDevis},
              type : "GET",
              success:function (oRep){
                console.log("devis annulé");
                },

                error: function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });
  }


    </script>

  <body>
  <main class="st_viewport">
  <div id="tabDevis"></div>
  
  <div class='detail test'>
    <div class='detail-container'>
      <dl>
        <dt>
          Projet / Chantier
        </dt>
        <dd id="nomProjet">
          
        </dd>
        <dt>
          Client
        </dt>
        <dd id="client">
          
        </dd>
        <dt>
          Numéro de devis
        </dt>
        <dd id="numDevis">
          
        </dd>
        <dt>
          Date de création
        </dt>
        <dd id="dateCreation">
      
        </dd>
        <dt>
          Date de livraison
        </dt>
        <dd id="dateLivraison">
          <input type="text" id="datepicker">     
        </dd>
        <dt>
          État
        </dt>
        <dd id="etat">
          
        </dd>
        <dt>
          Mail 
        </dt>
        <dd id="com">
         <p id=coms></p>
        </dd>

        <dt id="TEST">
          Articles du devis
        </dt>
        <dd id="articles">
         
        </dd>
      </dl>

    </div>
    <div class='detail-nav'>
      <button class='close' id="close">
        Fermer
      </button>
       <input type="button" id="supp" class="supp" value="Supprimer" onclick="SupprimerDevisInt();" />
    </div>
  </div>

  </main>
</body>

 

