<?php

namespace JDOUnivers\Helpers;

class Analytics
{
    public static function get_html()
    {
        return '<!-- Global Site Tag (gtag.js) - Google Analytics -->
                <script async src="https://www.googletagmanager.com/gtag/js?id=' . getenv("ANALYTICS_KEY") . '"></script>
                <script>
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag(\'js\', new Date());

                gtag(\'config\', \'' . getenv("ANALYTICS_KEY") . '\');
                </script>';
    }
}
