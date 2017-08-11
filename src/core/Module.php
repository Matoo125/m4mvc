<?php 

namespace m4\m4mvc\core;

class Module
{
  public static $list = [];

  public static $active = null;

  public static function register ($modules)
  {
    self::$list = array_merge(self::$list, $modules);
    self::$active = self::$list[0];

  }

  public static function set ($url)
  {
    if (in_array($url[0], self::$list)) {
      self::$active = $url[0];
      array_shift($url);
      return $url;
    } 
    else { 
      return $url; 
    }
  }
}