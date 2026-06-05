<?php

require_once __DIR__ . '/../Model/User.php';

class AuthController
{
    public function login()
    {
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $userModel = new User();

            $user = $userModel->findByEmail(
                $_POST['email']
            );

            if (
                $user &&
                password_verify(
                    $_POST['password'],
                    $user['password']
                )
            )
            {
                $_SESSION['user'] = $user;

                header('Location: ?action=dashboard');
                exit;
            }

            $error = "Email ou mot de passe incorrect";
        }

        require_once __DIR__ . '/../View/auth/login.php';
    }

    public function logout()
    {
        session_unset();
        session_destroy();

        header('Location: ?action=login');
        exit;
    }
}