<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca_id',
        'nome',
        'imagem',
        'numero_portas',
        'lugares',
        'air_bag',
        'abs',
    ];

    public function rules()
    {
        return [
            'marca_id' => 'required|exists:marcas,id',
            'nome' => "required|unique:modelos,nome,$this->id,id|min:3",
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5',
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean',
        ];
    }

    public function feedback()
    {
        return [
            'marca_id.exists' => 'Marca não encontrada/cadastrada',
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'Este modelo já foi cadastrado',
            'nome.min' => 'O nome deve ter no minimo 3 caracteres',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo (PNG,JPEG,JPG)',
            'integer' => 'O campo só aceita números inteiros',
            'numero_portas.digits_between' => 'Deve ser digitado um valor entre 1 e 5',
            'lugares.digits_between' => 'Deve ser digitado um valor entre 1 e 20',
            'boolean' => 'Selecione um valor valido',
        ];
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id', 'id');
    }

    public function carros()
    {
        return $this->hasMany(Carro::class, 'modelo_id', 'id');
    }
}
