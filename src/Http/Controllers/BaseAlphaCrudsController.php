<?php

namespace Aqamarine\AlphaCruds\Http\Controllers;

use Aqamarine\AlphaCruds\Components\SessionAlerts;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

class BaseAlphaCrudsController extends Controller
{

    use ValidatesRequests;

    protected function view($view, array $data = []): View
    {
        return view('alphacruds::'.$view, $data);
    }

    protected function showSuccessMessage($message): void
    {
        (new SessionAlerts())->success($message);
    }

    protected function showErrorMessage($message): void
    {
        (new SessionAlerts())->error($message);
    }

    protected function showWarningMessage($message): void
    {
        (new SessionAlerts())->warning($message);
    }

}
