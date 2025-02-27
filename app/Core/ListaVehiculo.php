<?php

namespace App\Core;

use App\Core\Services\ConductorService;

class ListaConductor
{
    private $service;

    public function __construct()
    {

        $this->service = new ConductorService;
    }

    
}
