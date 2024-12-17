<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([__DIR__ . '/src'])
    ->withSets([
        LevelSetList::UP_TO_PHP_84,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ])
    ->withPhpVersion(PhpVersion::PHP_84);
