<?php

namespace App\Http\Controllers;

use App\Helpers\DivisionHelper;
use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the divisions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $divisions = DivisionHelper::getAllDivisions();
        return view('divisions.index', compact('divisions'));
    }
}
