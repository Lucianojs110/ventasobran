<?php

namespace SisVentaNew\Http\Controllers;

use Illuminate\Http\Request;

class InformeController extends Controller
{
      public function index(Request $request)
    {
       
        return view('informe.indexx');
    }
}
