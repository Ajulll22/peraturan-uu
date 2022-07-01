<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruu_pasal extends Model
{
    use HasFactory;

    protected $table = 'ruu_pasal';
    protected $primaryKey = 'id_ruu_pasal';

    protected $guarded = ['id_ruu_pasal'];
    const UPDATED_AT = null;
    const CREATED_AT = null;

    public function ruu()
    {
        return $this->belongsTo(Ruu::class, 'id_ruu', 'id_ruu');
    }
}
