# Request

`m4\m4mvc\helper\Request`

## Methods

-----------------

### forceMethod

**argument**: *string|array* type or list of types

**type**: static

**returns**: error if message if method is not allowed, or true

**usage**:
```php
<?php Request::forceMethod('post'); ?>
```
------------------

### required

**arguments**: *strings* required parameters of request.

**type**: static

**returns**: error if required data not found, or true

**usage**:
```php
<?php Request::required('id', 'username', 'password'); ?>
```
-----------------

### handle

**type**: static

**description**:  adds POST or GET request data to Request::data parameter. 

**returns**: parseUrl()

**usage**:
```php
<?php $url = Request::handle(); ?>
```
----------------------

### getRequestType

**type**: static

**returns**: request method

**usage**:
```php
<?php $requestType = Request::getRequestType(); ?>
```

--------------------------

### jsonPost

**type**: static

**returns**: array, json Post content

--------------------------

### select
**arguments**: array keys to select
**type**: static
**returns**: array of data
**usage**
```php
<?php $data = Request::select('username', 'password'); ?>
```

------------------

### parseUrl

**returns**: parsed `$_GET['url']`, array

-------------------

### mapUrl

**argument**: array

**description**: changes GET['url']

**usage**: 
```php
<?php 
Request::mapUrl([
  'cm'        => 'controller/method',
  'register'  =>  'users/register'
]);
?>
``` 