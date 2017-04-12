<?php
$verif      = false;
$page       = '';
$page_array = ['index'];

foreach ($page_array as $element){
    $verif = ($_GET['page'] === $element) ? true : $verif;
}

$page = ($verif === true) ? $_GET['page'] . '.php' : 'index.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php include '../src/view/partials/head.php'; ?>
</head>

<body>

<header>
    <?php include '../src/view/partials/header.php'; ?>
</header>

<main>
    <?php include '../src/view/' . $page; ?>
</main>

<footer>
    <?php include '../src/view/partials/footer.php'; ?>
</footer>


<?php include '../src/view/partials/foot.php'; ?>
</body>
</html>
