<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Updaters\AgedBrieUpdater;
use GildedRose\Updaters\BackstagePassUpdater;
use GildedRose\Updaters\ItemUpdater;
use GildedRose\Updaters\StandardUpdater;
use GildedRose\Updaters\SulfurasUpdater;
use GildedRose\Updaters\ConjuredUpdater;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $updater = $this->getUpdater($item);
            $updater->update($item);
        }
    }

private function getUpdater(Item $item): ItemUpdater
    {
        if ($item->name === 'Aged Brie') {
            return new AgedBrieUpdater();
        }

        if ($item->name === 'Sulfuras, Hand of Ragnaros') {
            return new SulfurasUpdater();
        }

        if ($item->name === 'Backstage passes to a TAFKAL80ETC concert') {
            return new BackstagePassUpdater();
        }

        if (str_starts_with($item->name, 'Conjured')) {
            return new ConjuredUpdater();
        }

        return new StandardUpdater();
    }
}