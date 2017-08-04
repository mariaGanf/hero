<?php
require_once('autoloader.php');
spl_autoload_register('Autoloader::loader');

$orderus = new Fighter(
    'Orderus',
    mt_rand(70, 100),
    mt_rand(70, 80),
    mt_rand(45, 55),
    mt_rand(40, 50),
    mt_rand(10, 30)
);

$beast = new Fighter(
    'WildBeast',
    mt_rand(60, 90),
    mt_rand(60, 90),
    mt_rand(40, 60),
    mt_rand(40, 60),
    mt_rand(25, 45)
);

$printer = new Printer();
$skills = new Skills($orderus, $printer);
$battle = new Battle($orderus, $beast, $skills, 20);
$battle->battle();

?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hero</title>
</head>

<body>
<div class="container">

</div>

</body>
</html>
