<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;

    protected $fillable = [
        'modelo_id',
        'placa',
        'disponivel',
        'km'
    ];

    public function rules()
    {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => "required|size:10|string|unique:carros,placa,$this->id,id",
            'disponivel' => 'required|boolean',
            'km' => 'required|integer',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'modelo_id.exists' => 'O modelo selecionado não foi encontrado',
            'placa.size' => 'A placa deve ter 10 carecteres',
            'placa.unique' => 'Já existe um carro cadastrado com essa placa',
            'boolean' => 'Selecione um valor para esse campo',
            'integer' => 'Esse campo só aceita números',
        ];
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id', 'id');
    }
}
