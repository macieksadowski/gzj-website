<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'performer',
        'composer',
        'text_author',
        'title_official',

        'tempo',
        'drums_annotations',
        'announcer_annotations_1',
        'announcer_annotations_2'
    ];
}
