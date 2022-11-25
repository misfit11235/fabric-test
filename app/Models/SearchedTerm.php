<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchedTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'term',
        'total_pages',
        'last_cached_page'
    ];

    public $timestamps = false;

}
