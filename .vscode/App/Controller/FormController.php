<?php

require_once __DIR__ . '/../../Core/controller.php';

class FormController extends Controller
{
    public function adhesion()
    {
        $this->view('Forms/adhesion');
    }

    public function adhesion_submit()
    {
        $this->adhesion_checkout();
    }

    public function adhesion_checkout()
{
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $antenne = $_POST['antenne'] ?? '';

    if ($prenom === '' || $nom === '' || $email === '') {

        $this->view('Forms/adhesion', [
            'error' => 'Veuillez remplir tous les champs.',
            'old' => $_POST
        ]);

        return;
    }

    $_SESSION['adhesion_pending'] = [
        'prenom' => $prenom,
        'nom' => $nom,
        'email' => $email,
        'antenne' => $antenne,
        'montant' => 30
    ];

    header(
        'Location: https://www.helloasso.com/associations/all-inclusive/adhesions/adhesion-all-inclusive'
    );
    exit;
}
    public function adhesion_success()
{
    $pending = $_SESSION['adhesion_pending'] ?? null;

    if (!$pending) {

        $this->view('Forms/adhesion_success', [
            'error' => 'Aucune adhésion trouvée.'
        ]);

        return;
    }

    require_once __DIR__ . '/../Model/Adhesion.php';

    $model = new Adhesion();

    $id = $model->save(
        $pending['prenom'],
        $pending['nom'],
        $pending['email'],
        $pending['antenne'],
        $pending['montant']
    );

    unset($_SESSION['adhesion_pending']);

    $this->view('Forms/adhesion_success', [
        'prenom' => $pending['prenom'],
        'nom' => $pending['nom'],
        'email' => $pending['email'],
        'antenne' => $pending['antenne'],
        'montant' => $pending['montant'],
        'id' => $id
    ]);
}
    public function adhesion_cancel()
    {
        $this->view('Forms/adhesion_cancel');
    }

    public function don()
    {
        $this->view('Forms/don');
    }

    public function don_submit()
{
    $email = trim($_POST['email'] ?? '');
    $amount = trim($_POST['amount'] ?? '');

    if ($email === '' || $amount === '') {

        $this->view('Forms/don', [
            'error' => 'Veuillez remplir tous les champs.',
            'old' => $_POST
        ]);

        return;
    }

    $_SESSION['don_pending'] = [
        'email' => $email,
        'amount' => $amount
    ];

    header('Location: https://www.helloasso.com/associations/all-inclusive');
    exit;
}

   public function don_success()
{
    $pending = $_SESSION['don_pending'] ?? null;

    if (!$pending) {

        $this->view('Forms/don_success', [
            'error' => 'Aucun don trouvé.'
        ]);

        return;
    }

    require_once __DIR__ . '/../Model/Don.php';

    $model = new Don();

    $id = $model->save(
        $pending['email'],
        $pending['amount']
    );

    unset($_SESSION['don_pending']);

    $this->view('Forms/don_success', [
        'email' => $pending['email'],
        'amount' => $pending['amount'],
        'id' => $id
    ]);
}
}