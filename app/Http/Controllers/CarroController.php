<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    private $carroModel;

    public function __construct(Carro $carroModel) {
        $this->carroModel = $carroModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carroModel);
        
        if ($request->has('atributos_modelo')) {
            $atributosModelo = "modelo:$request->atributos_modelo";
            $carroRepository->selectAtributesRegistroRelacionamento($atributosModelo);
        } else {
            $carroRepository->selectAtributesRegistroRelacionamento('modelo');
        }

        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $carroRepository->selectAtributes($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->carroModel->rules(), $this->carroModel->feedback());
        $carro = $this->carroModel->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km,
        ]);
        return response()->json($carro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $carro = $this->carroModel->with('modelo')->find($id);
        if (empty($carro)) {
            return response()->json([], 404);
        }
        return response()->json($carro, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $carro = $this->carroModel->find($id);
        if (empty($carro)) {
            return response()->json([], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->carroModel->rules(), $this->carroModel->feedback());
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($this->carroModel->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $value;
                }
            }
            $request->validate($regrasDinamicas, $this->carroModel->feedback());
        }
        $carro->fill($request->all());
        $carro->save();
        return response()->json($carro, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $carro = $this->carroModel->find($id);
        if (empty($carro)) {
            return response()->json([], 404);
        }
        $carro->delete();
        return response()->json([], 204);
    }
}
