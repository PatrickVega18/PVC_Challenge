<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class AgedBrieUpdater extends StandardUpdater
{
    public function update(Item $item): void
    {
        $this->increaseQuality($item);

        $item->sellIn--;

        if ($item->sellIn < 0) {
            $this->increaseQuality($item);
        }
    }
}