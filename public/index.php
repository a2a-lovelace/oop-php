<?php
// Говорим, что нам нужны эти классы из папки "классы"
require_once '../classes/bdConnection.php';
require_once '../classes/User.php';
require_once '../classes/Department.php';

// Сюда надо бы добавить применение этих классов
$connection = bdConnection::getInstance();
$user = new User();
$department = new Department();
?>
