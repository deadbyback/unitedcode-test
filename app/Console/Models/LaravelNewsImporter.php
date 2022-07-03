<?php

namespace App\Console\Models;

use DateTime;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DomCrawler\Crawler;

class LaravelNewsImporter extends AbstractLaravelNewsImporter implements LaravelNewsImporterInterface
{
    const NEWS_TAG = 'News';


    /**
     * @var string
     */
    private $url;
    /**
     * @var int
     */
    private $monthsNumber;
    /**
     * @var bool
     */
    private $stopFlag;
    /**
     * @var int
     */
    private $iterator;


    public function __construct(string $url, int $months = 4)
    {
        $this->url = $url;
        $this->monthsNumber = $months;
        $this->stopFlag = false;
    }

    /**
     * Content Parsing
     */
    public function process()
    {
        try {
            $this->iterator = 0;
            ///
            $parsedItem = [
                'title' => 'News',
                'date' => time(),
                'imageLink' => 'https://laravelnews.imgix.net/images/vite.jpg?ixlib=php-3.3.1',
                'sourceLink' => 'https://laravel-news.com/vite-is-the-default-frontend-asset-bundler-for-laravel-applications'
            ];
            $articleImporter = new LaravelNewsArticleImporter($parsedItem);
            $articleImporter->process();
            die;
            ///
            $parsedDataArray = $this->doParsingLoop();

            foreach ($parsedDataArray as $parsedItem) {
                $articleImporter = new LaravelNewsArticleImporter($parsedItem);
                $articleImporter->process();
            }
        } catch (\Exception $e ) {
            echo $e->getMessage();
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Performs a cycle of page parsing of the {$this->url} site
     * TODO: make this with Iterator.
     * @return array
     * @throws GuzzleException
     */
    private function doParsingLoop(): array
    {
        $parsedDataArray = [];

        while (!$this->stopFlag) {
            $this->iterator++;
            if ($this->iterator === 1) {
                $url = $this->url;
            } else {
                $url = $this->url . "?page={$this->iterator}";
            }
            $content = $this->sendRequest($url);

            if ($content) {
                $currentStepData = $this->processContent($content);

                if (!empty($currentStepData)) {
                    $parsedDataArray = array_merge($parsedDataArray, array_filter($currentStepData));
                }
            }
        }

        return $parsedDataArray;
    }


    /**
     * Get content and pass to the Crawler
     * @param string $content
     * @return array
     */
    protected function processContent(string $content): array
    {
        $crawler = new Crawler($content, self::BASE_URI);

        return $crawler->filter('body > main > div.bg-gray-50.grid.grid-cols-12.pb-24 > ul > li')->each(function (Crawler $node) {
            $filteredNode = $node->filter('a');
            return $this->hasContent($filteredNode) ? $this->getNodeContent($filteredNode) : null;
        });
    }


    /**
     * Get node values
     * @filter function required the identifies, which we want to filter from the content.
     * @param Crawler $filteredNode
     * @return array|null
     */
    protected function getNodeContent(Crawler $filteredNode): ?array
    {
        $dateText = $filteredNode->filter('p.font-mono')->text();
        $timestamp = strtotime($dateText);
        $this->checkDate($timestamp);
        $category = $this->hasContent($filteredNode->filter('span')) ? $filteredNode->filter('span')->text() : null;

        if ($this->stopFlag || $category !== self::NEWS_TAG) {
            return null;
        }

        return [
            'title' => $this->hasContent($filteredNode->filter('p.mt-2')) ? $filteredNode->filter('p.mt-2')->text() : null,
            'date' => $timestamp !== false ? $timestamp : null,
            'imageLink' => $this->hasContent($filteredNode->filter('img')) ? $filteredNode->filter('img')->image()->getUri() : null,
            'sourceLink' => $this->hasContent($filteredNode) ? $filteredNode->link()->getUri() : null,
        ];
    }

    /**
     * This method checks if the date is within the period of $this->monthNumber months
     * @param int|bool $timestamp
     * @return void
     */
    private function checkDate($timestamp)
    {
        if (is_numeric($timestamp)) {
            $pastTimestamp = strtotime("-{$this->monthsNumber} Months");
            $pastTimePoint = DateTime::createFromFormat('U', $pastTimestamp);
            $pastTimePoint->setTime(0,0,0);

            if ($timestamp <= $pastTimePoint->getTimestamp()) {
                $this->stopFlag = true;
            }
        }
    }
}
