<?php

use Kirby\Exception\InvalidArgumentException;
use Kirby\Cms\Section;
use Kirby\Toolkit\Query;

$base = Section::$types['pages'];

if (is_string($base)) {
    $base = include $base;
}

$extension = [
    'props' => [
        'sortable' => function (bool $sortable = true) {
            return false;
        },
        'query' => function (string $query = 'page.children') {
            return $query;
        },
        'controls' => function ($controls = true) {
            if(!is_bool($controls) && $controls !== 'flag') {
                throw new InvalidArgumentException('Invalid value for "controls" option. It must be either true, false or "flag"');
            }

            return $controls;
        },
        // kirby does magic checks in mixins to see if this is a "pages" section. Fake that, will be reset in toArray. 
        'type' => fn() => 'pages',
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

            // filters pages that are protected and not in the templates list
            // internal `filter()` method used instead of foreach loop that previously included `unset()`
            // because `unset()` is updating the original data, `filter()` is just filtering
            // also it has been tested that there is no performance difference
            // even in 0.1 seconds on 100k virtual pages
            $pages = $pages->filter(function ($page) {
                // remove all protected pages
                if ($page->isReadable() === false) {
                    return false;
                }

                // filter by all set templates
                if ($this->templates && in_array($page->intendedTemplate()->name(), $this->templates) === false) {
                    return false;
                }

                return true;
            });

            // search
            if ($this->search === true && empty($this->searchterm()) === false) {
                $pages = $pages->search($this->searchterm());
            }

            // sort
            if ($this->sortBy) {
                $pages = $pages->sort(...$pages::sortArgs($this->sortBy));
            }

            // flip
            if ($this->flip === true) {
                $pages = $pages->flip();
            }

            // pagination
            $pages = $pages->paginate([
                'page'   => $this->page,
                'limit'  => $this->limit,
                'method' => 'none' // the page is manually provided
            ]);

            return $pages;

        },
        'add' => function () {
            return false;
        },
        'sortable' => function () {
            return false;
        }
    ],
    'toArray' => fn() => Closure::fromCallable($base['toArray'])->call($this) + [ 'type' => 'pagesdisplay' ]
];

return array_replace_recursive($base, $extension);
