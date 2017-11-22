<?php 

namespace m4\m4mvc\helper;


/*
 *  Query helper class
 *  builds MySql queries
 *  written by Matej Vrzala
 *  created at: 7.5.2017
 */
class Query
{
    private $action;
    private $columns = array();
    private $values = array();
    private $set = array();
    private $table;
    private $where;
    private $limit;
    private $join;
    private $groupBy;
    private $orderBy = array();

    private $insertSubqueries = array();

    public function select()
    {
        $this->columns = func_get_args();
        $this->action = 1;
        return $this;
    }
    public function insert()
    {
      $this->columns = func_get_args();
      $this->action = 2;
      return $this;
    }
    public function insertSubquery (array $subquery)
    {
      $this->insertSubqueries = array_merge($this->insertSubqueries, $subquery);
      return $this;
    }
    public function update($table)
    {
      $this->table = $table;
      $this->action = 3;
      return $this;
    }
    public function delete($table)
    {
      $this->table = $table;
      $this->action = 4;
      return $this;
    }
    public function from($table)
    {
      $this->table = $table;
      return $this;
    }
    public function into($table)
    {
      $this->table = $table;
      return $this;
    }
    public function where($where)
    {
      if (is_array($where)) {
       foreach ($where as $key) {
         $where[$key] = $key . " = :" . $key . " ";
       }
       $this->where = implode(' AND ', $where);
      }
      else if(is_string($where)) {
        $this->where = $where;
      }
      return $this;
    }
    public function limit($limit)
    {
      $this->limit = $limit;
      return $this;
    }
    public function join($type, $table, $on)
    {
      $this->join .= strtoupper($type) . " JOIN " . $table . " ON " . $on . " ";
      return $this;
    }
    public function set($to_set)
    {
      foreach($to_set as $s) {
        $this->set[] = $s . " = :" . $s;
      }
      return $this;
    }
    public function groupBy($by)
    {
      $this->groupBy = $by;
      return $this;
    }
    public function orderBy($what, $how)
    {
      array_push($this->orderBy, $what . " " . $how);
      return $this;
    }
    public function build()
    {
      if (!$this->table){
        throw new \Exception("Query could not be build. Missing table");
      }
      switch($this->action) {
        // select
        case 1:
          $query = "SELECT ";
          if (empty($this->columns)) {
            $query .= "* ";
          } else {
            $query .= implode(', ', $this->columns) . " ";
          }
          $query .= "FROM " . $this->table . " ";
          if (!empty($this->join)) {
            $query .= $this->join . " ";
            $this->join = null;
          }
          if (!empty($this->where)) {
            $query .= "WHERE ";
            $query .= $this->where;
            $query .= " ";
            $this->where = null;
          }
          if (!empty($this->groupBy)) {
            $query .= " GROUP BY ";
            $query .= $this->groupBy;
            $this->groupBy = null;
          }
          if (!empty($this->orderBy)) {
            $query .= " ORDER BY ";
            $query .= implode(', ', $this->orderBy) . ' ';
            $this->orderBy = [];
          }
          if (!empty($this->limit)) {
            $query .= "LIMIT ";
            $query .= $this->limit;
            $this->limit = null;
          }
          return $query;
          break;
        // insert
        case 2:
          $subqueries = [
              'columns' => (
                $this->insertSubqueries ?
                ', ' . implode(',', array_keys($this->insertSubqueries)) : ''
              ),
              'values' => ($this->insertSubqueries ? 
                      ', ' . implode(', ', $this->insertSubqueries) : '')
          ];
          
          $query = "INSERT INTO " . $this->table . " ";
          $query .= "(" . 
                      implode(', ', $this->columns) . 
                      $subqueries['columns'] .
                    ") ";
          $query .= "VALUES (:" . 
                     implode(', :', $this->columns) . 
                     $subqueries['values'] .
                     ") ";
          return $query;
        // update
        case 3:
          $query = "UPDATE " . $this->table . " ";
          $query .= "SET " . implode(', ', $this->set) . " ";
          if (!empty($this->where)) {
            $query .= "WHERE ".  $this->where;
          }
          return $query;
        // delete
        case 4:
          $query = "DELETE FROM " . $this->table . " ";
          $query .= "WHERE " . $this->where;
          return $query;
        // error
        default:
          throw new \Exception("Query could not be build. No action selected. ");
          break;
      }
    }
}