<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class SulfurasUpdater implements ItemUpdater
{
    public function update(Item $item): void
    {
        // Sulfuras never has to be sold or decreases in Quality
    }
}