<?php declare(strict_types=1);

namespace SharedBookshelf\Controller\Settings;

use ArrayIterator;
use IteratorAggregate;

class Collection implements IteratorAggregate
{
    private array $settings = [];

    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->settings);
    }
}
