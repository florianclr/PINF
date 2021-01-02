

<?php
  $admin = valider("isAdmin","SESSION");  
?>


  <!-- Bootstrap core JavaScript -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript">

// -------- MODELES JQUERY --------------//

  var jArticle = $('<div class="col-lg-4 col-md-6 mb-4">')
                  .append('<div class="card h-100"><img class="card-img-top"> <div class="card-body">');
      
                    
  var jCatgegorie = $('<div class=row></div>');

  var jtitre =  $('<h2 class="titre"></h2>');

  var jLien = $('<a href="#">').click(function () {
                                                    console.log(this.id);
                                                    // INSERER SUITE BENOIT ... 
                                                    // JTM
                                                  });  

  var jMenu=$('<a herf="#" class="list-group-item">').click(function(){
                                                $nomCategorie = $(this).html();
                                                $(".col-lg-9").empty(); // on vide l'ancien contenu affiché
                                                $(".col-lg-9").append(jtitre.html($nomCategorie).clone(true));
                                                $(".col-lg-9").append(jCatgegorie
                                                                    .attr("id",$nomCategorie)
                                                                  //.append(jtitre.html(oRep[i].nomCategorie))
                                                                  //.addClass("categorie")
                                                                    .clone(true));
                                                remplirCatgegorieV2();
  });

  var iconTrash = $('<button class="trashButton">').append('<img src="./ressources/delete.png" class="trash">'); 
  var iconPencil = $('<button class="trashPencil">').append('<img src="./ressources/pencil.png" class="pencil">');  

//-------- VAR GLOBALE ----- //
 var admin ="<?php echo $admin; ?>";
 console.log("admin =>" + admin); 
//------------ CRÉATION DE LA PAGE D'ACCEUIL LORSQUE QUE LE CATALOGUE EST CHARGÉ-----------//  
   $(document).ready(function(){
    
    $.ajax({
      url: "libs/dataBdd.php",
      data:{"action":"Categories"},
      type : "GET",
      success:function (oRep){
        console.log(oRep);
        // création du menu avec les catégories en jquery
        for (var i=0 ; i<oRep.length ;i++) {

          $(".list-group").append(jMenu.clone(true)
            .html(oRep[i].nomCategorie));
          

          if(oRep[i].nomCategorie != "Tout"){ 
             $(".col-lg-9").append(jtitre.html(oRep[i].nomCategorie).clone(true)); 
             $(".col-lg-9").append(jCatgegorie
                                .attr("id",oRep[i].nomCategorie)
                                //.append(jtitre.html(oRep[i].nomCategorie))
                                //.addClass("categorie")
                                .clone(true)); 
          }// fin if 
        }// fin for
          remplirCatgegorieV1(); 
      },// fin succes
      dataType: "json"
    });// fin requête ajax
  });// fin de la fontion de chargement

//////////////////////////////////////////////////////////////////// TODO : RENDRE LE CODE QUI SUIT PLUS OPTIMAL !!!! ////////////////////////////////////:

  function remplirCatgegorieV1(){ // ACCEUIL
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

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").attr('src',"./ressources/"+oRep[i].image+".jpeg");
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                                                    .attr("id",oRep[i].id)
                                                                                    );
                                                   // TODO : AJOUTER FLÈCHE !!!

                                                   if(admin == 1){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.clone(true));
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

  function remplirCatgegorieV2(){ // ACCEUIL
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
                                                  for (var i =0; i<oRep.length;i++){

                                                   $(lien).append(jArticle.clone(true)
                                                          .attr("id",oRep[i].id));

                                                   $("#" + oRep[i].id +" .card-img-top").attr('src',"./ressources/"+oRep[i].image+".jpeg");
                                                   $("#"+ oRep[i].id +" .card-body").append(jLien.clone(true)
                                                                                    .html(oRep[i].titre)
                                                                                    .attr("href","./index.php?view=article&produit="+oRep[i].id)
                                                                                    .attr("id",oRep[i].id)
                                                                                    );
                                                    if(admin == 1){ 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconTrash.clone(true)); 
                                                      $("#"+ oRep[i].id +" .card-body").append(iconPencil.clone(true));
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


