<?php
if(session_status() === PHP_SESSION_NONE){session_start();}
require_once( "bdd.php" );
require_once( "functions.php" );
$idSess = 1;

$men = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mfr = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');

$req2 = $bdd->prepare( "SELECT slug, content, icon FROM notification WHERE user_id = ?" );
$req2->execute( [ $idSess ] );
$notification = $req2->rowCount();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Sécurité Civile</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="stylesheet" href="/secuv2/bower_components/bootstrap/dist/css/bootstrap.min.css">
	<!--Icone navigateur-->
	<link rel="icon" href="/secuv2/dist/img/logo.ico">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/secuv2/bower_components/font-awesome/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="/secuv2/bower_components/Ionicons/css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="/secuv2/dist/css/AdminLTE.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="/secuv2/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
	<!-- Date Picker -->
	<link rel="stylesheet" href="/secuv2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/secuv2/bower_components/select2/dist/css/select2.min.css">
	<!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect. -->
	<link rel="stylesheet" href="/secuv2/dist/css/skins/skin-green.min.css">

	<!-- Custom styles for this template -->
	<link href="/secuv2/dist/css/custom.css" rel="stylesheet">


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->

<body class="hold-transition skin-green sidebar-mini">
	<div class="wrapper">

		<!-- Main Header -->
		<header class="main-header">

			<!-- Logo -->
			<a href="/secuv2/index.php" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b>SC</b></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg"><img class="img-circle" src="/secuv2/dist/img/logo.png" height="40px"> Sécurité <b>Civile</b></span>
			</a>

			<!-- Header Navbar -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
			<!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<?php if(isConnected()): ?>
						<!-- Notifications Menu -->
						<li class="dropdown notifications-menu">
							<!-- Menu toggle button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							  <i class="fa fa-bell-o"></i>
							  <span class="label label-warning"><?= $notification ?> </span>
							</a>



							<ul class="dropdown-menu notification-dropdown">
								<li id="notifications_title" class="user-header header-custom">Vous avez
									<?= $notification ?> notifications</li>
								<li>
									<!-- Inner Menu: contains the notifications -->
									<ul class="menu" style="position: relative;">
										<?php if($notification != 0){ while($result = $req2->fetch()){ ?>
										<li>
											<!-- start notification -->
											<a href="<?= $result->slug ?>">
                      							<i class="fa fa-<?= $result->icon ?>"></i> <?= $result->content ?>
											</a>
										</li>
										<!-- end notification -->
										<?php }}else{ ?>
										<li><a>Aucune notifications</a>
										</li>
										<?php } ?>
									</ul>
								</li>
								<li class="footer"><a href="/secuv2/suppNotification.php?id=<?= $idSess ?>&pagebefore=<?= $_SERVER['REQUEST_URI']; ?>">Supprimer</a>
								</li>
							</ul>
						</li>
						<!-- User Account Menu -->
						<li class="dropdown user user-menu">
							<!-- Menu Toggle Button -->
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<!-- The user image in the navbar-->
								<img src="/secuv2/dist/img/avatar5.png" class="user-image" alt="User Image">
								<!-- hidden-xs hides the username on small devices so only the image appears. -->
								<span class="hidden-xs"><?= $_SESSION["auth"]->username ?></span>
							</a>
							<ul class="dropdown-menu">
								<!-- The user image in the menu -->
								<li class="user-header">
									<img src="/secuv2/dist/img/avatar5.png" class="img-circle" alt="User Image">

									<p>
										<?= $_SESSION["auth"]->username ?> - <?= $_SESSION["auth"]->rank_sc_name ?>
										<small>Inscrit depuis <?= str_replace($men, $mfr, date("F Y",strtotime($_SESSION["auth"]->register_at))) ?></small>
									</p>
								</li>
								<!-- Menu Body -->
								<li class="user-body">
									<p class="text-muted text-center">
										<?php foreach($_SESSION["auth"]->rank as $rank): ?>
											<span class="label label-default" style="margin: 2px; color: #fff; background-color: <?= rankColor($rank) ?>; font-size: 12px; display: inline-block!important; padding: .3em .6em .3em!important;"><?= $rank ?></span>
										<?php endforeach; ?>
									</p>
								</li>
								<!-- Menu Footer-->
								<li class="user-footer">
									<div class="pull-left">
										<a href="/secuv2/profile.php" class="btn btn-default btn-flat">Profile</a>
									</div>
									<div class="pull-right">
										<a href="/secuv2/logout.php" class="btn btn-default btn-flat">Se déconnecter</a>
									</div>
								</li>
							</ul>
						</li>
						<?php else: ?>
						<li class="dropdown user user-menu">
  							<a class="btn" href="/secuv2/login.php" style="border: none;">Se connecter</a>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</nav>
		</header>
		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">

			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">

				<?php if(isConnected()): ?>
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel">
					<div class="pull-left image">
						<img src="/secuv2/dist/img/avatar5.png" class="img-circle" alt="User Image">
					</div>
					<div class="pull-left info">
						<p><?= $_SESSION["auth"]->username ?></p>
						<!-- Status -->
						<a href="#"><i class="fa fa-circle text-success"></i> En ligne</a>
					</div>
				</div>
				<?php endif; ?>

				<!-- Sidebar Menu -->
				<ul class="sidebar-menu" data-widget="tree">
					<?php if(!isConnected()): ?>
						<li style="margin-top: 10px;"><a href="/secuv2/login.php"><i class="fa fa-lock"></i> <span>Se connecter</span></a>
						</li>
						<hr style="margin-top: 10px !important; margin-bottom: 10px ">
					<?php endif; ?>
					<li><a href="/secuv2/index.php"><i class="fa fa-home"></i> <span>Accueil</span></a>
					</li>
					<hr style="margin-top: 10px !important; margin-bottom: 10px ">
					<!-- Optionally, you can add icons to the links -->
					<li class="treeview">
						<a href="#"><i class="fa fa-globe"></i> <span>Secouriste</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="/secuv2/secouriste/liste_secouriste.php"><i class="fa fa-user"></i> Effectif</a>
							</li>
							<li><a href="/secuv2/secouriste/liste_absence.php"><i class="fa fa-circle-o"></i> Absences</a>
							</li>
							<li><a href="/secuv2/secouriste/form_absence.php"><i class="fa fa-circle-o"></i> Déclarer une absence</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><i class="fa fa-graduation-cap"></i> <span>Formation</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="/secuv2/secouriste/form_demande_formation.php"><i class="fa fa-bookmark"></i> Demande de formation</a>
							</li>
							<li><a href="/secuv2/secouriste/liste_demande_formation.php"><i class="fa fa-list-ul"></i> Liste des demandes</a>
							</li>
							<li><a href="/secuv2/secouriste/formation_base.php"><i class="fa fa-male"></i> Formation de base</a>
							</li>
							<li><a href="/secuv2/secouriste/formation_pilotage.php"><i class="fa fa-plane"></i> Formation pilotage</a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><i class="fa fa-wrench"></i> <span>Mécanicien</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="#"><i class="fa fa-user"></i><span style="margin-left: 10px;">Effectif</span></a>
							</li>
							<li><a href="/secuv2/mecanicien/facture.php"><i class="fa fa-file-text"></i><span style="margin-left: 10px;">Factures</span></a>
							<li><a href="#"><i class="fa fa-file-text"></i><span style="margin-left: 10px;">Document Technique</span></a>
							</li>
						</ul>
					</li>
					<li class="treeview">
						<a href="#"><i class="fa fa-user-md"></i> <span>S.R.U</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="#"><i class="fa fa-user"></i>Effectif</a>
							</li>
						</ul>
					</li>
					<hr style="margin-top: 10px !important; margin-bottom: 10px ">
					<li class="treeview">
						<a href="#"><i class="fa fa-briefcase"></i> <span>Administration</span>
							<span class="pull-right-container">
								<i class="fa fa-angle-left pull-right"></i>
							</span>
						</a>
						<ul class="treeview-menu">
							<li><a href="/secuv2/admin/liste_membre.php"><i class="fa fa-user"></i> Membres</a>
							</li>
							<li><a href="#"><i class="fa fa-angle-double-up"></i> Grades</a>
							</li>
							<li><a href="/secuv2/admin/liste_advert.php"><i class="fa fa-gavel"></i> Sanctions</a>
							</li>
						</ul>
					</li>
					<li><a href="/secuv2/debug.php"><i class="fa fa-gears"></i> <span>Debug</span></a>
				</ul>
				<!-- /.sidebar-menu -->
			</section>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">

			<!-- Main content -->
			<section class="content container-fluid">
				<?php if(isset($_SESSION['flash'])): ?>
				<?php foreach($_SESSION['flash'] as $type => $message): ?>
				<div  class="col-xs-11 col-sm-4 alert alert-<?= $type ?> alert-dismissible" role="alert" style="z-index: 10; display: inline-block; margin: 0px auto; right: 30px; position: fixed;" role="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<?= $message; ?>
				</div>
				<?php endforeach; ?>
				<?php unset($_SESSION['flash']); ?>
				<?php endif; ?>