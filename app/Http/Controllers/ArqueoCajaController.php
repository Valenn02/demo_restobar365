<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ArqueoCaja;

class ArqueoCajaController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
            if (!$request->ajax()) return redirect('/');
            $arqueoCaja = new ArqueoCaja();
            $arqueoCaja->idcaja = "6";
            $arqueoCaja->idusuario = \Auth::user()->id; 
            $arqueoCaja->billete200 = $request->billete200;
            $arqueoCaja->billete100 = $request->billete100;
            $arqueoCaja->billete50 = $request->billete50;
            $arqueoCaja->billete20 = $request->billete20;
            $arqueoCaja->billete10 = $request->billete10;
            $arqueoCaja->moneda5 = $request->moneda5;
            $arqueoCaja->moneda2 = $request->moneda2;
            $arqueoCaja->moneda1 = $request->moneda1;
            $arqueoCaja->moneda050 = $request->moneda050;
            $arqueoCaja->moneda020 = $request->moneda020;
            $arqueoCaja->moneda010 = $request->moneda010;
    
            $arqueoCaja->save();

    }
}
