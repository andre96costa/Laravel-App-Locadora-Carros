<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    public function rules()
    {
        return [
            'nome' => "required|min:3|max:50",
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.min' => 'O nome deve ter no minimo 3 carecteres',
            'nome.max' => 'O nome deve ter no maximo 50 carecteres',
        ];
    }
}
