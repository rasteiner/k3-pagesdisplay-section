<?php

use Kirby\Exception\InvalidArgumentException;
use Kirby\Cms\Section;
use Kirby\Toolkit\Query;

$base = Section::$types['pages'];
$extension = [
    'props' => [
        'sortable' => function (bool $sortable = true) {
            return false;
        },
        'query' => function (string $query = 'page.children') {
            return $query;
        }
    ],
    'computed' => [
        'pages' => function () {
            $kirby = kirby();
            $q = new Query($this->query, [
                'kirby' => $kirby,
                'site' => $kirby->site(),
                'pages' => $kirby->site()->pages(),
                'page' => $this->model()
            ]);

            $pages = $q->result();

            if (!is_a($pages, \Kirby\Cms\Pages::class)) {
                $result = $pages === null ? 'null' : get_class($pages);
                throw new InvalidArgumentException(
                    "Query result must be of type \"Kirby\\Cms\\Pages\", \"{$result}\" given"
                );
            }

            // Loop for the best performance
            foreach ($pages->data as $id => $page) {
                // Remove all protected pages
                if (!$page->isReadable()) {
                    unset($pages->data[$id]);
                    continue;
                }

                // Filter by all set templates
                if ($this->templates && !in_array($page->intendedTemplate()->name(), $this->templates)) {
                    unset($pages->data[$id]);
                    continue;
                }
            }

            // Sort pages
            if ($this->sortBy) {
                $pages = $pages->sort(...$pages::sortArgs($this->sortBy));
            }

            // Flip pages
            if ($this->flip) {
                $pages = $pages->flip();
            }

            // Add pagination
            $pages = $pages->paginate([
                'page' => $this->page,
                'limit' => $this->limit
            ]);

            return $pages;
        },
        'add' => function () {
            return false;
        },
        'sortable' => function () {
            return false;
        }
    ]
];

return array_replace_recursive($base, $extension);
