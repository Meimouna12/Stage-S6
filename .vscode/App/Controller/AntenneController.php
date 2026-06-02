<?php

require_once __DIR__ . '/../../Core/controller.php';
require_once __DIR__ . '/../Model/Antenne.php';

class AntenneController extends Controller {

    public function index() {
        $model = new Antenne();
        $antennes = $model->getAll();

        $this->view("Antenne/index", ["antennes" => $antennes]);
    }

    public function show($id) {
        $model = new Antenne();
        $antenne = $model->getById($id);
        $events = $model->getUpcomingEventsByAntenne($id, 3);
        $socialLinks = $model->getSocialLinksByAntenne($id);
        $mairieInfo = $model->getMairieInfoByAntenne($id);

        $this->view("Antenne/Show", [
            "antenne" => $antenne,
            "events" => $events,
            "socialLinks" => $socialLinks,
            "mairieInfo" => $mairieInfo,
        ]);
    }
}