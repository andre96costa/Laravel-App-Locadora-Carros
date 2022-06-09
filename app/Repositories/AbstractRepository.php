<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributesRegistroRelacionamento(string $atributos)
    {
        $this->model = $this->model->with($atributos);
    }

    public function filtro(string $filtros)
    {
        $filtros = explode(';', $filtros);
        foreach ($filtros as $key => $value) {
            $condicoes = explode(':', $value);
            $this->model = $this->model->where($condicoes[0], $condicoes[1], $condicoes[2]);
        }    
    }

    public function selectAtributes(string $atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }
    public function getResultadoPaginado(int $numeroRegistrosPorPagina)
    {
        return $this->model->paginate($numeroRegistrosPorPagina);
    }
}