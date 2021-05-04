<?php
  if (!valider("connecte","SESSION")) {
      header("Location:index.php?view=connexion");
      die("");
  }

  $idUser = valider("idUser","SESSION");
  $admin = valider("isAdmin","SESSION");  
?>
<link href='fullcalendar/lib/main.css' rel='stylesheet' />
<script src='fullcalendar/lib/main.js'></script>

 <!-- Bootstrap -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="jquery-ui/jquery-ui.min.js"></script>
<script>

  //-------- VAR ------- //
  var admin ="<?php echo $admin; ?>";
  var idUser = "<?php echo $idUser; ?>";

  document.addEventListener('DOMContentLoaded', function() {

    /* initialize the external events
    -----------------------------------------------------------------*/

    var containerEl = document.getElementById('external-events-list');
    new FullCalendar.Draggable(containerEl, {
      itemSelector: '.fc-event',
      eventData: function(eventEl) {
        return {
          title: eventEl.innerText.trim()
        }
      }
    });

    //// the individual way to do it
    // var containerEl = document.getElementById('external-events-list');
    // var eventEls = Array.prototype.slice.call(
    //   containerEl.querySelectorAll('.fc-event')
    // );
    // eventEls.forEach(function(eventEl) {
    //   new FullCalendar.Draggable(eventEl, {
    //     eventData: {
    //       title: eventEl.innerText.trim(),
    //     }
    //   });
    // });

    /* initialize the calendar
    -----------------------------------------------------------------*/

    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listWeek'
      },
      editable: true,
      droppable: true, // this allows things to be dropped onto the calendar
      drop: function(arg) {
        // is the "remove after drop" checkbox checked?
        /*if (document.getElementById('drop-remove').checked) {
          // if so, remove the element from the "Draggable Events" list si on veut supprimer l'événement une fois placé dans le calendrier : normalement oui, dans tous les cas */
          
								  var ladiv = arg.draggedEl // l'ancienne div dans la liste

								  var newEventID = $(ladiv).children().data("id"); // l'id du devis droppé depuis la liste
								  var newEventDate = arg.dateStr;                  // sa date à enregistrer dans la BDD

								  planifierDevis(newEventDate, newEventID);

								  arg.draggedEl.parentNode.removeChild(arg.draggedEl);

								  location.reload();
          
        //}
      },

      eventDrop: function(info) {
      		var idDevis = info.event.id;
          $.ajax({
          		url: "libs/dataBdd.php",
              data:{"action":"Devis","idDevis":idDevis,"idUser":idUser},
              type : "GET",
              success:function (oRep){
								console.log(oRep);
								if (oRep[0].etat != "LIVRÉ" && oRep[0].etat != "ARCHIVÉ") {
								  var eventID = info.event.id;
								  var dateString = info.event.start.toISOString().substr(0,10);

								  var eventDate = new Date(dateString);

								  eventDate.setDate(eventDate.getDate() + 1);
								  
								  dateString = eventDate.toISOString().substr(0,10);

								  planifierDevis(dateString, eventID);
								}
								else {
									alert("Le changement de date n'a pas pu être pris en compte car la commande n'est plus en fabrication.");
									location.reload();
								}
              },
                error: function(jqXHR, textStatus) {
                    console.log("erreur");
                },
                    dataType: "json"
              });
          
        },
        
        eventClick: function(info) {

          var idDevis = info.event.id;
          $("#contenu").empty();

          $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"Devis","idDevis":idDevis,"idUser":idUser},
              type : "GET",
              success:function (oRep){
                console.log(oRep);
                var idDevis = oRep[0].id;
                $("#contenu").data("id", idDevis);
                $("#contenu").append("Numéro du devis : " + oRep[0].numeroDevis);
                $("#contenu").append("<br>Nom du devis : " + oRep[0].nomProjet);
                $("#contenu").append("<br>Nom du client : " + oRep[0].nomClient);
                $("#contenu").append("<br>Date de création : " + convertirDate(oRep[0].dateCreation));
                $("#contenu").append("<br>")
                $("#contenu").append("<br>")

                $("<input type='button' value='Mettre en attente' class='infoButtons'>").click(function () {

                    var ans = confirm("Retirer la date de livraison du devis ? (le propriétaire en sera informé)");

                    if (ans) {
                      var subject = "Annulation de la livraison du devis " + oRep[0].nomProjet;
                      var body = "La date de livraison de votre devis " + oRep[0].nomProjet + " (" + oRep[0].numeroDevis + ")" + " a &eacute;t&eacute; annul&eacute;e, il n'est plus en fabrication.";
                      mailClient(idDevis, subject, body);
                      annulerDevis(idDevis);
                    }

                  })

                .appendTo("#contenu");

                $("#contenu").append("<br>")
                $("<input type='button' value='Envoyer un mail au client' class='infoButtons'>").click(function () {

                    var ans = confirm("Informer le propriétaire du devis de la date de livraison de sa commande (par mail) ?");
                    if (ans) {

                      var Id = $("#contenu").data("id");
                      devis = calendar.getEventById(Id);

                      var dateString = devis.start.toISOString().substr(0,10);

                      var eventDate = new Date(dateString);

                      eventDate.setDate(eventDate.getDate() + 1);
          
                      dateString = eventDate.toISOString().substr(0,10);

                      var subject = "Date de livraison du devis " + oRep[0].nomProjet;
                      var body = "La livraison de votre devis " + oRep[0].nomProjet + " est pr&eacute;vue pour le " + convertirDate(dateString);
                      mailClient(idDevis, subject, body);
                    }
                  })

                .appendTo("#contenu");
                $("#contenu").append("<br>")

                $("<input type='button' value='Livrer la commande' class='infoButtons'>").click(function () {

                    var ans = confirm("Confirmer la livraison du devis ? (le propriétaire en sera informé)");

                    if (ans) {
                      var subject = "Livraison de la commande " + oRep[0].nomProjet;
                      var body = "Votre commande pour " + oRep[0].nomProjet + " (" + oRep[0].numeroDevis + ")" + " est pr&ecirc;te.";
                      mailClient(idDevis, subject, body);
                      livrerDevis(idDevis); 
                      $("input[value='Mettre en attente']").remove();
                    }

                  })

                .appendTo("#contenu");

                if (oRep[0].etat == "LIVRÉ" || oRep[0].etat == "ARCHIVÉ" ){
                  $("input[value='Livrer la commande']").remove();
                  $("input[value='Mettre en attente']").remove();
                }
              },
                error : function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });
        },
      locale: 'fr'
    });
    calendar.render();

      getDevisEnAttente();
    getDevisPlanifies();

});

  function addEvent(eventName, eventID) {
    var divEvent = $("<div>").addClass("fc-event fc-h-event fc-daygrid-event fc-daygrid-block-event").click(

      function () {

        var idDevis = $(this).children().data("id");
          $("#contenu").empty();

          $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"Devis","idDevis":idDevis,"idUser":idUser},
              type : "GET",
              success:function (oRep){
                console.log(oRep);
                var idDevis = oRep[0].id;
                $("#contenu").data("id", idDevis);
                $("#contenu").append("<br>Numéro du devis : " + oRep[0].numeroDevis);
                $("#contenu").append("<br>Nom du devis : " + oRep[0].nomProjet);
                $("#contenu").append("<br>Nom du client : " + oRep[0].nomClient);
                $("#contenu").append("<br>Date de création : " + convertirDate(oRep[0].dateCreation));
                $("#contenu").append("<br>");

              },
                error : function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });

      }
    );
    var divEvent2 = $("<div>").addClass("fc-event-main").html(eventName).data("id", eventID).appendTo(divEvent);
    $("#external-events-list").append(divEvent);
      }

  function getDevisEnAttente() {

      $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"DevisEnAttente"},
            type : "GET",
            success:function (oRep){
                console.log(oRep);

                for (i = 0 ; i < oRep.length ; i++)
                  addEvent(oRep[i].nomProjet, oRep[i].id);
              },
            dataType: "json"
      });
  }

  function annulerDevis(idDevis) {

      // Annulation du devis
      $.ajax({
              url: "libs/dataBdd.php",
              data:{"action":"AnnulerDevis","idDevis":idDevis},
              type : "GET",
              success:function (oRep){

                console.log("Devis annulé");
                location.reload();


                },

                error: function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });

  }

  function mailClient(idDevis, subject, body) {

      // Envoi d'un mail au client du devis
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

  function getDevisPlanifies() {

      $.ajax({
            url: "libs/dataBdd.php",
            data:{"action":"DevisPlanifies"},
            type : "GET",
            success:function (oRep){
                console.log(oRep);
                $.each( oRep, function(event) {
                  var eventID = this.id;
                  var eventTitle = this.nomProjet;
                  var eventStart = this.dateLivraison;

									if (this.etat == "LIVRÉ") {
										calendar.addEvent({
                      id: eventID,
                      title: eventTitle,
                      start: eventStart,
											backgroundColor: "green",
											borderColor: "darkgreen",
											droppable: false,
                      allDay: true
                    });
									}
									else {
										calendar.addEvent({
                      id: eventID,
                      title: eventTitle,
                      start: eventStart,
                      textColor: "black",
											backgroundColor: "gold",
											borderColor: "orange",
                      allDay: true
                    });
									}
                  
                });

              },
            dataType: "json"
      });
  }

  function planifierDevis(eventDate, eventID) {
    $.ajax({
            url: "libs/dataBdd.php",
            data:{
              "action":"PlanifierDevis",
              "id": eventID,
              "date": eventDate
            },
            type : "POST",
            success:function (oRep){
                console.log(oRep);
              },
            dataType: "json"
      });
  }

  function livrerDevis(idDevis){
    $.ajax({
              url: "libs/dataBdd.php" + "?action=LiverDevis&idDevis=" + idDevis,
              data:{"action":"LiverDevis","idDevis":idDevis},
              type : "PUT",
              success:function (oRep){
                console.log("Devis livré");
                },

                error: function(jqXHR, textStatus) {
                    console.log("erreur");
                    },
                    dataType: "json"
              });
  }

  function convertirDate(date) {

    tabDate = date.split('-');

    newDate = tabDate[2] + '/' + tabDate[1] + '/' + tabDate[0];

    return newDate;
  }
  
  function afficherArchives() {
  		$.ajax({
		          url: "libs/dataBdd.php",
		          data:{"action":"DevisArchives"},
		          type : "GET",
		          success:function (oRep){
		              console.log(oRep);
		              $.each( oRep, function(event) {
		                var eventID = this.id;
		                var eventTitle = this.nomProjet;
		                var eventStart = this.dateLivraison;

										// si la case est cochée, on affiche les devis archivés
    								if ($("#dispArchive").prop("checked") == true) {
    									if (!archivesPlanifiees) {
						            calendar.addEvent({
						                id: eventID,
						                title: eventTitle,
						                start: eventStart,
						                backgroundColor: "darkgrey",
						                borderColor: "grey",
						                droppable: false,
						                allDay: true
						            });
						            
						            var archivesPlanifiees = true;
                      }
                      else {
                        var tabEvents = calendar.getEvents();

                        tabEvents.forEach(function(event) {

                        var couleur = event.backgroundColor;

                        if (couleur == "darkgrey") {
                          event.setProp('display', 'none');
                        }
                      });
						        	}
				            }
				            else {
				            	var tabEvents = calendar.getEvents();

                      tabEvents.forEach(function(event) {

                        var couleur = event.backgroundColor;

                        if (couleur == "darkgrey") {
                          event.setProp('display', 'none');
                        }
                      });
				            }
		              });

		            },
		          dataType: "json"
		    });
			
  }

</script>
<style>

  body {
    margin-top: 65px;
    font-size: 14px;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
  }
  
  label {
  	margin-left: 5px;
  }

  #external-events {
    position: fixed;
    left: 20px;
    top: 120px;
    width: 250px;
    max-height: 320px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
    display: block;
    overflow-y: auto; 
  }

  #external-events h4 {
    font-size: 16px;
		font-weight: bold;
    margin-top: 0;
    padding-top: 1em;
		padding: 10px;
  }

  #external-events .fc-event {
    margin: 3px 0;
    cursor: move;
  }

  #external-events p {
    margin: 1.5em 0;
    font-size: 11px;
    color: #666;
  }

  #external-events p input {
    margin: 0;
    vertical-align: middle;
  }

  #calendar-wrap {
    margin-left: 200px;
  }

  #calendar {
    max-width: 1100px;
    margin: 0 90px 50px;
  }

  #infos {
    position: fixed;
    left: 0px;
    top: 300px;
    width: 250px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
    margin: 20px; 
  }

	#titreInfos {
		font-weight: bold;
		font-size: 16px;
		padding: 10px;
	}
	
	#archi {
		position: fixed;
    left: 0px;
    top: 650px;
    width: 250px;
    padding: 0 10px;
    border: 1px solid #ccc;
    background: #eee;
    text-align: left;
    margin: 20px; 
	}

  #contenu {
    padding: 10px;
    font-size: 16px;
  }

  .infoButtons {
    margin-bottom: 5px;
    color: white;
		background-color: #353a40;
  }

  .livre{
    background-color: lightgreen ; 
  }

</style>

<body>
  <div id='wrap'>

    <div id='external-events'>
      <h4>Liste des devis à planifier</h4>


      <div id='external-events-list'>
      <!-- events -->
      </div>

      <p>
      </p>
      
    </div>
    

    <div id='calendar-wrap'>
      <div id='calendar'></div>

    </div>
    
    <div id="infos">
	<div id="titreInfos">Informations du devis</div>
    <div id="contenu"></div>
  </div>
  
  <div id="archi">
  <input type="checkbox" name="archive" id="dispArchive" onclick="afficherArchives();"/><label for="archive">Afficher les devis archivés</label>
  </div>
  
  </div>

</body>

