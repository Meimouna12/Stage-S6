<?php

class Controller {
    public function view($view, $data = []) {
        extract($data);
        require __DIR__ . '/../App/View/' . $view . '.php';
    }
}