<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    public function index()
    {
        echo json_encode('test');
        exit();
    }
}
