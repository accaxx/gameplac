<?php
namespace App\Http\Controllers\Slot;

use App\Http\Controllers\Controller;

class SlotController extends Controller
{
    public function __construct()
    {
    }

    /**
     * Viewを表示
     *
     * @return mixed
     */
    public function index()
    {
        return view('slot/index');
    }
}