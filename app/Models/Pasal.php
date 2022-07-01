<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasal extends Model
{
    use HasFactory;

    protected $table = 'uu_pasal_html';
    protected $primaryKey = 'id';

    protected $guarded = ['id'];
    const UPDATED_AT = null;
    const CREATED_AT = null;

    public function uu()
    {
        return $this->belongsTo(Archive::class, 'id_tbl_uu', 'id_tbl_uu');
    }
}
