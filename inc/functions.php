<?php
if(session_status() === PHP_SESSION_NONE){session_start();}

function devAccess(){
	if(!isAdmin()){
		$_SESSION['flash']['danger'] = "Vous n'avez pas la permission d'accéder à cette page !";
		header("Location: index.php");
		exit();
	}else{
		return true;
	}
}

function rankColor($rank){
	switch ($rank) {
    case "Secouriste":
        return "#38761d";
        break;
    case "Mécanicien":
        return "#e67e22";
        break;
    case "S.R.U":
        return "#9b59b6";
        break;
    case "Formateur":
        return "#0b5394";
        break;
    case "Chef-Mécanicien":
        return "#e74c3c";
        break;
    case "Chef-S.R.U":
        return "#71368a";
        break;
    case "État-Major":
        return "#C70000";
        break;
    case "Admin site":
        return "#34495e";
        break;
	}
}

function isRank($rank){
	
	if(in_array($rank, $_SESSION["auth"]->rank)){
		return true;
	}
	
	return false;
	
}

function isConnected(){
	
	if(isset($_SESSION['auth'])){
		return true;
	}
	
	return false;
	
}

function token($lenght){
	
	$alphabet = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	return substr(str_shuffle(str_repeat($alphabet, $lenght)), 0, $lenght);
	
}

function postToDiscord($message){
	
	    $data = array("content" => $message);
	    $curl = curl_init("https://discordapp.com/api/webhooks/522527083407343616/3KcVbsjmM_D42OqH1FmHkE2fK0dqgM8mX28bKjthqm5oeodQo5iEODOimkAfQoRZICCR");
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    return curl_exec($curl);
	}