<?php

use Kirby\Exception\InvalidArgumentException;
use Kirby\Toolkit\Str;
use Kirby\Toolkit\Query;
use Kirby\Cms\Section;

$base = Section::$types['pages'];

return array_replace_recursive($base, [
    'props' => [
        'sortable' => function (bool $sortable = true) {
            return false;
        },
        'query' => function(string $query = 'page.children') {
            return $query;
        }
    ],
    'computed' => [
       'pages' => function () {
            $q = new Query($this->query, [
                'site' => site(),
                'page' => $this->model(),
                'pages' => site()->pages()
            ]);

            $pages = $q->result();

            if(is_a($pages, 'Kirby\\Cms\\Pages')) {
                // sort
                if ($this->sortBy) {
                    $pages = $pages->sortBy(...Str::split($this->sortBy, ' '));
                }
                
                // pagination
                $pages = $pages->paginate([
                    'page' => $this->page,
                    'limit' => $this->limit
                ]);

                return $pages;
            } else {
                throw new InvalidArgumentException(
                    'Invalid query result - Result must be of type Kirby\\Cms\\Pages, ' 
                    . ($pages === NULL ? 'NULL' : get_class($pages))
                    . ' given.'
                );
            }
        },
        'add' => function () {
            return false;
        },
        'sortable' => function () {
            return false;
        }
    ],
]);
