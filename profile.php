<?php require_once ("inc/header.php"); ?>
    <h2>Bienvenue sur votre profile <?= $_SESSION["auth"]->username ?></h2>
    <p>
        Votre email : <?= $_SESSION["auth"]->email ?><br>
        Votre grade : <?= $_SESSION["auth"]->rank_sc_name ?><br>
        <?php if ($_SESSION["auth"]->rank_meca_name): ?>
            Votre grade mécanicien : <?= $_SESSION["auth"]->rank_meca_name ?>
        <?php endif; ?>
        <?php if ($_SESSION["auth"]->rank_sru_name): ?>
            Votre grade S.R.U : <?= $_SESSION["auth"]->rank_sru_name ?>
        <?php endif; ?>
    </p>
<?php require_once ("inc/footer.php"); ?>