<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

class SpaController extends Controller
{
    /**
     * Render single page app view.
     *
     * @param Request $request http request
     * @return View
     */
    public function render(Request $request) : View
    {
        return view('app');
    }
}
