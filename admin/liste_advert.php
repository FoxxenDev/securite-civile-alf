<?php

require_once( "../inc/functions.php" );

if ( !isRank( "État-Major" ) && !isRank( "Admin site" ) ) {
	header( "Location: ../index.php" );
	$_SESSION[ 'flash' ][ 'danger' ] = "Vous n'avez pas l'autorisation d'accéder à cette page !";
	exit();
}

require( "../inc/bdd.php" );

if ( isset( $_GET ) && !empty( $_GET ) ) {
	if ( $_GET[ 'action' ] == "supp" && $_GET[ 'id' ] != null ) {
		$bdd->prepare( "DELETE FROM advert WHERE id=?" )->execute( [ $_GET[ 'id' ] ] );
		header( 'Location: liste_advert.php' );
		exit();
	}
}

require( "../inc/header.php" );

$req = $bdd->query( "SELECT advert.*, user.username FROM advert LEFT JOIN user ON user.id=advert.user_id_to ORDER BY advert.id DESC" );

?>

<div class="modal fade" id="suppmembermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Supprimer une sanction ?</h4>
      </div>
      <div class="modal-body">
        <form>
		  <div class="form-group">
            <label for="recipient-name" class="control-label">Êtes vous sûr de vouloir supprimer la sanction concernant le membre suivant</label>
            <input type="text" disabled class="form-control" id="recipient-name">
          </div>
          <div class="form-group">
            <label for="recipient-raison" class="control-label">Pour la raison suivante</label>
            <input type="text" disabled class="form-control" id="recipient-raison">
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
					<th>N°</th>
					<th>Nom</th>
					<th>Donner par</th>
					<th>Donner le</th>
					<th>Jusqu'au</th>
					<th>Type</th>
					<th>Raison</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php 				
					while($result = $req->fetch()):
				
					$req1 = $bdd->prepare("SELECT username FROM user WHERE id = ?");
					$req1->execute([$result->user_id_from]);
					$result1 = $req1->fetch();
				
					$date_start = strtotime($result->date_start);
					$date_end = strtotime($result->date_end);
					$date_now = strtotime("now");
				?>
				<tr>
					<th>
						<?= $result->id ?>
					</th>
					<th>
						<?= $result->username ?>
					</th>
					<th>
						<?= $result1->username ?>
					</th>					
					<th>
						<?= date("d-m-Y", $date_start) ?>
					</th>
					<th style="background-color: <?php if($date_now >= $date_end){echo'rgb(0, 128, 0)';} ?>;">
						<?= date("d-m-Y", $date_end) ?>
					</th>
					<th style="background-color: <?= $result->color ?>">
						<?= $result->type ?>
					</th>
					<th>
						<?= $result->raison ?>
					</th>
					<th>
						<?php if($result1->username == $_SESSION["auth"]->username || isRank("Admin site")): ?>
							<a href="form_edit_advert.php?id=<?= $result->id ?>"><i class="fa fa-pencil" ></i></a> | <i  data-toggle="modal" data-target="#suppmembermodal" data-memberid="<?= $result->id ?>" data-raison="<?= $result->raison ?>" data-membername="<?= $result->username ?>" class="fa fa-remove" style="color: #FF0000"></i>
						<?php endif; ?>
					</th>
				</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<a href = "form_add_advert.php" class = "btn btn-primary" > Ajouter une sanction </a>

<?php require("../inc/footer.php"); ?>

<!-- page script -->
<script type="text/javascript">
	$('#suppmembermodal').on('show.bs.modal', function (event) {
	  var button = $(event.relatedTarget) // Button that triggered the modal
	  var membername = button.data('membername') // Extract info from data-* attributes
	  var raison = button.data('raison') // Extract info from data-* attributes
	  var memberid = button.data('memberid') // Extract info from data-* attributes
	  console.log(raison)
	  console.log(membername)
	  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
	  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
	  var modal = $(this)
	  modal.find('.modal-body input#recipient-raison').val(raison)
	  modal.find('.modal-body input#recipient-name').val(membername)
	  document.forms['formemberid'].action = 'liste_advert.php?action=supp&id='+memberid
	})
	
	$( function () {
		$( '#example1' ).DataTable()
	} )
</script>