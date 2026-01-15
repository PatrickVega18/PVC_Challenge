<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class StandardUpdater implements ItemUpdater
{
    public function update(Item $item): void
    {
        $this->decreaseQuality($item);

        $item->sellIn--;

        if ($item->sellIn < 0) {
            $this->decreaseQuality($item);
        }
    }

    protected function decreaseQuality(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality--;
        }
    }
    
    protected function increaseQuality(Item $item): void
    {
        if ($item->quality < 50) {
            $item->quality++;
        }
    }
}