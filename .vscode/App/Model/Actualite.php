<?php

class Actualite {
    // Retourne quelques actualités à la une (données statiques pour l'instant)
    public function getFeatured($limit = 3) {
        $now = date('Y-m-d');
        return [
            [
                'id' => 1,
                'titre' => 'Lancement de la campagne estivale',
                'chapo' => 'Notre campagne pour accompagner les familles démarre en juillet avec des ateliers locaux.',
                'date' => $now,
                'slug' => 'campagne-estivale'
            ],
            [
                'id' => 2,
                'titre' => 'Nouvelles antennes ouvertes',
                'chapo' => 'Nous ouvrons 2 nouvelles antennes pour renforcer notre présence territoriale.',
                'date' => $now,
                'slug' => 'nouvelles-antennes'
            ],
            [
                'id' => 3,
                'titre' => 'Bilan semestriel des actions',
                'chapo' => 'Retour sur les actions menées lors du premier semestre et chiffres clés.',
                'date' => $now,
                'slug' => 'bilan-semestriel'
            ],
        ];
    }
}
