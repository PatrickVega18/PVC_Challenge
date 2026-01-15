<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class ConjuredUpdater extends StandardUpdater
{
    public function update(Item $item): void
    {
        // "Conjured" items degrade in Quality twice as fast as normal items
        $this->decreaseQuality($item);
        $this->decreaseQuality($item);

        $item->sellIn--;

        if ($item->sellIn < 0) {
            $this->decreaseQuality($item);
            $this->decreaseQuality($item);
        }
    }
}