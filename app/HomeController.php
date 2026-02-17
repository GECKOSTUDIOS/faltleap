<?php

declare(strict_types=1);

namespace App\Controllers;

use FaltLeap\LeapController;

class HomeController extends LeapController
{
    public function welcome()
    {
        $this->view->single('welcome');
    }

    public function index()
    {
        $this->view->render('home/index');
    }
}
