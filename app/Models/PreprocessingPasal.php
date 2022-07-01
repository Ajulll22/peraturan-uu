<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreprocessingPasal extends Model
{
    use HasFactory;
    protected $table = 'preprocessing_pasal';
    protected $primaryKey = 'id_prep_pasal';

    protected $guarded = ['id_prep_pasal'];
    const UPDATED_AT = null;
    const CREATED_AT = null;
}
