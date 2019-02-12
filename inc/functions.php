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

function reconnectFromCookie(){
    if(isset($_COOKIE["remember"]) && ! isConnected()){
        require_once ("bdd.php");
        global $bdd;
        $rememberToken = $_COOKIE["remember"];
        $parts = explode("==", $rememberToken);
        $user_id = $parts[0];
        $req = $bdd->prepare("SELECT user.id, user.password, user.username, user.email, user.remember_token, user.rank, user.register_at, rank_sc.rank_sc_name, rank_meca.rank_meca_name, rank_meca.rank_meca_level, rank_sru.rank_sru_name, rank_sru.rank_sru_level FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id WHERE id = ?");
        $req->execute([$user_id]);
        $user = $req->fetch();
        if($user){
            $expected = $user_id."==".$user->remember_token . sha1($user_id . "scalf");
            if($expected == $rememberToken){
                if(session_status() === PHP_SESSION_NONE){session_start();}

                $rankValue = explode(";", $user->rank);

                $count = count($rankValue);
                unset($rankValue[$count-1]);

                $user->rank = $rankValue;

                $_SESSION["auth"] = $user;
                setcookie("remember", $rememberToken, time() + 60 * 60 * 24 * 7, "/", "securite-civile-alf.tk");
            }else{
                setcookie("remember", NULL, -1);
            }
        }else{
            setcookie("remember", NULL, -1);
        }
    }
}

function postToDiscord($message){
	
	    $data = array("content" => $message);
	    $curl = curl_init("https://discordapp.com/api/webhooks/522527083407343616/3KcVbsjmM_D42OqH1FmHkE2fK0dqgM8mX28bKjthqm5oeodQo5iEODOimkAfQoRZICCR");
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
	    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    return curl_exec($curl);
	}