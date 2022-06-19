<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;

    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id',
        'carro_id',
        'data_inicio_periodo',
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final',
    ];

    protected $with = ['carro.modelo'];

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'carro_id' => 'required|exists:carros,id',
            'data_inicio_periodo' => 'required|date_format:Y-m-d',
            'data_final_previsto_periodo' => 'required|date_format:Y-m-d',
            'data_final_realizado_periodo' => 'date_format:Y-m-d',
            'valor_diaria' => 'required|integer',
            'km_inicial' => 'required|integer',
            'km_final' => 'integer',
        ];
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'date_format' => 'A data :attribute deve ser informada no padrão (ano-mes-dia)',
            'integer' => 'O campo deve ser um valor númerico inteiro',
            'cliente_id.exists' => 'Selecione um cliente valido',
            'carro_id.exists' => 'Selecione um carro valido',
        ];
    }
    
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function carro()
    {
        return $this->belongsTo(Carro::class, 'carro_id', 'id');
    }
}
