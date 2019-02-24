<?php

require_once( "../inc/functions.php" );

if ( !isRank( "État-Major" ) && !isRank( "Admin site" ) ) {
	header( "Location: ../index.php" );
	$_SESSION[ 'flash' ][ 'danger' ] = "Vous n'avez pas l'autorisation d'accéder à cette page !";
	exit();
}

if ( !isset( $_GET[ 'id' ] ) || empty( $_GET[ "id" ] ) ) {
	session_start();
	$_SESSION[ 'flash' ]->danger = "Impossible de modifier les informations de ce membre";
	header( "Location: liste_membre.php" );
	exit();
}

if ( isset( $_POST ) && !empty( $_POST ) ) {
	require_once( "../inc/bdd.php" );
	session_start();

	$upDate;
	if ( $_POST[ "old_rank_id" ] == $_POST[ "rank_sc" ] ) {
		$upDate = $_POST[ "old_date_up" ];
	} else {
		$upDate = date( "Y-m-d H:i:s" );
	}

	$rank2 = "";
	foreach ( $_POST[ "rank" ] as $rank ) {
		$rank2 .= $rank . ";";
	}

	if ( $_GET[ "id" ] == 1 ) {
		$rank2 .= "Admin site;";
	}

	$req1 = $bdd->prepare( "UPDATE user SET username = ?, email = ?, rank_sc_id = ?, rank = ?, up_at = ? , activite = ?, attribution = ? WHERE id = ?" );
	$req1->execute( [ $_POST[ 'username' ], $_POST[ 'mail' ], $_POST[ 'rank_sc' ], $rank2, $upDate, $_POST[ 'activite' ], $_POST[ 'attribution' ], $_GET[ 'id' ] ] );
	$_SESSION[ 'flash' ]->success = "L'utilisateur à bien été modifié";
	header( "Location: liste_membre.php" );
	exit();

}

require_once( "../inc/header.php" );

$req = $bdd->prepare( "SELECT user.*, rank_sc.*, rank_meca.*, rank_sru.* FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id WHERE id = ?" );
$req->execute( [ $_GET[ "id" ] ] );
$user = $req->fetch();
$rankValue = explode( ";", $user->rank );
$user->rank = $rankValue;

?>

<div class="box">
	<form method="post">
		<div class="box-header with-border">
			<h3 class="box-title">Éditer un membre</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<div class="row">
				<div class="col-sm-6">
					<div id="usernamediv" class="form-group">
						<label for="username">Prénom + Nom RP</label>
						<input type="text" value="<?= $user->username ?>" name="username" class="form-control" id="username" placeholder="Prénom + Nom RP" aria-describedby="usernamespan">
						<span id="usernamespan" class="help-block">Le pseudo doit être au format : Prénon Nom</span>
					</div>
				</div>
				<div class="col-sm-6">
					<div id="maildiv" class="form-group">
						<label for="mail">Mail</label>
						<input type="email" value="<?= $user->email ?>" name="mail" class="form-control" id="mail" placeholder="Mail" aria-describedby="email">
						<span id="email" class="help-block">L'adresse mail doit être au format exemple@domain.fr</span>
					</div>
				</div>
			</div>
			<input type="hidden" value="<?= $user->rank_sc_id ?>" name="old_rank_id">
			<input type="hidden" value="<?= $user->up_at ?>" name="old_date_up">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="rank_sc">Garde secouriste</label>
						<select name="rank_sc" class="form-control" id="rank_sc">
							<option <?php if($user->rank_sc_id == 1){echo "selected";} ?> value="1">2nd Classe</option>
							<option <?php if($user->rank_sc_id == 2){echo "selected";} ?> value="2">1ère Classe</option>
							<option <?php if($user->rank_sc_id == 3){echo "selected";} ?> value="3">Caporal</option>
							<option <?php if($user->rank_sc_id == 4){echo "selected";} ?> value="4">Caporal-Chef</option>
							<option <?php if($user->rank_sc_id == 5){echo "selected";} ?> value="5">Brigadier</option>
							<option <?php if($user->rank_sc_id == 6){echo "selected";} ?> value="6">Brigadier-Chef</option>
							<option <?php if($user->rank_sc_id == 7){echo "selected";} ?> value="7">Adjudant</option>
							<option <?php if($user->rank_sc_id == 8){echo "selected";} ?> value="8">Adjudant-Chef</option>
							<option <?php if($user->rank_sc_id == 9){echo "selected";} ?> value="9">Lieutenant</option>
							<option <?php if($user->rank_sc_id == 10){echo "selected";} ?> value="10">Capitaine</option>
							<option <?php if($user->rank_sc_id == 11){echo "selected";} ?> value="11">Commandant</option>
							<option <?php if($user->rank_sc_id == 12){echo "selected";} ?> value="12">Colonel</option>
							<option <?php if($user->rank_sc_id == 13){echo "selected";} ?> value="13">Général</option>
						</select>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
						<label>Grade</label>
						<select name="rank[]" class="form-control select2" multiple="multiple" data-placeholder="Sélectionnez une valeur" style="width: 100%;">
							<option <?php if(in_array( "Secouriste", $user->rank)){echo"selected";} ?> value="Secouriste">Secouriste</option>
							<option <?php if(in_array( "Mécanicien", $user->rank)){echo"selected";} ?> value="Mécanicien">Mécanicien</option>
							<option <?php if(in_array( "S.R.U", $user->rank)){echo"selected";} ?> value="S.R.U">S.R.U</option>
							<option <?php if(in_array( "Formateur", $user->rank)){echo"selected";} ?> value="Formateur">Formateur</option>
							<option <?php if(in_array( "Chef-Mécanicien", $user->rank)){echo"selected";} ?> value="Chef-Mécanicien">Chef-Mécanicien</option>
							<option <?php if(in_array( "Chef-S.R.U", $user->rank)){echo"selected";} ?> value="Chef-S.R.U">Chef-S.R.U</option>
							<option <?php if(in_array( "État-Major", $user->rank)){echo"selected";} ?> value="État-Major">État-Major</option>
							<option <?php if(in_array( "Admin site", $user->rank)){echo"selected";} ?> disabled value="Admin site">Admin Site</option>
						</select>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label for="activite">Activité</label>
						<select name="activite" class="form-control" id="activite">
							<option <?php if($user->activite == "Actif:"){echo "selected";} ?> value="Actif:">Actif</option>
							<option style="background-color: #FF0000;" <?php if($user->activite == "Inactif:#FF0000"){echo "selected";} ?> value="Inactif:#FF0000">Inactif</option>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="attribution">Attribution</label>
						<select name="attribution" class="form-control" id="attribution">
							<option <?php if($user->attribution == ""){echo "selected";} ?> value="">Aucun</option>
							<option <?php if($user->attribution == "Formateur"){echo "selected";} ?> value="Formateur">Formateur</option>
							<option <?php if($user->attribution == "Formatrice"){echo "selected";} ?> value="Formatrice">Formatrice</option>
							<option <?php if($user->attribution == "Recruteur"){echo "selected";} ?> value="Recruteur">Recruteur</option>
							<option <?php if($user->attribution == "Recruteuse"){echo "selected";} ?> value="Recruteuse">Recruteuse</option>
							<option <?php if($user->attribution == "Formateur/Recruteur"){echo "selected";} ?> value="Formateur/Recruteur">Formateur/Recruteur</option>
							<option <?php if($user->attribution == "Formatrice/Recruteuse"){echo "selected";} ?> value="Formatrice/Recruteuse">Formatrice/Recruteuse</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button id="submit" type="submit" class="btn btn-primary">Modifier</button>
		</div>
	</form>
</div>

<?php require('../inc/footer.php'); ?>
<script type="text/javascript">
	$( function () {

		$( '.select2' ).select2()

		$valid = true;
		$( "#email" ).hide();
		$( "#usernamespan" ).hide();

		$( "#username" ).keyup( function () {

			if ( !$( "#username" ).val().match( /^\w+([\.-]?\w+)*\s\w+([\.-]?\w+)*$/ ) ) {

				$( "#usernamediv" ).addClass( "has-error" );
				$( "#usernamediv" ).removeClass( "has-success" );
				$( "#usernamespan" ).fadeIn();
				$valid = false;

			} else {

				$( "#usernamediv" ).addClass( "has-success" );
				$( "#usernamediv" ).removeClass( "has-error" );
				$( "#usernamespan" ).fadeOut();
				$valid = true;

			}

		} );

		$( "#mail" ).keyup( function () {

			if ( $( "#mail" ).val() == "" ) {

				$( "#maildiv" ).addClass( "has-error" );
				$valid = false;

			}

			if ( !$( "#mail" ).val().match( /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/ ) ) {

				$( "#maildiv" ).addClass( "has-error" );
				$( "#maildiv" ).removeClass( "has-success" );
				$( "#email" ).fadeIn();
				$valid = false;

			} else {

				$( "#maildiv" ).addClass( "has-success" );
				$( "#maildiv" ).removeClass( "has-error" );
				$( "#email" ).fadeOut();
				$valid = true;

			}

		} );

		$( "#submit" ).click( function () {

			return $valid;

		} );

	} );
</script>