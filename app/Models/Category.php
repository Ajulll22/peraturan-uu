<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'tbl_kategori';
    protected $primaryKey = 'kategori_id';

    protected $guarded = ['kategori_id'];

    const UPDATED_AT = null;

    public function archive()
    {
        return $this->hasMany(Archive::class, 'id_kategori', 'kategori_id');
    }
}
