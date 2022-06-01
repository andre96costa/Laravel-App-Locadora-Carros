<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        if (empty($marca)) {
            return response()->json([], 404);
        }
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
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem')->store('imagens', 'public');

        $marca = $this->marca->create([
            "nome" => $request->nome,
            "imagem" => $imagem,
        ]);

        return response()->json([], 201);
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
        $image = $request->file('imagem');
        
        if (empty($marca)) {
            return response()->json([], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($marca->rules(), $marca->feedback());
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();

            foreach ($marca->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $value;
                }
            }
            $request->validate($regrasDinamicas, $marca->feedback());
        }
        
        if (empty($image)) {
            $image = $marca->imagem;
        } else {
            Storage::disk('public')->delete($marca->imagem);
            $image = $image->store('imagens', 'public');
        }

        $update = $request->all();
        $update['imagem'] = $image;
        $marca->update($update);
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

        if (empty($marca)) {
            return response()->json([], 404);
        }

        Storage::disk('public')->delete($marca->imagem);
        $marca->delete();

        return response()->json([], 204);
    }
}
