<?php
/**
 * Assets static helper
 */

namespace JDOUnivers\Helpers;

/**
 * Collect and output css and js link tags.
 */
class Assets
{
    /**
     * @var array Asset templates
     */
    protected static $templates = array
    (
        'js'  => '<script src="%s" type="text/javascript"></script>',
        'css' => '<link href="%s" rel="stylesheet" type="text/css">'
    );

    /**
     * Common templates for assets.
     *
     * @param string|array $files
     * @param string       $template
     */
    protected static function resource($files, $template)
    {
        $template = self::$templates[$template];

        if (is_array($files)) {
            foreach ($files as $file) {
                echo sprintf($template, $file) . "\n";
            }
        } else {
            echo sprintf($template, $files) . "\n";
        }
    }

    /**
     * Output script.
     *
     * @param array|string $file/s
     */
    public static function js($files)
    {
        static::resource($files, 'js');
    }

    /**
     * Output stylesheet.
     *
     * @param string $file
     */
    public static function css($files)
    {
        static::resource($files, 'css');
    }
}
