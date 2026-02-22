<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    'user_id',
    'value',
    'cpf',
    'document_path',
    'status'
    ];
}
