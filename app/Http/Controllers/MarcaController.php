<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    private Marca $marca; 

    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->marca->all(), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $marca = $this->marca->find($id);
        return response()->json($marca, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $marca = $this->marca->create([
            "nome" => $request->nome,
            "imagem" => $request->imagem,
        ]);

        return response()->json($marca, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {   
        $marca = $this->marca->find($id);
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $request->imagem, 
        ]);

        return response()->json($marca, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();

        return response()->json([], 204);
    }
}
