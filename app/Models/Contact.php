<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// protected untuk table contac query yang akan di isi
class Contact extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'email',
        'phone',
        'pesan'

    ];
}
