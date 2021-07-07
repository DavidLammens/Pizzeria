<?php

declare(strict_types=1);

namespace Business;

use Data\LeverPlaatsDAO;

class PlaatsService
{
    public function getLeverPlaatsen(): array
    {
        $leverPlaatsDAO = new LeverPlaatsDAO;
        return $leverPlaatsDAO->getAll();
    }    
}