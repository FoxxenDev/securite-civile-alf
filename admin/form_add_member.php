<?php

require_once("../inc/functions.php");

if(!isRank("État-Major") && !isRank("Admin site")){header("Location: ../index.php");$_SESSION['flash']['danger'] = "Vous n'avez pas l'autorisation d'accéder à cette page !";exit();}

if ( isset( $_POST[ 'email' ] ) && !empty( $_POST[ 'email' ] ) ) {
	require_once( '../inc/functions.php' );
	require_once( '../inc/bdd.php' );
	
	$rank2 = "";
	foreach($_POST["rank"] as $rank){
		$rank2 .= $rank.";";
	}
	
	
	session_start();
	$token = token(80);
	$req = $bdd->prepare( "INSERT INTO user (email, token, register_by_id, rank_sc_id, rank_sru_id, rank_meca_id, rank) VALUES (?, ?, ?, ?, ?, ?, ?)" );
	$req->execute( [ $_POST[ 'email' ], $token, "1", $_POST[ 'rank_sc' ], $_POST[ 'rank_sru' ], $_POST[ 'rank_meca' ], $rank2 ] );
	$id = $bdd->lastInsertId();
	$_SESSION[ 'flash' ]->success = "Veuillez donner le lien suivant à la personne recrutée : <a href=\"http://127.0.0.1/secuv2/register.php?id=" . $id . "&token=" . $token . "\">http://127.0.0.1/secuv2/register.php?id=" . $id . "&token=" . $token . "</a>";
	header( "Location: form_add_member.php" );
	exit();
	
}

require( "../inc/header.php" );

?>
<div class="box">
	<form action="form_add_member.php" method="post" id="form1" role="form">
		<div class="box-header with-border">
			<h3 class="box-title">Recruter une nouvelle personne</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<div class="form-group" id="mailDiv">
				<label>Adresse mail</label>
					<input name="email" type="email" id="mailInput" class="form-control has-error" placeholder="Email">
				<span class="help-block" id="mailSpan" style="display: none;">L'adresse mail doit être au format : john@doe.fr</span>
			</div>
			<!-- select -->
			<div class="form-group">
				<label for="rank_sc">Garde secouriste</label>
				<select name="rank_sc" class="form-control" id="rank_sc">
					<option selected value="1">2nd Classe</option>
					<option value="2">1ère Classe</option>
					<option value="3">Caporal</option>
					<option value="4">Caporal-Chef</option>
					<option value="5">Brigadier</option>
					<option value="6">Brigadier-Chef</option>
					<option value="7">Adjudant</option>
					<option value="8">Adjudant-Chef</option>
					<option value="9">Lieutenant</option>
					<option value="10">Capitaine</option>
					<option value="11">Commandant</option>
					<option value="12">Colonel</option>
					<option value="13">Général</option>
				</select>
			</div>
			<div class="form-group">
				<label for="rank_meca">Garde mécanicien</label>
				<select class="form-control" name="rank_meca" id="rank_meca">
					<option selected value="0">Aucun grade</option>
					<option value="1">2nd Classe</option>
					<option value="2">Agent dépanneur</option>
					<option value="3">Formateur dépannage</option>
					<option value="4">Chef Mécanicien</option>
				</select>
			</div>
			<div class="form-group">
				<label for="rank_sru">Garde S.R.U</label>
				<select class="form-control" name="rank_sru" id="rank_sru">
					<option selected value="0">Aucun grade</option>
					<option value="1">Interne</option>
					<option value="2">Médecin Aspirant</option>
					<option value="3">Médecin</option>
					<option value="4">Médecin Agréer</option>
					<option value="5">Médecin Coordinateur</option>
					<option value="6">Médecin Chef</option>
				</select>
			</div>
              <div class="form-group">
                <label>Grade</label>
                <select name="rank[]" class="form-control select2" multiple="multiple" data-placeholder="Sélectionnez une valeur"
                        style="width: 100%;">
                  <option selected value="Secouriste">Secouriste</option>
                  <option value="Mécanicien">Mécanicien</option>
                  <option value="S.R.U">S.R.U</option>
				  <option value="Formateur">Formateur</option>
                  <option value="Chef-Mécanicien">Chef-Mécanicien</option>
                  <option value="Chef-S.R.U">Chef-S.R.U</option>
                  <option value="État-Major">État-Major</option>
                  <option disabled value="Admin site">Admin Site</option>
                </select>
              </div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button id="submit" type="submit" class="btn btn-primary">Recruter</button>
		</div>
	</form>
</div>
<?php require("../inc/footer.php"); ?>
<!-- Page script -->
<script type="text/javascript">	
	$( function () {
		
		$('.select2').select2()
		
		$valid = false;
		$( "#mailInput" ).keyup( function () {
			if ( $( "#mailInput" ).val() == "" ) {
				$( "#mailDiv" ).addClass( "has-error" );
				$valid = false;
			}
			if ( !$( "#mailInput" ).val().match( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/ ) ) {
				$( "#mailDiv" ).addClass( "has-error" );
				$( "#mailDiv" ).removeClass( "has-success" );
				$( "#mailSpan" ).fadeIn();
				$valid = false;
			} else {
				$( "#mailDiv" ).addClass( "has-success" );
				$( "#mailDiv" ).removeClass( "has-error" );
				$( "#mailSpan" ).fadeOut();
				$valid = true;
			}
		} );
		$( "#submit" ).click( function () {
			console.log( $valid );
			return $valid;
		} );
	} );
</script>