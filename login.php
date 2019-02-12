<?php

require_once( 'inc/bdd.php' );
require_once( 'inc/functions.php' );
if(session_status() === PHP_SESSION_NONE){session_start();}

if ( !empty( $_POST ) && !empty( $_POST[ 'username' ] ) && !empty( $_POST[ 'password' ] ) ) {

	$req = $bdd->prepare( "SELECT user.id, user.password, user.username, user.email, user.avatar, user.rank, user.register_at, rank_sc.rank_sc_name, rank_meca.rank_meca_name, rank_meca.rank_meca_level, rank_sru.rank_sru_name, rank_sru.rank_sru_level FROM user LEFT JOIN rank_sc ON user.rank_sc_id=rank_sc.rank_sc_id LEFT JOIN rank_meca ON user.rank_meca_id=rank_meca.rank_meca_id LEFT JOIN rank_sru ON user.rank_sru_id=rank_sru.rank_sru_id WHERE (user.username = :username OR user.email = :username)" );
	$req->execute( [ 'username' => $_POST[ 'username' ] ] );
	$user = $req->fetch();
	$userexist = $req->rowCount();

	if ( $userexist == 1 ) {

		if ( password_verify( $_POST[ 'password' ], $user->password ) ) {
			
			$rankValue = explode(";", $user->rank);
			
			$count = count($rankValue);
			unset($rankValue[$count-1]);
			
			$user->rank = $rankValue;
			
			$_SESSION[ 'auth' ] = $user;
			$_SESSION[ 'flash' ][ 'success' ] = "Vous êtes maintenant connecté";

			if(isset($_POST["remember"]) && $_POST["remember"] == 1){
			    $token = token(150);
			    $bdd->prepare("UPDATE user SET remember_token = ? WHERE id = ?")->execute([$token, $user->id]);
			    setcookie("remember", $user->id."==".$token . sha1($user->id . "scalf"), time() + 60 * 60 * 24 * 7);
            }

			header( "Location: /secuv2/index.php" );
			exit();

		} else {
			$_SESSION[ 'flash' ][ 'danger' ] = "Mauvais utilisateur ou mauvais mot de passe";
			header( "Location: /secuv2/index.php" );
		}

	} else {
		$_SESSION[ 'flash' ][ 'danger' ] = "Mauvais utilisateur ou mauvais mot de passe";
		header( "Location: /secuv2/index.php" );
	}

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sécurité Civile | Connexion</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!--Icone navigateur-->
	<link rel="icon" href="/secuv2/dist/img/logo.ico">
	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="/secuv2/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/secuv2/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="/secuv2/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/secuv2/dist/css/AdminLTE.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="/secuv2/plugins/iCheck/square/blue.css">
	<style>
		body {
			background-image: url(dist/img/bglogin.jpg)!important;
			background-repeat: no-repeat!important;
			background-position: 50% 50%!important;
			height: auto!important;
		}
	</style>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
	<?php if(isset($_SESSION['flash'])): ?>
	<?php foreach($_SESSION['flash'] as $type => $message): ?>
	<div class="col-xs-11 col-sm-4 alert alert-<?= $type ?> alert-dismissible" role="alert" style="display: inline-block; margin: 0px auto; right: 30px; position: fixed;" role="alert">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<?= $message; ?>
	</div>
	<?php endforeach; ?>
	<?php unset($_SESSION['flash']); ?>
	<?php endif; ?>
	<div class="login-box">
		<div class="login-logo">
			<a href="/secuv2/index.php">Sécurité <b>Civile</b></a>
		</div>
		<!-- /.login-logo -->
		<div class="login-box-body">
			<p class="login-box-msg">Se connecter</p>

			<form action="/secuv2/login.php" method="post">
				<div class="form-group has-feedback">
					<input name="username" type="text" class="form-control" placeholder="Nom d'utilisateur / Adresse mail">
					<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
				</div>
				<div class="form-group has-feedback">
					<input name="password" type="password" class="form-control" placeholder="Mot de passe">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				</div>
				<div class="row">
					<div class="col-xs-8">
						<div class="checkbox icheck">
							<label>
								<input type="checkbox" name="remember" value="1"> Se souvenir de moi
							</label>

						</div>
					</div>
					<!-- /.col -->
					<div class="col-xs-4">
						<button type="submit" class="btn btn-primary btn-block btn-flat">Connexion</button>
					</div>
					<!-- /.col -->
				</div>
			</form>

		</div>
		<!-- /.login-box-body -->
	</div>
	<!-- /.login-box -->

	<!-- jQuery 3 -->
	<script src="/secuv2/bower_components/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap 3.3.7 -->
	<script src="/secuv2/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- iCheck -->
	<script src="/secuv2/plugins/iCheck/icheck.min.js"></script>
	<script>
		$( function () {
			$( 'input' ).iCheck( {
				checkboxClass: 'icheckbox_square-blue',
				radioClass: 'iradio_square-blue',
				increaseArea: '20%' /* optional */
			} );
		} );
	</script>
</body>
</html>