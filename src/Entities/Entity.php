<?php

namespace SharedBookshelf\Entities;

use Doctrine\ORM\Mapping\ClassMetadata;

interface Entity
{
    public static function loadMetadata(ClassMetadata $metadata): void;
}
