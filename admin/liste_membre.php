<?php

require_once("../inc/functions.php");

if(!isRank("État-Major") && !isRank("Admin site")){header("Location: ../index.php");$_SESSION['flash']['danger'] = "Vous n'avez pas l'autorisation d'accéder à cette page !";exit();}

require( "../inc/bdd.php" );

if ( isset( $_GET ) && !empty( $_GET ) ) {
	if ( $_GET[ 'action' ] == "supp" && $_GET[ 'id' ] != null ) {
		$bdd->prepare( "DELETE FROM user WHERE id=?" )->execute( [ $_GET[ 'id' ] ] );
		header( 'Location: liste_membre.php' );
		exit();
	}
}

require( "../inc/header.php" );

$req = $bdd->query( "SELECT user.*, rank_sc.*, rank_meca.*, rank_sru.*, advert.type, advert.color, advert.date_end AS date_end_advert, advert.date_start AS date_start_advert, absence.date_start, absence.date_end FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id LEFT JOIN advert ON user.advert_id=advert.id LEFT JOIN absence ON user.absence_id=absence.id ORDER BY user.id ASC" );

?>

<div class="modal fade" id="suppmembermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="exampleModalLabel">Supprimer un membre ?</h4>
			</div>
			<div class="modal-body">
				<form>
					<div class="form-group">
						<label for="recipient-name" class="control-label">Êtes vous sûr de vouloir supprimer le membre</label>
						<input type="text" disabled class="form-control" id="recipient-name">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<form name="formemberid" action="" method="post">
					<button type="button" class="btn btn-success" onClick="document.forms['formemberid'].submit()">Oui</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Non</button>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nom</th>
							<th>Mail</th>
							<th>Secouriste</th>
							<th>Spécialisation</th>
							<th>Activité</th>
							<th>Attribution</th>
							<th>Sanction</th>
							<th>Date de recrutement</th>
							<th>Recruter par</th>
							<th>Date de passage</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php while($result = $req->fetch()){ 
							$req1 = $bdd->prepare("SELECT username FROM user WHERE id = ?");
							$req1->execute([$result->register_by_id]);
							$result1 = $req1->fetch();
							
							$date_end_advert = strtotime($result->date_end_advert);
							$date_start_advert = strtotime($result->date_start_advert);
	
							$date_start = strtotime($result->date_start);
							$date_end = strtotime($result->date_end);
							$date_now = strtotime("now");
	
							$typeRank;
							$color;	
	
							$rankValue = explode(";", $result->rank);
							$result->rank = $rankValue;
	
							$activite = explode(":", $result->activite);
	
							if(in_array("Mécanicien", $result->rank)){
								$typeRank = "Mécanicien";
								$color = "e67e22";
							}elseif(in_array("S.R.U", $result->rank)){
								$typeRank = "S.R.U";
								$color = "9b59b6";
							}else{
								$typeRank = "Aucun";
								$color = "";
							}
						?>
						<tr>
							<th>
								<?= $result->id ?>
							</th>
							<th>
								<?= $result->username ?>
							</th>
							<th>
								<?= $result->email ?>
							</th>
							<th style="background-color: <?= $result->rank_sc_color ?>;">
								<?= $result->rank_sc_name ?>
							</th>
							<th style="background-color: #<?= $color ?>;">
								<?= $typeRank ?>
							</th>
							<?php if($date_now >= $date_start && $date_now <= $date_end): ?>
							<th style="background-color: rgb(0, 128, 0);">
								En pause
							</th>
							<?php else: ?>
							<th style="background-color: <?= $activite[1] ?>;">
								<?= $activite[0] ?>
							</th>
							<?php endif; ?>
							<th>
								<?= $result->attribution ?>
							</th>
							<?php if($date_now >= $date_start_advert && $date_now <= $date_end_advert && $result->type != "Dégrade" && $result->type != "Viré"): ?>
							<th style="background-color: <?= $result->color ?>;">
								<?= $result->type ?>
							</th>
							<?php else: ?>
							<th>
							</th>
							<?php endif; ?>
							<th>
								<?= date("d-m-Y", strtotime($result->register_at)) ?>
							</th>
							<th>
								<?= $result1->username ?>
							</th>
							<th>
								<?= date("d-m-Y", strtotime($result->up_at)) ?>
							</th>							
							<th>
								<a href="form_edit_member.php?id=<?= $result->id ?>"><i class="fa fa-pencil" ></i></a> | <i data-toggle="modal" data-target="#suppmembermodal" data-memberid="<?= $result->id ?>" data-membername="<?= $result->username ?>" class="fa fa-remove" style="color: #FF0000"></i>
							</th>
						</tr>
						<?php }; ?>
					</tbody>
				</table>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<a href = "form_add_member.php" class = "btn btn-primary" > Recruter un nouveau membre </a>

<?php require("../inc/footer.php"); ?>

<!-- page script -->
<script>
	$( '#suppmembermodal' ).on( 'show.bs.modal', function ( event ) {
		var button = $( event.relatedTarget ) // Button that triggered the modal
		var membername = button.data( 'membername' ) // Extract info from data-* attributes
		var memberid = button.data( 'memberid' ) // Extract info from data-* attributes
			// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
			// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		var modal = $( this )
		modal.find( '.modal-body input' ).val( membername )
		document.forms[ 'formemberid' ].action = 'liste_membre.php?action=supp&id=' + memberid
	} )

	$( function () {
		$( '#example1' ).DataTable()
	} )
</script>