# Query

`m4\m4mvc\helper\Query`

Helps with task of creating SQL queries. 

  > You need to create instance before use, but if you extend our core Model, it holds instance of this class in `query` param, so you can access it with `$this->query`

It works by chaining methods and then using `build()` as the last one. 

## Example

``` php
<?php
$query = $this->query->select('name', 'age')->from('users')->where('id = :id')->build();
$query = $this->query->select('t.hash', 'u.name')->from('users u')->join('left', 'tokens t', 't.user_id = u.id')->limit(10)->groupBy('u.name')->orderBy('u.name DESC')->build();
$query = $this->query->insert('name', 'age')->into('users')->build();
$query = $this->query->update('users')->set('name', 'age')->where('id = :id');
$query = $this->query->delete('users')->where('id = :id');
```

## Methods 

There are 4 action types
#### 1. select [no argument or column strings]
- **from** specifies table [string]
- **limit** [int or string]
- **join** [type, table, on] \[strings\]
- **groupBy** column name [string]
- **orderBy** column name [string], type (ASC / DESC) [string] 

----------------

#### 2. insert [no argument or column strings]
- **into** specifies table [string]

----------------

#### 3. update [table name string]
- **set** list of columns to update [array]

----------------

#### 4. delete [table name string]

-----------------

#### Common methods
- **where** where block [string], type: 1, 3, 4

