<?php

namespace Laravelwebdev\NovaQuotes;

use Laravel\Nova\Card;
use Laravelwebdev\NovaQuotes\Inspiring\Inspiring;

class NovaQuotes extends Card
{
    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = 'full';

    protected $limit = 10;

    public function __construct()
    {
        parent::__construct();

        $this->withMeta([
            'slides' => $this->getSlides(),
        ]);
    }

    protected function getSlides()
    {
        return collect([
            [
                'type' => 'welcome',
                'name' => auth()->user()->name ?? 'Guest',
                'title' => 'Welcome to Laravel Nova',
                'gradient' => 'linear-gradient(to right, #b92b27, #1565C0)',
            ],
        ])->merge(
            Inspiring::getQuotesCollections($this->limit)->map(function ($quote) {
                return [
                    'type' => 'quote',
                    'text' => $quote['text'],
                    'author' => $quote['author'],
                ];
            })
        )->toArray();
    }

    public function limitQuotes(int $limit) : self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Get the component name for the element.
     */
    public function component(): string
    {
        return 'nova-quotes';
    }
}
