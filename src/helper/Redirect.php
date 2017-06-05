<?php 

namespace m4\m4mvc\helper;

use app\helper\Str;

/*
 *  Redirect helper class
 *  handles redirect operations
 */
class Redirect
{
    public static function to($url)
    {
        header("Location: " . $url);
        exit();
    }
    public static function toURL($key)
    {
      return self::to(Strings::getUrl($key));
    }
}