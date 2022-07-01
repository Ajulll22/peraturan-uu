<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruu extends Model
{
    use HasFactory;

    protected $table = 'ruu';
    protected $primaryKey = 'id_ruu';

    protected $guarded = ['id_ruu'];

    const UPDATED_AT = null;
    const CREATED_AT = null;


    public function pasal()
    {
        return $this->hasMany(Ruu_pasal::class, 'id_ruu', 'id_ruu');
    }
}
