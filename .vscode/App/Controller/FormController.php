<?php

require_once __DIR__ . '/../../Core/controller.php';
require_once __DIR__ . '/../config.php';

class FormController extends Controller {
    public function adhesion() {
        $this->view('Forms/adhesion');
    }

    public function adhesion_submit() {
        // compat: if the form still posts here, redirect to Stripe flow
        $this->adhesion_checkout();
    }

    public function adhesion_checkout() {
        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $antenne = $_POST['antenne'] ?? null;

        $montant = 30.00;

        if ($prenom === '' || $nom === '' || $email === '') {
            $this->view('Forms/adhesion', [
                'error' => 'Veuillez remplir prénom, nom et email.',
                'old' => compact('prenom', 'nom', 'email', 'antenne'),
            ]);
            return;
        }

        if (STRIPE_SECRET_KEY === '') {
            $this->view('Forms/adhesion', [
                'error' => 'Stripe n’est pas configuré. Ajoutez STRIPE_SECRET_KEY dans .vscode/.env pour activer le paiement.',
                'old' => compact('prenom', 'nom', 'email', 'antenne'),
            ]);
            return;
        }

        $_SESSION['adhesion_pending'] = [
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'antenne' => $antenne,
            'montant' => $montant,
        ];

        $this->createStripeCheckoutSession($montant);
    }

    public function adhesion_success() {
        $pending = $_SESSION['adhesion_pending'] ?? null;
        if (!$pending) {
            $this->view('Forms/adhesion_success', [
                'error' => 'Aucune adhésion en attente de paiement.',
                'id' => null,
            ]);
            return;
        }

        require_once __DIR__ . '/../Model/Adhesion.php';
        $model = new Adhesion();
        try {
            $id = $model->save(
                $pending['prenom'],
                $pending['nom'],
                $pending['email'],
                $pending['antenne'],
                $pending['montant']
            );
            unset($_SESSION['adhesion_pending']);
        } catch (Exception $e) {
            $id = null;
        }

        $this->view('Forms/adhesion_success', [
            'prenom' => $pending['prenom'],
            'nom' => $pending['nom'],
            'email' => $pending['email'],
            'antenne' => $pending['antenne'],
            'montant' => $pending['montant'],
            'id' => $id,
        ]);
    }

    public function adhesion_cancel() {
        $this->view('Forms/adhesion_cancel');
    }

    private function createStripeCheckoutSession($amount) {
        $amountInCents = (int) round($amount * 100);
        $successUrl = APP_URL . '/index.php?action=adhesion_success';
        $cancelUrl = APP_URL . '/index.php?action=adhesion_cancel';

        $postFields = http_build_query([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'line_items[0][price_data][currency]' => 'eur',
            'line_items[0][price_data][product_data][name]' => 'Adhésion All Inclusive',
            'line_items[0][price_data][unit_amount]' => $amountInCents,
            'line_items[0][quantity]' => 1,
            'customer_email' => $_POST['email'] ?? '',
        ]);

        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . STRIPE_SECRET_KEY,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($httpCode >= 200 && $httpCode < 300 && !empty($data['url'])) {
            header('Location: ' . $data['url']);
            exit;
        }

        $this->view('Forms/adhesion', [
            'error' => 'Impossible de créer la session Stripe. ' . ($curlError ?: 'Vérifiez STRIPE_SECRET_KEY.'),
            'old' => $_POST,
        ]);
    }

    public function don() {
        $this->view('Forms/don');
    }

    public function don_submit() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $amount = $_POST['amount'] ?? '';

        $this->view('Forms/don_success', [
            'name' => $name,
            'email' => $email,
            'amount' => $amount,
        ]);
    }
}
