<?php
/**
 * Hooks controller
 */

namespace JDOUnivers\Helpers;

/**
 * Hooks allow code to be injected into various parts of the framework.
 */
class Hooks
{
    /**
     * Array of plugins.
     * @var array
     */
    private static $plugins = array();

    /**
     * Array of available hooks.
     *
     * @var array
     */
    private static $hooks = array();

    /**
     * Array of instances - for the purpose of reusing the same instance.
     *
     * @var array
     */
    private static $instances = array();

    /**
     * Initial hooks.
     *
     * @param  integer $id
     *
     * @return $instance
     */
    public static function get($id = 0)
    {
        // return if instance exists
        if (isset(self::$instances[$id])) {
            return self::$instances[$id];
        }

        //define hooks
        self::setHooks(array(
            'meta',
            'css',
            'afterBody',
            'footer',
            'js',
            'routes'
        ));

        $instance = new self();
        self::$instances[$id] = $instance;
        return $instance;
    }

    /**
     * Adds hook to hook list.
     *
     * @param string $where Hook to add.
     */
    public static function setHook($where)
    {
        self::$hooks[$where] = '';
    }

    /**
     * Add multiple hooks.
     *
     * @param array $where array of hooks to add.
     */
    public static function setHooks($where)
    {
        foreach ($where as $where) {
            self::setHook($where);
        }
    }

    /**
     * Load all modules found in folder.
     *
     * Only load modulename.module.php files
     *
     * @param  string $fromFolder path to the folder.
     */
    public static function loadPlugins($fromFolder)
    {
        if ($handle = opendir($fromFolder)) {
            while ($file = readdir($handle)) {
                if (is_file($fromFolder.$file)) {
                    //only load modulename.module.php file
                    if(preg_match('@module.php@', $file)) {
                        require_once $fromFolder . $file;
                    }
                    self::$plugins [$file] ['file'] = $file;
                } elseif ((is_dir($fromFolder.$file)) && ($file != '.') && ($file != '..')) {
                    self::loadPlugins($fromFolder.$file.'/');
                }
            }
            closedir($handle);
        }
    }

    /**
     * Attach custom function to hook.
     *
     * @param string $where hook to use
     * @param string $function function to attach to hook
     */
    public static function addHook($where, $function)
    {
        if (!isset(self::$hooks[$where])) {
            die("There is no such place ($where) for hooks.");
        } else {
            $theseHooks = explode('|', self::$hooks[$where]);
            $theseHooks[] = $function;
            self::$hooks[$where] = implode('|', $theseHooks);

        }
    }

    /**
     * Run all hooks attached to the hook.
     *
     * @param  string $where Hook to execute
     * @param  string $args option arguments
     *
     * @return object - returns the called function
     */
    public function run($where, $args = '')
    {
        if (isset(self::$hooks[$where])) {
            $theseHooks = explode('|', self::$hooks[$where]);
            $result = $args;

            foreach ($theseHooks as $hook) {
                if (preg_match("/@/i", $hook)) {
                    //grab all parts based on a / separator
                    $parts = explode('/', $hook);

                    //collect the last index of the array
                    $last = end($parts);

                    //grab the controller name and method call
                    $segments = explode('@', $last);

                    $classname = new $segments[0]();
                    $result = call_user_func(array($classname, $segments[1]), $result);

                } else {
                    if (function_exists($hook)) {
                        $result = call_user_func($hook, $result);
                    }
                }
            }

            return $result;
        } else {
            die("There is no such place ($where) for hooks.");
        }
    }

    /**
     * Execute hooks attached to run and collect instead of running
     *
     * @param  string $where hook
     * @param  string $args optional arguments
     * @return object - returns output of hook call
     */
    public function collectHook($where, $args = null)
    {
        ob_start();
            echo $this->run($where, $args);
        return ob_get_clean();
    }
}
