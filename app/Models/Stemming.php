<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stemming extends Model
{
    use HasFactory;

    protected $table = 'stemming';
    protected $primaryKey = 'id_stemming';

    protected $guarded = ['id_stemming'];
    const UPDATED_AT = null;
    const CREATED_AT = null;
}
