
<?php

require_once __DIR__ . '/../../Core/controller.php';

class DashboardController extends Controller
{
    public function index()
    {
        global $pdo;

        $nbUsers = $pdo->query(
            "SELECT COUNT(*) FROM users"
        )->fetchColumn();

        $nbAntennes = $pdo->query(
            "SELECT COUNT(*) FROM antennes"
        )->fetchColumn();

        $nbActualites = $pdo->query(
            "SELECT COUNT(*) FROM actualites"
        )->fetchColumn();

        $nbEvenements = $pdo->query(
            "SELECT COUNT(*) FROM evenements"
        )->fetchColumn();

        $nbDons = $pdo->query(
            "SELECT COUNT(*) FROM dons"
        )->fetchColumn();

        require_once __DIR__
            . '/../View/dashboard/index.php';
    }

    public function dons()
{
    global $pdo;

    $stmt = $pdo->query("
        SELECT *
        FROM dons
        ORDER BY date_don DESC
    ");

    $dons = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->view('Dashboard/dons', [
        'dons' => $dons
    ]);
}

public function adhesions()
{
    global $pdo;

   $stmt = $pdo->query("
    SELECT *
    FROM adhesions
    ORDER BY date_adhesion DESC"
    );
    $adhesions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $this->view('Dashboard/adhesions', [
        'adhesions' => $adhesions
    ]);
}
}