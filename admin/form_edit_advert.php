<?php

require_once( "../inc/functions.php" );

if ( !isRank( "État-Major" ) && !isRank( "Admin site" ) ) {
	header( "Location: ../index.php" );
	$_SESSION[ 'flash' ][ 'danger' ] = "Vous n'avez pas l'autorisation d'accéder à cette page !";
	exit();
}

if ( !isset( $_GET[ 'id' ] ) || empty( $_GET[ "id" ] ) ) {
	session_start();
	$_SESSION[ 'flash' ]->danger = "Impossible de modifier les informations de cette sanction";
	header( "Location: liste_advert.php" );
	exit();

}

if ( isset( $_POST ) && !empty( $_POST ) ) {
	require_once( "../inc/bdd.php" );
	session_start();
	
	$color = explode(":", $_POST["type"])[1];
	$type = explode(":", $_POST["type"])[0];

	$req1 = $bdd->prepare( "UPDATE advert SET user_id_to = ?, date_end = ?, type = ?, color = ?, raison = ? WHERE id = ?" );
	$req1->execute( [ $_POST[ 'user_to' ], $_POST[ 'date_end' ], $type, $color, $_POST[ 'raison' ], $_GET[ 'id' ] ] );
	$_SESSION[ 'flash' ]->success = "La sanction à bien été modifiée";
	header( "Location: liste_advert.php" );
	exit();

}

require_once( "../inc/header.php" );

$req = $bdd->prepare( "SELECT * FROM advert WHERE id = ?" );
$req->execute( [ $_GET[ "id" ] ] );
$user = $req->fetch();

$req2 = $bdd->query("SELECT id, username FROM user");

?>

<div class="box">
	<form method="post">
		<div class="box-header with-border">
			<h3 class="box-title">Éditer une sanction</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
			<!-- text input -->
			<div class="row">
				<div class="col-sm-4">
					<div class="form-group">
						<label for="user_to">Donné à</label>
						<select id="user_to" name="user_to" class="form-control">
							<?php while($result = $req2->fetch()): ?>
							<option <?php if($user->user_id_to == $result->id){echo"selected";} ?> value="<?= $result->id ?>"><?= $result->username ?></option>
							<?php endwhile; ?>
						</select>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group date">
						<label>Date de fin</label>
						<input class="form-control" type="text" id="date_end" name="date_end" data-inputmask="'alias': 'yyyy-mm-dd'" value="<?= date("Y-m-d", strtotime($user->date_end)) ?>" data-mask="" title="Veuillez entrer une date valide !" required="">
					</div>
				</div>
				<div class="col-sm-4">
					<div class="form-group">
						<label for="type">Type de sanction</label>
						<select class="form-control" name="type" id="type">
							<option <?php if($user->type == "Avertissement 1"){echo "selected";} ?> style="background-color: #ff9900;"  value="Avertissement 1:#ff9900">Avertissement 1</option>
							<option <?php if($user->type == "Avertissement 2"){echo "selected";} ?> style="background-color: #ff0000;"  value="Avertissement 2:#ff0000">Avertissement 2</option>
							<option <?php if($user->type == "Dégrade"){echo "selected";} ?> style="background-color: #cc4125;"  value="Dégrade:#cc4125">Dégrade</option>
							<option <?php if($user->type == "Mise à pieds"){echo "selected";} ?> style="background-color: #990000;"  value="Mise à pieds:#990000">Mise à pieds</option>
							<option <?php if($user->type == "Viré"){echo "selected";} ?> style="background-color: #660000;"  value="Viré:#660000">Viré</option>
						</select>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label>Raison</label>
				<textarea name="raison" class="form-control" rows="3"><?= $user->raison ?></textarea>
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
		
		$( "#date_end" ).datepicker({
			format: "yyyy-mm-dd",
			weekStart: 1,
			language: "fr",
			orientation: "bottom",
			autoclose: true,
			startDate: new Date()
		});

		$( '.select2' ).select2()

	} );
</script>