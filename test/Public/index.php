<?php

session_start();

require_once __DIR__ . '/../App/Controller/AntenneController.php';
require_once __DIR__ . '/../App/Controller/UserController.php';
require_once __DIR__ . '/../App/Controller/HomeController.php';
require_once __DIR__ . '/../App/Controller/FormController.php';

$action = $_GET['action'] ?? 'home';
$id = $_GET['id'] ?? null;

if ($action === 'users') {
    $controller = new UserController();
    $role = $_GET['role'] ?? 'admin';
    $antenneId = isset($_GET['antenne_id']) ? (int) $_GET['antenne_id'] : null;
    $controller->index($role, $antenneId);
} elseif ($action === 'antennes') {
    $controller = new AntenneController();
    $controller->index();
} elseif ($action === 'show' && $id) {
    $controller = new AntenneController();
    $controller->show($id);
} elseif ($action === 'adhesion') {
    $controller = new FormController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->adhesion_checkout();
    } else {
        $controller->adhesion();
    }
} elseif ($action === 'adhesion_success') {
    $controller = new FormController();
    $controller->adhesion_success();
} elseif ($action === 'adhesion_cancel') {
    $controller = new FormController();
    $controller->adhesion_cancel();
} elseif ($action === 'don') {
    $controller = new FormController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->don_submit();
    } else {
        $controller->don();
    }
} else {
    $controller = new HomeController();
    $controller->index();
}