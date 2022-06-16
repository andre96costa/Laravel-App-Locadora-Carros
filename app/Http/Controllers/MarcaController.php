<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Repositories\MarcaRepository;
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
    public function index(Request $request)
    {
        $marcaRepository = new MarcaRepository($this->marca);
        
        if ($request->has('atributos_modelos')) {
            $atributosModelos = "modelos:$request->atributos_modelos";
            $marcaRepository->selectAtributesRegistroRelacionamento($atributosModelos);
        } else {
            $marcaRepository->selectAtributesRegistroRelacionamento('modelos');
        }

        if ($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $marcaRepository->selectAtributes($request->atributos);
        }

        return response()->json($marcaRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $marca = $this->marca->with('modelos')->find($id);
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

        if ($image) {
            Storage::disk('public')->delete($marca->imagem);
            $image = $image->store('imagens', 'public');
        }

        $marca->fill($request->all());
        $marca->imagem = $image ?? $marca->imagem;
        $marca->save();
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

    public function listAllMarca()
    {
        $marcaRepository = new MarcaRepository($this->marca);
        return response()->json($marcaRepository->getResultado(), 200);
    }
}
