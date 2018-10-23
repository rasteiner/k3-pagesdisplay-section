Display any page list in a Section. Any parent, many parents, filtered, don't care.   
On the other hand, you won't be able to sort the list or add new pages to it.

# Install
## Download Zip file

Copy plugin folder into `site/plugins`

## Composer
Run `composer require rasteiner/k3-pagesdisplay-section`.

# Usage
You select and filter the pages with the query language, with a `query` property in the section yaml. 
You can start the query with `site`, `page` (refers to the current page), or `pages` (which is equal to `site.pages`).

## Example
Show all pages that have "Foo" in their title:

```yaml
sections:
  mysection:
    headline: Foo Pages
    type: pagesdisplay
    query: site.index.filterBy(title, *=, Foo)
```


Show sibling pages (exclude current page):

```yaml
sections:
  mysection:
    headline: Siblings
    type: pagesdisplay
    query: page.siblings(false)
```
