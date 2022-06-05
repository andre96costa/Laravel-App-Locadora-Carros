<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    private $locacaoModel;
    public function __construct(Locacao $locacaoModel) {
        $this->locacaoModel = $locacaoModel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locacaoeRepository = new LocacaoRepository($this->locacaoModel);
        
        // if ($request->has('atributos_modelo')) {
        //     $atributosModelo = "modelo:$request->atributos_modelo";
        //     $carroRepository->selectAtributesRegistroRelacionamento($atributosModelo);
        // } else {
        //     $carroRepository->selectAtributesRegistroRelacionamento('modelo');
        // }

        if ($request->has('filtro')) {
            $locacaoeRepository->filtro($request->filtro);
        }

        if ($request->has('atributos')) {
            $locacaoeRepository->selectAtributes($request->atributos);
        }
        
        return response()->json($locacaoeRepository->getResultado(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->locacaoModel->rules(), $this->locacaoModel->feedback());
        $locacao = $this->locacaoModel->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final,
        ]);
        return response()->json($locacao, 201);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $locacao = $this->locacaoModel->find($id);
        if (empty($locacao)) {
            return response()->json([], 404);
        }
        return response()->json($locacao, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $locacao = $this->locacaoModel->find($id);
        if (empty($locacao)) {
            return response()->json([], 404);
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->locacaoModel->rules(), $this->locacaoModel->feedback());
        }

        if ($request->method() === 'PATCH') {
            $regrasDinamicas = array();
            foreach ($this->locacaoModel->rules() as $key => $value) {
                if (array_key_exists($key, $request->all())) {
                    $regrasDinamicas[$key] = $value;
                }
            }
            $request->validate($regrasDinamicas, $this->locacaoModel->feedback());
        }
        $locacao->fill($request->all());
        $locacao->save();
        return response()->json($locacao, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $locacao = $this->locacaoModel->find($id);
        if (empty($locacao)) {
            return response()->json([], 404);
        }
        $locacao->delete();
        return response()->json([], 204);
    }
}
