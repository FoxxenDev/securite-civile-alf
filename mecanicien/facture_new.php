<?php

require('header.php');

$req = $bdd->query("SELECT username FROM user WHERE rank_meca_id > 0");
?>

<h2>Facturer un client</h2>
<br>
<h4>Informations générales</h4>
<br><br>

<form method="post" action="facture_create.php">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="mecanicien">Mécanicien</label>
				<select name="mecanicien" class="form-control" id="mecanicien">
					<?php while($result = $req->fetch()){ ?>
						<option value="<?php $nom=explode(" ", $result->username); echo $nom[1] ?>"><?= $result->username ?></option>
					<?php }; ?>
				</select>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="client">Client</label>
				<input name="client" id="client" type="text" placeholder="Ex : Odor Noël" class="form-control">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="adresse">Adresse</label>
				<input name="adresse" id="adresse" type="text" placeholder="Ex : 19 Rue des pommiers | 1 place d'Athira" class="form-control">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<label for="ville">Ville</label>
				<input name="ville" id="ville" type="text" placeholder="Ex : 036 131 Kavala" class="form-control">
			</div>
		</div>
	</div>
	
	<br><hr><br>
	
	<h4>Actions</h4>
	<h6>Le prix TTC est calculé automatiquement</h6><br>
	
	<div id="row0" class="row">
		<div class="col-sm-9">
			<div class="form-group">
				<label for="description">Description</label>
				<input name="description0" id="description" type="text" placeholder="Ex : Pneu avant gauche | Nouveau démarreur" class="form-control">
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<label for="prix_ht">Prix HT</label>
				<div class="input-group">
					<input name="prix_ht0" id="prix_ht" type="text" placeholder="0000" class="form-control" aria-describedby="basic-addon">
					<span class="input-group-addon" id="basic-addon">€</span>
				</div>
			</div>
		</div>	
		<div class="col-sm-1">
			<div class="form-group">
				<a onclick="ajouterChamps()"><img src="img/plus.png" width="32px" height="32px"></a>
			</div>
		</div>
	</div>
	
	<button type="submit" id="submit" class="btn btn-success">Facturer</button>
</form>

<?php
require('footer.php');

?>

<script type="text/javascript">
	
var i = 0;

function retirerChamps(){
	var removeObject = document.getElementById('row' + i);
	removeObject.remove();
	i--;
	var removeObjectBefore = document.getElementById('row' + i);
	if(i != 0){
	removeObjectBefore.lastElementChild.firstElementChild.firstElementChild.firstElementChild.removeAttribute("hidden");
	}
}
	
function ajouterChamps() {
    var original = document.getElementById('row' + i);
    var clone = original.cloneNode(true); // "deep" clone
    clone.id = "row" + ++i; // there can only be one element with an ID
	tempSubmit = document.getElementById("submit");
	tempButtonPlus = document.getElementById("plusbutton");	clone.lastElementChild.firstElementChild.firstElementChild.firstElementChild.src = "img/minus.png";
	clone.lastElementChild.firstElementChild.firstElementChild.setAttribute("onclick", "retirerChamps()");
	if(i != 1){
	original.lastElementChild.firstElementChild.firstElementChild.firstElementChild.setAttribute("hidden", "");
	}
	var ht = clone.firstElementChild.nextElementSibling.firstElementChild.lastElementChild.firstElementChild;
	var desc = clone.firstElementChild.firstElementChild.lastElementChild;
	ht.name = "prix_ht" + i;
	ht.value = "";
	desc.name = "description" + i;
	desc.value = "";
    original.parentNode.appendChild(clone);
	original.parentNode.appendChild(tempSubmit);
	
}
	
</script>