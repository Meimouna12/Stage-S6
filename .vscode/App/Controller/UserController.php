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
}