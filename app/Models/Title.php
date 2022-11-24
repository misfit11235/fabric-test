<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'year', 'imdb_id', 'type'
    ];

    public $timestamps = false;

    public function poster() {
        return $this->hasOne(Poster::class);
    }
}
