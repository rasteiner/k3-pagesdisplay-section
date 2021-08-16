<?php

\Kirby\Cms\App::plugin('rasteiner/k3-pagesdisplay-section', [
    'sections' => [
        'pagesdisplay' => require __DIR__ . '/src/PagesDisplaySection.php'
    ]
]);