<?php 

namespace m4\m4mvc\core;

class Module
{
  // list of modules
  public static $list = [];

  // string of active module
  public static $active = null;

  // register modules to use
  public static function register (array $modules)
  {
    self::$list = array_merge(self::$list, $modules);
    self::$active = array_keys(self::$list)[0];
  }

  // set active module from url
  public static function set (array $url)
  {
    if (in_array($url[0], array_keys(self::$list))) {
      self::$active = $url[0];
      array_shift($url);
    } 
    return $url; 
  }

  public static function render ()
  {
    return self::$list[self::$active]['render'];
  }

  public static function folder ()
  {
    return self::$list[self::$active]['folder'];
  }
}