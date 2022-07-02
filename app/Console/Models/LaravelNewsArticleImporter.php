<?php

namespace App\Console\Models;

use GuzzleHttp\Client;

class LaravelNewsArticleImporter extends AbstractLaravelNewsImporter
{
    private $category;
    private $title;
    private $date;
    private $imageLink;
    private $sourceLink;



    public function __construct(array $params = [])
    {
        $this->client = new Client([
            'timeout'   => 10,
            'verify'    => false
        ]);

        $objectParams = get_object_vars($this);
        foreach ($objectParams as $paramName => $paramValue) {
            $this->$paramName = $params[$paramName] ?? null;
        }
    }

    public function process()
    {

    }

    private function saveToModel()
    {

    }
}
