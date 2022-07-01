<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StemmingTable extends Model
{
    use HasFactory;

    // protected $table = 'tbl_stemming';
    // protected $primaryKey = 'id_stemming';
    protected $table = 'stemming';
    protected $primaryKey = 'id_tbl_uu';
}
