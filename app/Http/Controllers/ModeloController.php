<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use App\Repositories\ModeloRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    private $modeloModel;

    public function __construct(Modelo $modeloModel) {
        $this->modeloModel = $modeloModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $modeloRepository = new ModeloRepository($this->modeloModel);
        
        if ($request->has('atributos_marca')) {
            $atributosMarca = "marca:$request->atributos_marca";
            $modeloRepository->selectAtributesRegistroRelacionamento($atributosMarca);
        } else {
            $modeloRepository->selectAtributesRegistroRelacionamento('marca');
        }

        if ($request->has('filtro')) {
            $modeloRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $modeloRepository->selectAtributes($request->atributos);
        }

        return response()->json($modeloRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $request->validate($this->modeloModel->rules(), $this->modeloModel->feedback());
        $imagem = $request->file('imagem')->store('imagens/modelos', 'public');
        $modelo = $this->modeloModel->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs,
        ]);
        return response()->json($modelo, 201); 
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $modelo = $this->modeloModel->with(['marca', 'carros'])->find($id);
        if (empty($modelo)) {
            return response()->json([], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {   
        $modelo = $this->modeloModel->find($id);
        $imagem = $request->file('imagem');

        if (empty($modelo)) {
            return response()->json([], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($modelo->rules(), $modelo->feedback());
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($modelo->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $value;
                }
            }
            $request->validate($regrasDinamicas, $modelo->feedback());
        }
        
        if ($imagem) {
            Storage::disk('public')->delete($modelo->imagem);
            $imagem = $imagem->store('imagens/modelos', 'public');
        }

        $modelo->fill($request->all());
        $modelo->imagem = $imagem ?? $modelo->imagem;
        $modelo->save();
        return response()->json($modelo, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $modelo = $this->modeloModel->find($id);
        if (empty($modelo)) {
            return response()->json([], 404);
        }
        
        try {
            $modelo->delete();
            Storage::disk('public')->delete($modelo->imagem);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Não pode ser deletado. Este registro está relacionado com um registro de carro.'], 500);
        }
    }

    public function listAllModelos()
    {
        $modeloRepository = new ModeloRepository($this->modeloModel);
        return response()->json($modeloRepository->getResultado(), 200);
    }
}
