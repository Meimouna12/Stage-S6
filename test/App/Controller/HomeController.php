<?php

require_once __DIR__ . '/../../Core/controller.php';
require_once __DIR__ . '/../Model/Actualite.php';

class HomeController extends Controller {
    public function index() {
        $model = new Actualite();
        $actualites = $model->getFeatured(3);

        $this->view('Home/index', [
            'actualites' => $actualites,
        ]);
    }
}
