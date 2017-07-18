# Query

`m4\m4mvc\helper\Query`

Helps with task of creating SQL queries. 

  > You need to create instance before use, but if you extend our core Model, it holds instance of this class in `query` param, so you can access it with `$this->query`

It works by chaining methods and then using `build()` as the last one. 

## Example

``` php
$query = $this->query->
```

