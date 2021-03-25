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

    <link href="css/devis.css" rel="stylesheet"> 
    <link href="jquery-ui/jquery-ui.css" rel="stylesheet" />
    <link href="jquery-ui/jquery-ui.structure.css" rel="stylesheet" />
    <link href="jquery-ui/jquery-ui.theme.css" rel="stylesheet" />

  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="jquery-ui/jquery-ui.js"></script>

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
	var pseudo = "<?php echo $pseudo; ?>";
 	var prixTotal=0;
 	var tab ;

 	//----- MODELE JQUERY -----//

 	var jTd =$("<div class='st_column _rank'></div>");
	var jTdSmall =$("<div class='st_column _btnDevis'></div>");
 	var jTr =$("<tr></tr>");

  var jButtonChgtDate=$('<input id="mailDate" type="button" value="Informer le propriétaire du changement de date de livraison"></input>').click(function(){
    // TODO: mail !!!
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
    '<option value="FABRIQUÉ">FABRIQUÉ</option>'+
    '<option value="LIVRÉ">LIVRÉ</option>'+
    '<option value="ARCHIVÉ">ARCHIVÉ</option>'+
    '</select>"').change(function(){
        var newEtat = $(this).val(); 
        var select = $(this)
        $.ajax({
        url: "libs/dataBdd.php?action=MajEtat&idDevis="+idDevis+"&etat="+newEtat,
        type : "PUT",
        success: function(oRep){
            console.log(oRep);
            console.log($("#mailEtat").index())
            if( $("#mailEtat").index() == -1){ 
              $(select).after(jButtonChgtEtat.clone(true));
              $(select).after("</br>");  
          }
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
 	//EN_COURS_DE_FABRICATION
 	//RÉALISE
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
    
    var subject="information sur votre devis [ "+numDevis+" ] [ "+nomProjet+" ]";
    if(commentaire=="Aucun commentaire")
      var body="Votre devis est en "+etat;
    else 
    	var body="Votre devis est en "+etat+" commentaire : "+commentaire;
    mailClient(idDevis, subject, body);
    
  });

  var jDate = $('<dd> <input type="text" id="datepicker"></dd>');

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

var jTable=$('<table id="FerrureDevis"><tr id="lig0"><td class="tabDevis"></td><td>Ferrure</td><td>Quantité</td><td>Prix</td></tr></table>');

var jLignePrixTot = $("<tr id='ligPrixTot'><td>Prix total</td><td></td><td></td><td id='prixTot'></td></tr>"); 

var jImg=$('<img  class="imgSuppArtDevis" src="./ressources/moins.png"/>').click(function(){
	
	var prix=parseInt($(this).parent().parent().find('td').eq(3).html());
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

 	// ------ GENERATION TABELAU NON ADMIN ---//

 	function generateTableUser(idUser){
 		var jligne ; 
 		var dateLivraison;
 		var etat ; 
 		var nbTab = 0; 
 		var tabAct ;
 		$.ajax({
              url: "libs/dataBdd.php?action=DevisUser&idUser="+idUser,
      				//data:{"action":"AllDevis","idUser":idUser},
      				type : "GET",
                    success:function (oRep){ 
                    console.log(oRep);
                    etat = "" ;

              // MYSTÈRE PQ QUAND ON LE MET EN VAR GLOBALE ÇA MARCHE PAS !!! //
                    var jlien=$("<a class='button' href='#'></a>").click(function(e){
                      console.log("test!!!");
                      idDevis = $(this).prop("id");console.log(idDevis);
                      // change l'url 
                      var lien = "index.php?view=devis&idDevis=" + idDevis ; 
                      var stateObj = { foo: "bar" };
                      history.pushState(stateObj, "InfosDevis", lien);

                      genDetailsDevis(idDevis);
                      e.preventDefault();
                      $(".st_table_header").toggle();
                      $('.test, html, body').toggleClass('open'); // .detail indique quel classe ouvrir
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
                    		$(".st_viewport").append($('<div class="st_wrap_table" data-table_id="'+ oRep[i].etat +'" id="' + tabAct + '">').clone(true));
                    		$("#" + tabAct).append(jHeaderTab.clone(true));
                    		$("#" + tabAct +" .title_tab").html(etat); 
                    		if(admin == 1 || admin == 2){// ajout d'une colone pour afficher le créateur du devis 
                    			$("#" + tabAct + " header .st_row div.st_column._year").before(jInfosChrageAff.clone(true)); 
                    			//.append(jInfosChrageAff).clone(true)
                    		}
                    		nbTab++ ; 
                    		console.log("test");
                    	}

                    	jligne = $('<div class="st_row"></div>').append(jTd.clone(true).append(oRep[i].numeroDevis))
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
                      $(".st_table_header").toggle();
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

                    console.log("TESTDATE");
                       console.log(oRep[0].dateLivraison); 

                    if(admin==0 ||(admin==1 || admin==2 && oRep[0].etat=="ARCHIVÉ")){
                      $("#datepicker").replaceWith($('<p id="dateLivraison"></p>').html(oRep[0].dateLivraison));
                    }

					if(oRep[0].etat=="COMMANDE_VALIDÉE" || oRep[0].etat=="EN_FABRICATION") {
                      if( oRep[0].dateLivraison != null  ){ 
                        $("#dateLivraison").replaceWith(jDate.clone(true));
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
                      $("#etat").prepend(jSelectEtat);
                      $('#etat select option[value="' + oRep[0].etat +'"]').prop('selected', true);
                      }
                    }
                    else 
                      $("#etat").html(oRep[0].etat);

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
			        	.after($('<tr id="lig'+(i+1)+'""><td class="tabDevis" id="img"></td><td class="tabDevis" id="nomF'+i+'"></td><td class="tabDevis" id="qte'+i+'"></td><td class="tabDevis"id="prix'+i+'""></td></tr>'));

        				$("#img").prepend(jImg.clone(true).attr("id",oRep[i].id));

			        	$("#qte"+i).html(oRep[i].quantite);
			        	$("#prix"+i).html(oRep[i].prix + "€");
			        	remplirTab(i,oRep);
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
 	}

 	function remplirTab(i,oRep) {

	$.ajax({
		url: "libs/dataBdd.php",
		data:{"action":"Produit","idProduit" :oRep[i].refFerrures},
			    type : "GET",
			    success: function(oRep){
			    	var lien = "index.php?view=article&produit=" + oRep[0].id ; 
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
 			// change l'url 
 			var stateObj = { foo: "bar" };
 		 	history.pushState(stateObj, "Devis", "index.php?view=devis");
 			$("#articles").empty();
  			e.preventDefault();
  			$(".st_table_header").toggle();
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
    var subject = "Demande de commande de "+pseudo;
    var body = "Veuillez valider ou refuser le devis de "+pseudo +" pour le projet "+$("#nomProjet").text()+" pour le client "+$("#client").text();

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
 	})



    </script>

  <body>
  <main class="st_viewport">
  <div class="st_wrap_table" data-table_id="0">

  </div>
  
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
        Close
      </button>
    </div>
  </div>

  </main>
</body>

 

