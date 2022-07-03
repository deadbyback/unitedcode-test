<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public $timestamps = false;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $link;


    protected $table = 'authors';
    protected $fillable = [
        'name', 'link',
    ];


    public function articles()
    {
        return $this->hasMany('App\Articles');
    }
}
