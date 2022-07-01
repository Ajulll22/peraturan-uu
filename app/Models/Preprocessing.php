<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preprocessing extends Model
{
    use HasFactory;

    protected $table = 'preprocessing';
    protected $primaryKey = 'id_preprocessing';

    protected $guarded = ['id_preprocessing'];
    const UPDATED_AT = null;
    const CREATED_AT = null;
}
