<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = false;


    protected $table = 'articles';
    protected $fillable = [
        'link', 'title', 'date', 'authorId', //TODO: 'tags'
    ];


    public function author()
    {
        return $this->belongsTo('App\Author');
    }
}
