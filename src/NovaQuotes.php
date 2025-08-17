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

    protected $user = 'Guest';

    protected $description = '';

    protected $greetings = 'Welcome';

    protected $interval = 5;

    public function render()
    {
        return $this->withMeta([
            'slides' => $this->getSlides(),
            'refreshInterval' => $this->interval,
        ]);
    }

    protected function getSlides()
    {
        return collect([
            [
                'type' => 'welcome',
                'greetings' => $this->greetings,
                'user' => $this->user,
                'description' => $this->description,
                'gradient' => 'linear-gradient(to right, #b92b27, #1565C0)',
            ],
        ])->merge(
            Inspiring::getQuotesCollections()->map(function ($quote) {
                return [
                    'type' => 'quote',
                    'text' => $quote['text'],
                    'author' => $quote['author'],
                ];
            })
        )->toArray();
    }

    /**
     * Get the component name for the element.
     */
    public function component(): string
    {
        return 'nova-quotes';
    }

    public function refreshInterval(int $seconds): self
    {
        $this->interval = $seconds;

        return $this;
    }

    public function user(string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function description(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function greetings(string $greetings): self
    {
        $this->greetings = $greetings;

        return $this;
    }
}
