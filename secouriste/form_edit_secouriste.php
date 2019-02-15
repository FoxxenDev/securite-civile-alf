<?php

require_once( "../inc/functions.php" );

if ( !isRank( "Formateur" ) && !isRank( "État-Major" ) && !isRank( "Admin site" )) {
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

	$req1 = $bdd->prepare( "UPDATE user SET formation_base = ?, formation_pilote = ?, formation_commandement = ? WHERE id = ?" );
	$req1->execute( [ $_POST[ 'formation_base' ], $_POST[ 'formation_pilote' ], $_POST[ 'formation_commandement' ], $_GET[ 'id' ] ] );
	$_SESSION[ 'flash' ]->success = "L'utilisateur à bien été modifié";
	header( "Location: liste_secouriste.php" );
	exit();

}

require_once( "../inc/header.php" );

$req = $bdd->prepare( "SELECT formation_base, formation_pilote, formation_commandement FROM user WHERE id = ?" );
$req->execute( [ $_GET[ "id" ] ] );
$user = $req->fetch();

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
				<div class="col-sm-4">
					<div class="form-group">
						<label for="formation_base">Formation de base</label>
						<select name="formation_base" class="form-control" id="formation_base">
							<option style="background-color: #cc4125;" <?php if($user->formation_base == "En attente:#cc4125"){echo "selected";} ?> value="En attente:#cc4125">En attente</option>
							<option style="background-color: #6d9eeb;" <?php if($user->formation_base == "Validée:#6d9eeb"){echo "selected";} ?> value="Validée:#6d9eeb">Validée</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_base == "Formateur:#0b5394"){echo "selected";} ?> value="Formateur:#0b5394">Formateur</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_base == "Formatrice:#0b5394"){echo "selected";} ?> value="Formatrice:#0b5394">Formatrice</option>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="formation_pilote">Formation pilote</label>
						<select name="formation_pilote" class="form-control" id="formation_pilote">
							<option <?php if($user->formation_pilote == "Aucune:#ffffff"){echo "selected";} ?> value="Aucune:#ffffff">Aucune</option>
							<option style="background-color: #cc4125;" <?php if($user->formation_pilote == "En attente:#cc4125"){echo "selected";} ?> value="En attente:#cc4125">En attente</option>
							<option style="background-color: #6d9eeb;" <?php if($user->formation_pilote == "Validée:#6d9eeb"){echo "selected";} ?> value="Validée:#6d9eeb">Validée</option>
							<option style="background-color: #38761d;" <?php if($user->formation_pilote == "Maîtrisée:#38761d"){echo "selected";} ?> value="Maîtrisée:#38761d">Maîtrisée</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_pilote == "Formateur:#0b5394"){echo "selected";} ?> value="Formateur:#0b5394">Formateur</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_pilote == "Formatrice:#0b5394"){echo "selected";} ?> value="Formatrice:#0b5394">Formatrice</option>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="formation_commandement">Formation commandement</label>
						<select name="formation_commandement" class="form-control" id="formation_commandement">
							<option <?php if($user->formation_commandement == "Aucune:#ffffff"){echo "selected";} ?> value="Aucune:#ffffff">Aucune</option>
							<option style="background-color: #cc4125;" <?php if($user->formation_commandement == "En attente:#cc4125"){echo "selected";} ?> value="En attente:#cc4125">En attente</option>
							<option style="background-color: #6d9eeb;" <?php if($user->formation_commandement == "Validée:#6d9eeb"){echo "selected";} ?> value="Validée:#6d9eeb">Validée</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_commandement == "Formateur:#0b5394"){echo "selected";} ?> value="Formateur:#0b5394">Formateur</option>
							<option style="background-color: #0b5394;" <?php if($user->formation_commandement == "Formatrice:#0b5394"){echo "selected";} ?> value="Formatrice:#0b5394">Formatrice</option>
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

	} );
</script>