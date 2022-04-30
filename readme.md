# Pages Display Section

Display any page list in a section using [Kirby's query language](https://getkirby.com/docs/guide/blueprints/query-language). Any parent, many parents, filtered, don't care.

> ℹ️ Note: While this functionality gives you a lot of freedom, you won't be able to sort the list or add new pages to the query.

## Installation

### Download

Download and copy this repository to `/site/plugins/k3-pagesdisplay-section`.

### Git submodule

```bash
git submodule add https://github.com/rasteiner/k3-pagesdisplay-section.git site/plugins/k3-pagesdisplay-section
```

### Composer

```bash
composer require rasteiner/k3-pagesdisplay-section
```

## Usage

Create a section of your liking and add a `query` property. Within the query you may select and filter any pages by making use of [Kirby's query language](https://getkirby.com/docs/guide/blueprints/query-language).

You can start the query with one of the following variables:

- `site`
- `page` (refers to the current page)
- `pages` (which equals `site.pages`)
- `kirby` (mainly to use with `kirby.collection`)

## Example

### All pages with `Foo` in their title

```yaml
sections:
  mysection:
    headline: Foo Pages
    type: pagesdisplay
    query: site.index.filterBy('title', '*=', 'Foo')
```

## Sibling pages (exclude the current page)

```yaml
sections:
  mysection:
    headline: Siblings
    type: pagesdisplay
    query: page.siblings(false)
```

### Disable Controls

In addition to leaving the controls (the status flag and the options dropdown) visible - the default, it's possible to either hide them completely or show only the status flag. 

To completely hide the controls:
```yaml 
sections:
  mysection:
    headline: Siblings
    type: pagesdisplay
    query: page.siblings(false)
    controls: false
```

To show only the status flag:
```yaml 
sections:
  mysection:
    headline: Siblings
    type: pagesdisplay
    query: page.siblings(false)
    controls: flag
```
