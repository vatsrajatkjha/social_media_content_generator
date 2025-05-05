<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneratedImage extends Model
{
    protected $fillable = [
        'url',
        'prompt',
        'image_type',
        'style',
        'width',
        'height',
        'error_message',
    ];
}
