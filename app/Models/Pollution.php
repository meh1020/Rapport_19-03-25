<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pollution extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'numero',
        'zone',
        'coordonnees',
        'type_pollution',
        'image_satellite'
    ];

    public function images()
    {
        return $this->hasMany(PollutionImage::class);
    }
}