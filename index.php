<?php

include('functions.php');

$db = include('start.php');

$users = $db->getAll('users');

include('index.view.php');
?>

