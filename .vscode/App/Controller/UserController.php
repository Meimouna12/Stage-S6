<?php

require_once __DIR__ . '/../../Core/controller.php';
require_once __DIR__ . '/../Model/User.php';

class UserController extends Controller {
    public function index($role = 'admin', $antenneId = null) {
        $model = new User();
        $users = $model->getVisibleUsers($role, $antenneId);

        $this->view('User/index', [
            'users' => $users,
            'currentRole' => $role,
            'currentAntenneId' => $antenneId,
        ]);
    }

   public function edit($id)
{
    global $pdo;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $sql = "UPDATE users SET
                    nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    role = :role
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':nom'     => $_POST['nom'],
            ':prenom'  => $_POST['prenom'],
            ':email'   => $_POST['email'],
            ':role'    => $_POST['role'],
            ':id'      => $id
        ]);

        header('Location: index.php?action=users');
        exit;
    }

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    require_once __DIR__ . '/../View/User/edit.php';
}

public function update()
{
    global $pdo;

    $id = (int) $_POST['id'];

    // Anciennes données
    $stmtOld = $pdo->prepare("
        SELECT *
        FROM users
        WHERE id = ?
    ");

    $stmtOld->execute([$id]);
    $old = $stmtOld->fetch(PDO::FETCH_ASSOC);

    // Nouvelles données
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $antenne_id = !empty($_POST['antenne_id'])
        ? (int) $_POST['antenne_id']
        : null;

    // Mise à jour
    $stmt = $pdo->prepare("
        UPDATE users
        SET
            nom = ?,
            prenom = ?,
            email = ?,
            role = ?,
            antenne_id = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $nom,
        $prenom,
        $email,
        $role,
        $antenne_id,
        $id
    ]);

    // Création du message de log
    $modifications = [];

    if ($old['nom'] != $nom) {
        $modifications[] =
            "Nom : {$old['nom']} → {$nom}";
    }

    if ($old['prenom'] != $prenom) {
        $modifications[] =
            "Prénom : {$old['prenom']} → {$prenom}";
    }

    if ($old['email'] != $email) {
        $modifications[] =
            "Email : {$old['email']} → {$email}";
    }

    if ($old['role'] != $role) {
        $modifications[] =
            "Rôle : {$old['role']} → {$role}";
    }

    if ($old['antenne_id'] != $antenne_id) {
        $modifications[] =
            "Antenne : {$old['antenne_id']} → {$antenne_id}";
    }

    // Enregistrer seulement s'il y a eu une modification
    if (!empty($modifications)) {

        $action = implode(' | ', $modifications);

        $log = $pdo->prepare("
            INSERT INTO logs_admin
            (admin_id, utilisateur_id, action)
            VALUES (?, ?, ?)
        ");

        $log->execute([
            $_SESSION['user']['id'],
            $id,
            $action
        ]);
    }

    header('Location: index.php?action=users');
    exit;
}

public function delete($id)
{
    global $pdo;

    $stmt = $pdo->prepare("
        SELECT nom, prenom, email
        FROM users
        WHERE id = ?
    ");
    $stmt->execute([$id]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $log = $pdo->prepare("
        INSERT INTO logs_admin
        (admin_id, utilisateur_id, action)
        VALUES (?, ?, ?)
    ");

    $log->execute([
        $_SESSION['user']['id'],
        $id,
        'Suppression de ' . $user['prenom'] . ' ' . $user['nom']
    ]);

    $stmt = $pdo->prepare("
        DELETE FROM users
        WHERE id = ?
    ");

    $stmt->execute([$id]);

    header('Location: index.php?action=users');
    exit;
}



}