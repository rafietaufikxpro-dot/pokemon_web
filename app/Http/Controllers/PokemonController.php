<?php

namespace App\Http\Controllers;

class PokemonController extends Controller
{
    public function pokedex()
    {
        return view('pokedex');
    }

    public function battle()
    {
        return view('battle');
    }
}
