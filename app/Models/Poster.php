<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poster extends Model
{
    use HasFactory;

    protected $fillable = [
        'url', 'title_id'
    ];

    public $timestamps = false;

    public function title() {
        return $this->belongsTo(Title::class);
    }
}
