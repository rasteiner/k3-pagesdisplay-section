<?php

use Kirby\Cms\App;
use Kirby\Cms\Blueprint;
use Kirby\Toolkit\F;
use Kirby\Toolkit\Str;

Kirby::plugin('rasteiner/k3-pagesdisplay-section', [
    'sections' => [
        'pagesdisplay' => require __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'PagesDisplaySection.php'
    ]
]);