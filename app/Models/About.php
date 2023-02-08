<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// protected untuk table contac query yang akan di isi
class About extends Model
{
    use HasFactory;
    protected $fillable = [
        'misi',
        'visi'

    ];
    
}
