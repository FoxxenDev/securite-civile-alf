<?php

require( "../inc/header.php" );
require_once( "../inc/bdd.php" );

$req = $bdd->query( "SELECT user.id, user.username, user.rank, user.rank_sc_id, user.rank_meca_id, user.rank_sru_id, user.attribution, user.advert_id, user.activite, user.up_at, user.formation_base, user.formation_pilote, user.formation_commandement, rank_sc.rank_sc_name, rank_sc.rank_sc_color, rank_meca.rank_meca_name, rank_meca.rank_meca_color, rank_sru.rank_sru_name, rank_sru.rank_sru_color, advert.type, advert.color, advert.date_end AS date_end_advert, advert.date_start AS date_start_advert, absence.date_start, absence.date_end FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id LEFT JOIN advert ON user.advert_id=advert.id LEFT JOIN absence ON user.absence_id=absence.id ORDER BY user.rank_sc_id DESC" )

?>

<div class="row">
	<div class="col-xs-12">
		<table id="secouriste" class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>Nom</th>
					<th>Grade</th>
					<th>Spécialisation</th>
					<th>Attribution</th>
					<th>Avertissement</th>
					<th>Activité</th>
					<th>Date de passage</th>
					<th>Formation de base</th>
					<th>Formation pilotage</th>
					<th>Formation commandement</th>
					<?php if(isRank("Formateur") || isRank("État-Major") || isRank("Admin site")){echo "<th>Action</th>";} ?>
				</tr>
			</thead>
			<tbody>
				<?php
					while($result = $req->fetch()):
				
					$date_end_advert = strtotime($result->date_end_advert);
					$date_start_advert = strtotime($result->date_start_advert);
				
					$date_start = strtotime($result->date_start);
					$date_end = strtotime($result->date_end);
					$date_now = strtotime("now");
				
					$typeRank;
					$color;	

					$rankValue = explode(";", $result->rank);
					$result->rank = $rankValue;
	
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
					
					$formation_base = explode(":", $result->formation_base);
					$formation_pilote = explode(":", $result->formation_pilote);
					$formation_commandement = explode(":", $result->formation_commandement);
					$activite = explode(":", $result->activite);
				?>
				<tr>
					<th>
						<?= $result->username ?>
					</th>
					<th style="background-color: <?= $result->rank_sc_color ?>;">
						<?= $result->rank_sc_name ?>
					</th>
					<th style="background-color: #<?= $color ?>;">
						<?= $typeRank ?>
					</th>
					<th>
						<?= $result->attribution ?>
					</th>
					<?php if($date_now >= $date_start_advert && $date_now <= $date_end_advert && $result->type != "Viré"): ?>
					<th style="background-color: <?= $result->color ?>;">
						<?= $result->type ?>
					</th>
					<?php else: ?>
					<th>
					</th>
					<?php endif; ?>
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
						<?= date("d-m-Y", strtotime($result->up_at)) ?>
					</th>
					<th style="background-color: <?= $formation_base[1] ?>;">
						<?= $formation_base[0] ?>
					</th>
					<th style="background-color: <?= $formation_pilote[1] ?>;<?php if($formation_pilote[0] == "Aucune"){echo'color: #434343;';} ?>">
						<?= $formation_pilote[0] ?>
					</th>
					<th style="background-color: <?= $formation_commandement[1] ?>;<?php if($formation_commandement[0] == "Aucune"){echo'color: #434343;';} ?>">
						<?= $formation_commandement[0] ?>
					</th>
					<?php if(isRank("Formateur") || isRank("État-Major") || isRank("Admin site")): ?>
						<th>
							<a href="form_edit_secouriste.php?id=<?= $result->id ?>"><i class="fa fa-pencil" ></i></a>
						</th>
					<?php endif; ?>
				</tr>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
	<!-- /.col -->
</div>
<!-- /.row -->

<?php require("../inc/footer.php"); ?>
<!-- page script -->
<script>
  $(function () {
    $('#secouriste').DataTable({
      'paging'      : false,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false
    })
  })
</script>
