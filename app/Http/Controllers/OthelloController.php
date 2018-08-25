<?php
namespace App\Http\Controllers;

class OthelloController extends Controller
{
    /**
     * Viewを表示
     *
     * @return mixed
     */
    public function index()
    {
        return view('othello');
    }
}
