<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class BackstagePassUpdater extends StandardUpdater
{
    public function update(Item $item): void
    {
        $this->increaseQuality($item);

        if ($item->sellIn < 11) {
            $this->increaseQuality($item);
        }

        if ($item->sellIn < 6) {
            $this->increaseQuality($item);
        }

        $item->sellIn--;

        if ($item->sellIn < 0) {
            $item->quality = 0;
        }
    }
}