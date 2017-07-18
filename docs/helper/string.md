# String

`m4\m4mvc\helper\Str`

[Str](https://github.com/Matoo125/m4mvc/blob/master/src/helper/Str.php) helper simplifies different string tasks. All methods and properties are static.

## Properies
1. lang - array of language strings to be used with `getLang` method
2. url - array of url's to be used with `getUrl` method

## Methods
----------
### getUrl 
**argument**: *key*

**description**: value will be returned based on key in `Helper::$url` array. If key is null it will return whole array.

--------------

### getLang
**argument**: *key*

**description**: value will be returned based on key in `Helper::$lang` array. If key is null it will return whole array.


----------

### slugify
**argument**: *string*

**description** - Slug will be returned of passed string. (Make me Slug) => (make-me-slug)

-------

### removeAccent
**argument**: *string*

**description** - Text without accent will be returned (čťžýáíé) => (ctzyaie)