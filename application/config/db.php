<?php
switch ($_SERVER['HTTP_HOST']) {
    //case 'abram-world.loc' : return require(__DIR__ . '/db-local.php');
    case 'localhost' : return require(__DIR__ . '/db-local.php');
    case 'x67582pz.bget.ru' : return require(__DIR__ . '/db-beget.php');
}