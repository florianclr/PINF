
<script type="text/javascript">
  
function deconnexion() {

  $.ajax({
    url: "libs/dataBdd.php",
    data:{"action":"Deconnexion"},
    type : "GET",
    success:function (oRep){
      console.log(oRep);
      document.location.href="./index.php";
    },
    error : function(jqXHR, textStatus){
      console.log("erreur");
    },
    dataType: "json"
  });

}
</script>

<!-- **** B O D Y **** -->
<body>

<footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; Déci'2i</p>
      <p class="m-0 text-center text-white">
      <?php
    // Si l'utilisateur est connecté, on affiche un lien de déconnexion 
    if (valider("connecte","SESSION"))
    {
      echo "Utilisateur <b>$_SESSION[pseudo]</b> connecté depuis <b>$_SESSION[heureConnexion]</b> &nbsp; "; 
      echo '<input type="button" onclick="deconnexion();" value="Se déconnecter"/>';
    }

    ?>
  </p>
    </div>
    <!-- /.container -->
  </footer>
</body>
</html>





