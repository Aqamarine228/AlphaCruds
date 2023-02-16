<?php

namespace AlphaDevTeam\AlphaCruds\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends BaseAlphaCrudsController
{

    public function index(): View
    {
        return $this->view('dashboard');
    }

}
