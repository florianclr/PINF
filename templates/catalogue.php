

<?php
  	$admin = valider("isAdmin","SESSION");  
  	$idUser = valider("idUser","SESSION");

	if (!valider("connecte","SESSION")) {
  		header("Location:index.php?view=connexion");
  		die("");
	}
?>

<!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript">

// -------- MODELES JQUERY --------------//

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

  var jArticle = $('<div class="col-lg-4 col-md-6 mb-4">')
                  .append('<div class="card h-100"><img class="card-img-top"> <div class="card-body fond">');
      
                    
  var jCatgegorie = $('<div class=row></div>');
  
  var jWarning = $('<div>Aucun résultat ne correspond à votre recherche</div>');

  var jtitre =  $('<h2 class="titre"></h2>');

  var jLien = $('<a href="#">');

  var jMenu=$('<a herf="#" class="list-group-item">').click(function(){
                $nomCategorie = $(this).html();
                $couleurCategorie = $(this).css("color");
                $(".col-lg-9").empty(); // on vide l'ancien contenu affiché
                $(".col-lg-9").append(jtitre.html($nomCategorie).css("color", $couleurCategorie).clone(true));
                $(".col-lg-9").append(jCatgegorie
                              .attr("id",$nomCategorie)
                              .clone(true));
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

  var iconPencil = $('<button class="trashPencil">').append('<img src="./ressources/pencil.png" class="pencil">');  

  var imgAjout = $('<div class="col-lg-4 col-md-6 mb-4">')
                  .append($('<img class="card-img-top" src="./ressources/plus.jpg">')
                  	.click(function(){
                  		console.log("redirection...");
                  		document.location.href="./index.php?view=creerArticle";
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
                      if(admin == 1){ 
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
                if(admin == 1){ 
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
  
  var jPopupCategorie=$('<div id="newC" title="Ajouter une catégorie"><div id="nomCol">Nom de la catégorie :</div>').append('<input type="text" id="nomC"/>').append('<div id="colCat">Couleur de la catégorie :</div>').append('<input type="color" id="couleur" name="head" value="#E66465">');
  
  var jPopupDevis=$('<div id="newD" title="Création d\'un devis"><div id="nomCli">Nom du client : </div>').append('<input type="text" id="nomClient"/>').append('<div id="numDev">Numéro du devis :</div>').append('<input type="text" id="numD">').append('<div id="nomPro">Nom du projet :</div>').append('<input type="text" id="nomP"/>');

//-------- VAR GLOBALE ----- //

 var admin ="<?php echo $admin; ?>";
 var idUser ="<?php echo $idUser; ?>";
 console.log("admin =>" + admin); 

//------------ CRÉATION DE LA PAGE D'ACCEUIL LORSQUE QUE LE CATALOGUE EST CHARGÉ-----------//  
   $(document).ready(function(){

   	$(".col-lg-3").append(jRecherche.clone(true));
   	if(admin==1)
    	$(".col-lg-3").append(jAddCategorie.clone(true));
    	
    $(".col-lg-3").append(jAddDevis.clone(true));
    
    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"Categories"},
      type : "GET",
      success:function (oRep){
        console.log(oRep);
        var couleurCat;
        
        // création du menu avec les catégories en jquery
        for (var i=0 ; i<oRep.length ;i++) {
        
        	if (oRep[i].nomCategorie != "Tout")
        		couleurCat = tab[(oRep[i].id)-1];
        	else
        		couleurCat = "black";
					
          $(".list-group").append(jMenu.css("color", couleurCat).clone(true)
            .html(oRep[i].nomCategorie));

          if(oRep[i].nomCategorie != "Tout"){ 
             $(".col-lg-9").append(jtitre.html(oRep[i].nomCategorie).css("color", couleurCat).clone(true)); 
             $(".col-lg-9").append(jCatgegorie
                                .attr("id",oRep[i].nomCategorie)
                                //.append(jtitre.html(oRep[i].nomCategorie))
                                //.addClass("categorie")
                                .clone(true)); 
            // TODO : SI ADMIN OPTION POUR CRÉER UNE CATÉGORIE !!
          }// fin if 
        }// fin for
          remplirCatgegorieV1(); 
      },// fin succes
      dataType: "json"
    });// fin requête ajax
  });// fin de la fontion de chargement

///////////////////////////////// TODO : RENDRE LE CODE QUI SUIT PLUS OPTIMAL !!!! ///////////////////////////:

  function remplirCatgegorieV1(){ // ACCUEIL
  
  var couleurBord;
  
    $(".col-lg-9 .row").each(function(){  
                                      
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
                                                  for (var i =0; i<3;i++){
                                                  
                                                   couleurBord = tab[(oRep[i].refcategories)-1];

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").css("border", "3px solid "+couleurBord)
                                                   																			.attr('src',"./images/"+oRep[i].image);
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                                                    .attr("id",oRep[i].id)
                                                                                    );
                                                   // TODO : AJOUTER FLÈCHE !!!

                                                   if(admin == 1){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.data("idFerrure",oRep[i].id).clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.data("idFerrure",oRep[i].id).clone(true));
                                                    }// fin if admin 
                                                  }//fin for
                                                }// fin if    
                                              },// fin succes
                                              error : function(jqXHR, textStatus) {
                                                console.log("erreur");  
                                              },
                                              dataType: "json"
                                            });
      });  
  }

  function remplirCatgegorieV2(){ // 1 CATEGORIE
  
  var couleurBord;
  
    $(".col-lg-9 .row").each(function(){  
                                      
                                      var nom = $(this).prop("id");
                                      if (nom == "Tout")
                                        nom = null ; // si catgéogirie est null alors on va vercher toute les catégorie
                                      var lien = $(this);

                                      $.ajax({
                                              url: "libs/dataBdd.php",
                                              data:{"action":"Articles","categorie":nom},
                                              type : "GET",

                                              success:function (oRep){ 
                                                console.log(oRep);

                                                if(oRep.length != 0){ 
                                                  for (var i = 0; i < oRep.length; i++){
                                                  
                                                   couleurBord = tab[(oRep[i].refcategories)-1];

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").css("border", "3px solid "+couleurBord)
                                                   																			.attr('src',"./images/"+oRep[i].image);
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                                                    .attr("id",oRep[i].id)
                                                                                    );
                                                    if(admin == 1){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.data("idFerrure",oRep[i].id).clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.data("idFerrure",oRep[i].id).clone(true));
                                                    }// fin if admin
                                                  }//fin for
                                                }// fin if
                                                if(admin == 1){
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


