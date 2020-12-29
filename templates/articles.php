 <script src="vendor/jquery/jquery.min.js"></script>
<?php
	include_once "libs/maLibUtils.php";
	include_once "libs/maLibSQL.pdo.php";
	include_once "libs/maLibSecurisation.php"; 
	include_once "libs/modele.php"; 
	include_once "libs/maLibForms.php";

$categorie = valider("categorie");
?>

<script type="text/javascript">
	var categorie="<?php echo $categorie; ?>";
	console.log(categorie);
var jArticle=$('<div class="card h-100"><img class="card-img-top" alt=""></a><div class="card-body"><h4 class="card-title"></h4></div></div></div>');
var jDiv=$('<div class="col-lg-4 col-md-6 mb-4">')
        .click(function () {
             console.log(this.id);
        });

$.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Articles"},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
      for (var i =0; i<oRep.length;i++) {
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

</script>
<body>
 
 <h1 class="my-4"><?php echo $categorie;?></h1>
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

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
