<?php 

namespace m4\m4mvc\core;

use m4\m4mvc\helper\Query;
use m4\m4mvc\helper\Image;

abstract class Model
{
  // Db credentials array
  public static $credentials;
  // Db adapter string
  public static $adapter = 'mysql';
  // Query helper object
  public $query;
  // DB connection object
  public $db;

  public function __construct()
  {
    $this->query = new Query;
    $this->db = self::getDb();
  }

  // @returns $db connection
  protected static function getDB()
  {
    static $db = null;

    if ($db === null) {
      try {
        if (self::$adapter === 'sqlite') {
          $db = new \PDO("sqlite:" . self::$credentials['path']);
        }
        else {
          $dns = 'mysql:host=' . self::$credentials['DB_HOST'] . 
             ';dbname=' . self::$credentials['DB_NAME'] . 
             ';charset=utf8';

          $db = new \PDO($dns, 
            self::$credentials['DB_USER'], 
            self::$credentials['DB_PASSWORD']
          );
        }

        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $db;
      } catch (\PDOException $e) {
        echo $e->getMessage();
        return null;
      }
    }
    return $db;
  }

  // To insert or update record
  // retuns boolean
  // return lastinserted id if type is 1
  public function save(string $query, 
                       array $params = null, 
                       bool $lastInsertedId = null)
  {
    if ($lastInsertedId) {
    return self::runQuery($query, $params, 4);
    }
    return self::runQuery($query, $params, 3);
  }

  // Fetch a single row
  public function fetch($query, $params = [])
  {
    return self::runQuery($query, $params, 1);
  }

  // Fetch multiple rows
  public function fetchAll($query, $params = [])
  {
    return self::runQuery($query, $params, 2);
  }

  /*
    Runs query and returns result based on $type
   */
  public static function runQuery($query, $params, $type)
  {
    $stmt = self::getDb()->prepare($query);
    try {
    $stmt->execute($params);
    } catch (\Exception $e) {
    echo 'error: <br>';
    echo 'Query: ' . $query . "<br>";
    echo 'Params: <pre>'; print_r($params); echo '</pre>';
    echo 'Type: ' . $type . "<br>";
    echo "Exception: " . $e;
     die;
    }
    switch ($type) {
    case 2:
      if ($results = $stmt->fetchAll(\PDO::FETCH_ASSOC)) {
       return $results;
      }
      break;
    case 1:
      if ($result = $stmt->fetch(\PDO::FETCH_ASSOC)) {
      return $result;
      }
      break;
    case 3:
      return $stmt->rowCount() ? true : false;
      break;
    case 4:
      return self::getDb()->lastInsertId();
      break;
    }
  }

  /*
    Uploads image
   */
  public function image($image, $folder)
  {
    if ($image && Image::upload($image, $folder)) {
    $query = $this->query->insert('folder', 'name', 'type', 'size')->into('images')->build();
    $params = [
      'folder'  =>  static::$table,
      'name'    =>  $image['name'],
      'type'    =>  $image['type'],
      'size'    =>  $image['size']
    ];
    return $this->save($query, $params, 1);
    }
    return null;
  }

  /*
   * Count rows in table
   * @param $where adds where clause to query
   * @param $like adds like clause to query
   * @return number of rows
   */
  public function countTable($table, $where = null, $like = null) {
    $db = self::getDB();
    $sql = "SELECT count(*) FROM " . $table;
    if ($where) $sql .= " WHERE " . $where;
    if ($like) $sql .= " LIKE '" . $like . "'";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    return $result ? $result[0] : null;
  }


  /* new functions - experimental */
  public function add ($data)
  {
    $q = $this->query->insert(...array_keys($data))->into(static::$table);
    return $this->save($q->build(), $data, true);
  }

  public function list ($filters = [])
  {
    $q = $this->query->select()
                     ->from(static::$table)
                     ->where(array_keys($filters));
    return $this->fetchAll($q->build(), $filters);
  }

  public function find ($filters)
  {
    $q = $this->query->select()
                     ->from(static::$table);
    $where = [];
    foreach ($filters as $key => $value) {
      $where[] =  $key . " = :" . $key . " "; 
    }
    $q->where(implode(' AND ', $where));
    return $this->fetch($q->build(), $filters);
  }

  public function getColumnNames ($table = null)
  {
    $table = $table ?? static::$table;
    $columns = $this->fetchAll("DESCRIBE " . $table);
    return $columns;
  }


  /* end of new functions */
}