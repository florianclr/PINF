

<?php
  	$admin = valider("isAdmin","SESSION");  
  	$idUser = valider("idUser","SESSION");
  	$categ = valider("categ");

	if (!valider("connecte","SESSION")) {
  		header("Location:index.php?view=connexion");
  		die("");
	}
?>
<!-- Css galerie -->
<link rel="stylesheet" type="text/css" href="./slick/slick.css">
<link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">

<style type="text/css">
    .slick-prev:before,
    .slick-next:before {
      color: black;
    }

    .slick-prev{
      left:-10px;
    }

    .test{
      max-width:250px;
      max-height:250px; 
    }
    .test2{
      width: 320px ; 
      height : 320px;
    }

</style>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script src="slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">

// -------- MODELES JQUERY --------------//

  var tab = [];

  var jArticle = $('<div class="test2 col-lg-4 col-md-6 mb-4">')
                  .append('<div class="card h-100"><img class="test card-img-top"> <div class="card-body fond">');
      
                    
  var jCatgegorie = $('<div class="regular slick row"></div>');
  
  var jWarning = $('<div>Aucun résultat ne correspond à votre recherche</div>');

  var jtitre =  $('<h2 class="titre"></h2>');

  var jLien = $('<a href="#">');

  var jMenu=$('<a href="#" class="list-group-item">').click(function(){
                $nomCategorie = $(this).html();
                $couleurCategorie = $(this).css("color");
                $(".col-lg-9").empty(); // on vide l'ancien contenu affiché
                $(".col-lg-9").append(jtitre.html($nomCategorie).css("color", $couleurCategorie).clone(true));
                $(".col-lg-9").append(jCatgegorie
                              .attr("id",$nomCategorie)
                              .clone(true));

                var stateObj = { foo: "bar" };
                history.pushState(stateObj, "categorie", "index.php?view=catalogue&categ="+$nomCategorie);
                remplirCatgegorieV2();
  });

  var iconTrash = $('<button class="trashButton">').append('<img src="./ressources/delete.png" class="trash">').click(function(){
                                                      		
                                                      		$('body').append(popupSuppression.clone(true)
                                                      			.data("idFerrure",$(this).data("idFerrure"))); 

                                                      		$("#popupSuppr").dialog({
                                                      			 modal: true, 
                                                      			 height: 265,
      															 width: 400,
                                                      			 buttons: { // on ajoute des boutons à la pop up 
															        "Oui": function(){
															        	console.log($(this).data("idFerrure"));
															        	$.ajax({
                                          url: "libs/dataBdd.php?action=Ferrure&id="+$(this).data("idFerrure"),
                                          type : "DELETE",
                                          success : function(oRep){
                                            console.log("Ferrure supprimée!!");
                                            console.log(oRep);
                                             $("#popupSuppr").dialog( "close" ); // ferme la pop up
                                          },
                                          error : function(oRep){
                                            console.log("ERREUR"); 
                                          },
                                          dataType: "json"
                                        });
                                        
                                        $("#"+$(this).data("idFerrure")).hide('slow', function() { 
                                          $("#"+$(this).data("idFerrure")).remove();
                                        });
															        },
															        "Non": function() {
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

  var iconPencil = $('<button class="trashPencil">').append('<img src="./ressources/pencil.png" class="pencil">').click(function(){
    var idFerrure = $(this).data("idFerrure");
     document.location.href="./index.php?view=creerArticle&idFerrure="+idFerrure;
  });  

  var imgAjout = $('<div class="col-lg-4 col-md-6 mb-4">')
                  .append($('<img class="card-img-top" src="./ressources/plus.jpg">')
                  	.click(function(){
                  		console.log("redirection...");
                      var categ = $(this).parent().parent().prop("id");
                      console.log("categ=>"+categ);
                      document.location.href="./index.php?view=creerArticle&categorie="+categ;
                  	}) // fin function 
                  );// fin append

  var popupSuppression = $('<div id="popupSuppr" title="Confirmer la suppression"><h4 id="warningConfirm">Voulez-vous vraiment supprimer cette ferrure ?</h4><p>Cette action est irréversible</p>');

  var jRecherche=$('<div id="recherche">')
      .append($('<input type="text" id="mot"/>')).keydown(function(contexte){
        //console.log(contexte);
        if(contexte.key == "Enter"){
          console.log($('#mot').val());
            $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"Rechercher","keyword":$('#mot').val()},
              type : "GET",
              success:function (oRep){
              console.log(oRep);
              if (oRep != null) {
            
                $(".col-lg-9").empty();
                $(".col-lg-9").append($('<div class="row"></div>'));
                for (var i = 0; i <oRep.length; i++) {
              
                   if(oRep.length != 0){ 
                                              
                      $(".col-lg-9 .row").append(jArticle.clone(true).attr("id",oRep[i].id));

                      $("#" + oRep[i].id +" .card-img-top").attr('src',"./images/"+oRep[i].image);
                      $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                 .html(oRep[i].titre)
                                                 .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                 .attr("id",oRep[i].id)
                                                 );
                      if(admin == 1 || admin == 2){ 
                        $("#"+ oRep[i].id +" .card-body").append(iconTrash.clone(true)); 
                        $("#"+ oRep[i].id +" .card-body").append(iconPencil.clone(true));
                      }// fin if admin
                                              
                    }// fin if
      
                }//fin for

                if (oRep.length == 0) {
                  console.log("pas trouvé");
                  $(".col-lg-9 .row").append(jWarning.clone(true).attr("id","warning"));
                }
              } // fin oRep != null
            },//fin succes 
              dataType: "json"
            })//fin ajax
          }

      })
      .append($('<input type="button" id="searchButton" value="Rechercher"/>')
        .click(function(){
          console.log($('#mot').val());
            $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"Rechercher","keyword":$('#mot').val()},
              type : "GET",
              success:function (oRep){
                console.log(oRep);
    if (oRep != null) {
            $(".col-lg-9").empty();
            $(".col-lg-9").append($('<div class="row"></div>'));
            for (var i = 0; i <oRep.length; i++) {
              
              if(oRep.length != 0){ 
                                              
                $(".col-lg-9 .row").append(jArticle.clone(true).attr("id",oRep[i].id));

                $("#" + oRep[i].id +" .card-img-top").attr('src',"./images/"+oRep[i].image);
                $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                 .html(oRep[i].titre)
                                                 .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                 .attr("id",oRep[i].id)
                                                 );
                if(admin == 1 || admin == 2){ 
                  $("#"+ oRep[i].id +" .card-body").append(iconTrash.clone(true)); 
                    $("#"+ oRep[i].id +" .card-body").append(iconPencil.clone(true));
                }// fin if admin
                                              
              }// fin if
      
            }//fin for
      if (oRep.length == 0) {
        $(".col-lg-9 .row").append(jWarning.clone(true).attr("id","warning"));
      }
    } // fin oRep != null
              },//fin succes 
              dataType: "json"
            })//fin ajax
        })//fin click
      )//fin append 
      
    var jAddCategorie=$('<div class="buttonsCenter"><input type="button" id="addC" value="Ajouter une catégorie"/></div>').click(function(){
    $('body').append(jPopupCategorie.clone(true));
    $("#newC").dialog({
      modal: true, 
      height: 300,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "Créer": function(){
        console.log($('#nomC').val());

      $.ajax({
        url: "libs/dataBdd.php",
        data:{"action":"CreerCategorie","nomC":$('#nomC').val(),"admin":admin,"couleur":$('#couleur').val()},
        type : "POST",
        success:function (oRep){
          console.log(oRep);
          $("#newC").dialog( "close" ); // ferme la pop up 
        	$("#newC").remove(); // supprime la pop up
        	tab=[];
        	$(".list-group").empty();
        	$(".col-lg-9").empty();
        	remplirMenu(); 

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
      $(this).remove(); // supprime la pop up 
      }
    });

  });

	var jAddFinition=$('<div class="buttonsCenter"><input type="button" id="addF" value="Ajouter une finition"/></div>').click(function(){
    $('body').append(jPopupFinition.clone(true));
    $("#newF").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "Créer": function(){
        console.log($('#nomF').val());

      $.ajax({
        url: "libs/dataBdd.php",
        data:{"action":"CreerFinition","nomF":$('#nomF').val()},
        type : "POST",
        success:function (oRep){
          console.log(oRep);
          $("#newF").dialog( "close" ); // ferme la pop up 
          $("#newF").remove(); // supprime la pop up
        
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
      $(this).remove(); // supprime la pop up 
      }
    });

  });

     var jAddMatiere=$('<div class="buttonsCenter"><input type="button" id="addM" value="Ajouter une matière"/></div>').click(function(){
    $('body').append(jPopupMatiere.clone(true));
    $("#newM").dialog({
      modal: true, 
      height: 250,
      width: 400,
      buttons: { // on ajoute des boutons à la pop up 
        "Créer": function(){
        console.log($('#nomM').val());

      $.ajax({
        url: "libs/dataBdd.php",
        data:{"action":"CreerMatiere","nomM":$('#nomM').val()},
        type : "POST",
        success:function (oRep){
          console.log(oRep);
          $("#newM").dialog( "close" ); // ferme la pop up 
          $("#newM").remove(); // supprime la pop up
        
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
      $(this).remove(); // supprime la pop up 
      }
    });

  });
  
  var jPopupFinition=$('<div id="newF" title="Ajouter une finition"><div id="nomFin">Nom de la finition :</div>').append('<input type="text" id="nomF"/>');

  var jPopupMatiere=$('<div id="newM" title="Ajouter une matière"><div id="nomMat">Nom de la matiere :</div>').append('<input type="text" id="nomM"/>');
  
  var jPopupCategorie=$('<div id="newC" title="Ajouter une catégorie"><div id="nomCol">Nom de la catégorie :</div>').append('<input type="text" id="nomC"/>').append('<div id="colCat">Couleur de la catégorie :</div>').append('<input type="color" id="couleur" name="head" value="#E66465">');
  
  var jPopupDevis=$('<div id="newD" title="Création d\'un devis"><div id="nomCli">Nom du client : </div>').append('<input type="text" id="nomClient"/>').append('<div id="numDev">Numéro du devis :</div>').append('<input type="text" id="numD">').append('<div id="nomPro">Nom du projet :</div>').append('<input type="text" id="nomP"/>');

//-------- VAR GLOBALE ----- //

 var admin ="<?php echo $admin; ?>";
 var idUser ="<?php echo $idUser; ?>";
 console.log("admin =>" + admin); 
 var categLoad = "<?php echo $categ; ?>";

//------------ CRÉATION DE LA PAGE D'ACCUEIL LORSQUE QUE LE CATALOGUE EST CHARGÉ-----------//  
   $(document).ready(function(){

   	$(".col-lg-3").append(jRecherche.clone(true));
   	if(admin == 1 || admin == 2) {
    	$(".col-lg-3").append(jAddCategorie.clone(true));
		$(".col-lg-3").append(jAddFinition.clone(true));
		$(".col-lg-3").append(jAddMatiere.clone(true));
    }
    $(".col-lg-3").append(jAddDevis.clone(true));
    remplirMenu();
  });// fin de la fonction de chargement

  function remplirCatgegorieV1(){ // ACCUEIL
  
  var couleurBord;
  
    $(".col-lg-9 .row").each(function(){  
                                      
                                      var couleur= $(this).prev().css("color");
                                      var nom = $(this).prop("id");
                                      console.log("remplissage !!" + nom);  
                                      var lien = $(this);

                                      $.ajax({
                                              url: "libs/dataBdd.php",
                                              data:{"action":"Articles","categorie":nom},
                                              type : "GET",

                                              success:function (oRep){ 
                                                console.log(oRep);

                                                if(oRep.length != 0){ 
                                                  for (var i =0; i<9;i++){
                                                  
                                                  if ( i >= oRep.length ){ 
                                                    console.log("OUTTTTTTTTTTTT!!!!!!");
                                                    break; 
                                                  }
                                                  
                                                   couleurBord = couleur;

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").css("border", "3px solid "+couleurBord)
                                                   																			.attr('src',"./images/"+oRep[i].image);
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id+"&categorie="+$(lien).prop("id"))
                                                                                    .attr("id",oRep[i].id)
                                                                                    );

                                                   if(admin == 1 || admin == 2){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.data("idFerrure",oRep[i].id).clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.data("idFerrure",oRep[i].id).clone(true));
                                                    }// fin if admin 
                                                  }//fin for
                                                  
                                                  // TRANSFORME LA DIV EN GALERIE
                                                  $(lien).slick({
                                                      dots: true,
                                                      infinite: true,
                                                      slidesToShow: 3,
                                                      slidesToScroll: 3
                                                    });
                                                  
                                                }// fin if    
                                              },// fin succes
                                              error : function(jqXHR, textStatus) {
                                                console.log("erreur");  
                                              },
                                              dataType: "json"
                                            });
      });  
  }

  function remplirCatgegorieV2(categLoad=null){ // 1 CATEGORIE
  
  var couleurBord;
  
    $(".col-lg-9 .row").each(function(){  
                                      var couleur= $(this).prev().css("color");
                                      
                                      if(categLoad == null) {
		                                  var nom = $(this).prop("id");
		                                  if (nom == "Tout")
		                                    nom = null ; // si catégorie est null alors on va chercher toutes les catégories
		                              }
		                              else
		                              	var nom = categLoad ;
		                              
                                      var lien = $(this);

                                      $.ajax({
                                              url: "libs/dataBdd.php",
                                              data:{"action":"Articles","categorie":nom},
                                              type : "GET",

                                              success:function (oRep){ 
                                                console.log(oRep);

                                                if(oRep.length != 0){ 
                                                  for (var i = 0; i < oRep.length; i++){
                                                  
                                                   couleurBord = couleur;

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").css("border", "3px solid "+couleurBord)
                                                   																			.attr('src',"./images/"+oRep[i].image);
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id+"&categorie="+$(lien).prop("id"))
                                                                                    .attr("id",oRep[i].id)
                                                                                    );
                                                    if(admin == 1 || admin == 2){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.data("idFerrure",oRep[i].id).clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.data("idFerrure",oRep[i].id).clone(true));
                                                    }// fin if admin
                                                  }//fin for
                                                }// fin if
                                                if(admin == 1 || admin == 2){
                                                	$(lien).prepend(imgAjout.clone(true)
                                                           .attr("id",nom));   
                                                }
                                              },// fin succes
                                              error : function(jqXHR, textStatus) {
                                                console.log("erreur");  
                                              },
                                              dataType: "json"
                                            });
      });  
  }
  
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
           $("#newD").dialog( "close" ); // ferme la pop up
           $("#newC").remove(); // supprime la pop up 
			$("body").prepend("<div id='ajoutDevOK'>Le devis a bien été créé</div>");
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
  
  function remplirMenu() {

    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"Categories"},
      type : "GET",
      success: function(oRep){
          console.log(oRep);
          for (var i = 0; i < oRep.length; i++) {
            tab.push(oRep[i].couleur);
          }
          console.log(tab);
      },
    error : function(jqXHR, textStatus) {
        console.log("erreur");
    },
    dataType: "json"
  });

    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"Categories"},
      type : "GET",
      success:function (oRep){
        console.log(oRep);
        var couleurCat;
        
        // création du menu avec les catégories en jquery
        for (var i=0 ; i<oRep.length ;i++) {
        
          if (oRep[i].nomCategorie != "Tout"){
            couleurCat = tab[(oRep.length+i)-(oRep.length)];
          }
          else
            couleurCat = "black";
          if(oRep[i].nomCategorie != "Tout")
          $(".list-group").append(jMenu.css("color", couleurCat).clone(true)
            .html(oRep[i].nomCategorie));

          if(oRep[i].nomCategorie != "Tout"){ 
             $(".col-lg-9").append(jtitre.html(oRep[i].nomCategorie).css("color", couleurCat).clone(true)); 
             $(".col-lg-9").append(jCatgegorie
                                .attr("id",oRep[i].nomCategorie)
                                .clone(true));
          }// fin if 
        }// fin for
        $(".list-group").append(jMenu.css("color", "black").clone(true)
            .html("Tout"));
            
          if(categLoad != null && categLoad != ""){ 
            $(".list-group-item:contains('"+categLoad+"')").click();
          } 
          else 
          	remplirCatgegorieV1(); 
      },// fin succes
      dataType: "json"
    });// fin requête ajax
  }

  </script>

  <body>

    <!-- Page Content -->
    <div class="container">

      <div class="row"> <!-- <div class="menuCateg row"> -->

        <div class="col-lg-3">

          <div class="list-group">

            <!-- CONTIENT LE MENU DES CATÉGORIES -->
          </div>

        </div>

        <div class="col-lg-9">

            <!-- CONTIENT LES CATGÉOGIRES AVEC LEURS FERRURES -->

        </div>
        <!-- /.col-lg-9 -->

      </div>
      <!-- /.row -->

    </div>
    <!-- /.container -->
  </body>


