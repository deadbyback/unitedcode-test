<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public $timestamps = false;
    /**
     * @var string|null
     */
    public $title;
    /**
     * @var string|null
     */
    public $link;
    /**
     * @var integer e.g. timestamp
     */
    public $date;
    /**
     * @var integer
     */
    public $author_id;


    protected $table = 'articles';
    protected $fillable = [
        'link', 'title', 'date', 'author_id', //TODO: 'tags'
    ];


    public function author()
    {
        return $this->belongsTo('App\Author');
    }
}
