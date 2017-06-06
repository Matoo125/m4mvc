<?php 

namespace m4\m4mvc\helper;

class Session {
    /*
     * Flash message to be shown
     * array[message, style] or null
     */
    protected static $flash_message = null;
    /*
     * @param $message to be shown
     * @param $style of the message (warning, danger, info, success)
     * @param $type of storing the message:
     * 1 - store flash in Session  (survives redirect)
     * 0 - store flash in property (cannot redirect) default
    */
    public static function setFlash($message, $style = null, $type = 0) {
        if ($type === 1) {
            self::set("message", $message);
            self::set("style", $style);
        } else {
           self::$flash_message['message'] = $message;
           self::$flash_message['style'] = $style;
        }
    }
    // @return boolean
    public static function hasFlash(){
        return !is_null(self::$flash_message) || !is_null(self::get("message"));
    }
    // @echoes flash style
    public static function flashStyle() {
            echo self::$flash_message['style'] . self::get('style');
            self::delete('style');
    }
    // @echoes flash message
    public static function flash() {
        echo self::$flash_message['message'] . self::get('message');
        self::$flash_message = null;
        self::delete('message');
    }
    /*
     * Set function sets the Session
     * @param $key String
     * @param $value Mixed
     */
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    /*
     * Get function gets the value
     * of Session key
     * @param $key to be returned
     * or null
     */
    public static function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }
    /*
     *  Function delete
     *  @param $key - to be deleted String
     *  @return nothing
     */
    public static function delete($key) {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    /*
     *  Function destroy
     *  wipe whole session.
     *  The end!
     */
    public static function destroy() {
        session_destroy();
    }

}