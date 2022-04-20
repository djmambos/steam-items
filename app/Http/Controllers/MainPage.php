<?php

namespace App\Http\Controllers;

use App\Models\Items;

class MainPage extends Controller
{
    public function index() {
        $items = Items::getItems();

        return view('main_page', ['items' => $items]);
    }
}
