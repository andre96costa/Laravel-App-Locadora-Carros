<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Repositories\ClienteRepository;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    private $clienteModel;
    
    public function __construct(Cliente $clienteModel) {
        $this->clienteModel = $clienteModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->clienteModel);
        
        // if ($request->has('atributos_modelo')) {
        //     $atributosModelo = "modelo:$request->atributos_modelo";
        //     $carroRepository->selectAtributesRegistroRelacionamento($atributosModelo);
        // } else {
        //     $carroRepository->selectAtributesRegistroRelacionamento('modelo');
        // }

        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $clienteRepository->selectAtributes($request->atributos);
        }
        
        return response()->json($clienteRepository->getResultadoPaginado(3), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->clienteModel->rules(), $this->clienteModel->feedback());
        $cliente = $this->clienteModel->create([
            'nome' => $request->nome,
        ]);
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $cliente = $this->clienteModel->with('carros')->find($id);
        if (empty($cliente)) {
            return response()->json([], 404);
        }
        return response()->json($cliente, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $cliente = $this->clienteModel->find($id);
        if (empty($cliente)) {
            return response()->json([], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->clienteModel->rules(), $this->clienteModel->feedback());
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($this->clienteModel->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $value;
                }
            }
            $request->validate($regrasDinamicas, $this->clienteModel->feedback());
        }
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $cliente = $this->clienteModel->find($id);
        if (empty($cliente)) {
            return response()->json([], 404);
        }
        $cliente->delete();
        return response()->json([], 204);
    }
}
