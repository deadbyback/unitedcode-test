<?php

namespace App\Console\Models;

use App\Article;
use App\Author;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class LaravelNewsArticleImporter extends AbstractLaravelNewsImporter implements LaravelNewsImporterInterface
{
    private $title;
    private $date;
    private $imageLink; //TODO: save image sources too
    private $sourceLink;
    private $authorLink;
    private $authorName;
    private $tagString;



    public function __construct(array $params = [])
    {
        $objectParams = get_object_vars($this);
        foreach ($objectParams as $paramName => $paramValue) {
            $this->$paramName = $params[$paramName] ?? null;
        }
    }

    /**
     * @throws GuzzleException
     */
    public function process()
    {
        $content = $this->sendRequest($this->sourceLink);

        if ($content) {
            $this->parseContent($content);
            $this->saveToModels();
        }
    }

    /**
     * Parses the current page and adds missing data.
     * @param string $content
     * @return void
     */
    private function parseContent(string $content): void
    {
        $crawler = new Crawler($content, self::BASE_URI);
        $rawTagsArray = $crawler->filter('div.col-span-10')
            ->filter('div.flex')->filter('span.text-white')
            ->each(function (Crawler $node) {
                return $this->hasContent($node) ? $node->text() : null;
            });
        $uniqueTags = array_unique($rawTagsArray);
        $this->tagString = implode(', ', $uniqueTags);
        $authorTag = $crawler->filter('p[itemprop=author]');
        $this->authorName = $authorTag->text();
        $this->authorLink = $authorTag->filter('a[rel=author]')->link()->getUri();
    }

    /**
     * Performs saving received data to database tables via Eloquent models.
     * @return void
     */
    private function saveToModels()
    {
        $authorId = $this->findOrSaveAuthor();
        $this->saveArticle($authorId);
    }

    /**
     * Finds the article's author. If it fails, creates a new one and returns that author's ID.
     * @return int
     */
    private function findOrSaveAuthor(): int
    {
        $authorModel = Author::firstOrNew(
            ['name' => $this->authorName],
            ['link' => $this->authorLink]
        );
        if (!$authorModel->exists) {
            $authorModel->save();
        }

        return $authorModel->id;
    }

    /**
     * Creates new Article instance.
     * @param int $authorId
     * @return void
     */
    private function saveArticle(int $authorId): void
    {
        Article::create([
            'title' => $this->title,
            'link' => $this->sourceLink,
            'date' => $this->date,
            'author_id' => $authorId
        ]);
    }
}
