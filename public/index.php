<?php
$verif      = false;
$page       = '';
$page_array = ['index'];

foreach ($page_array as $element){
    $verif = ($_GET['page'] === $element) ? true : $verif;
}
$page = ($verif === true) ? $_GET['page'] : 'index';
$page .= '.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include('partials/head.php'); ?>
</head>
<body>
<header>
    <?php include('partials/header.php'); ?>
</header>

<main>
    <?php include('page/' . $page); ?>
</main>

<footer>
    <?php include('partials/footer.php'); ?>
</footer>

<?php include('partials/foot.php'); ?>
</body>
</html>
