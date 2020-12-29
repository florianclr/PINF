
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">


  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="css/shop-homepage.css" rel="stylesheet">
   <script src="vendor/jquery/jquery.min.js"></script>

<script type="text/javascript">

var jMenu=$('<a class="list-group-item"></a>')
          .click(function () {
            console.log(this);
          });

var jArticle=$('<div class="card h-100"><img class="card-img-top" alt=""/><div class="card-body"><h4 class="card-title"></h4></div></div></div>');

var jDiv=$('<div class="col-lg-4 col-md-6 mb-4">')
        .click(function () {
             console.log(this.id);
             document.location.href="./index.php?view=article&produit="+this.id;

        });

function affichage() {
  
  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Categories"},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
      for (var i=0 ;i<oRep.length;i++) {
        $(".list-group").append(jMenu.clone(true)
          .html(oRep[i].nomCategorie));

      } 
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
  });

$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Articles"},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
      for (var i =0; i<3;i++) {
        $(".col-lg-9 .row").append(jDiv.clone(true).attr("id",oRep[i].id));
        var id='#'+oRep[i].id;
        $(id).append(jArticle.clone(true));
        $(id +" .card-img-top").attr('src',"./ressources/"+oRep[i].image+".jpeg");
         $(id +" .card-title").html(oRep[i].titre);
      }
        
    },
    error : function(jqXHR, textStatus) {
      console.log("erreur");  
    },
    dataType: "json"
  });
}

</script>

</head>

<body onload="affichage();">

  <!-- Page Content -->
  <div class="container">

    <div class="row">

      <div class="col-lg-3">

        <div class="list-group">
        </div>

      </div>
      <!-- /.col-lg-3 -->

      <div class="col-lg-9">

        <div class="row">


        </div>
        <!-- /.row -->

      </div>
      <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

  </div>
  <!-- /.container -->


  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
