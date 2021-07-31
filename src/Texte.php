<?php
/**
 * url Class
 *
 */

namespace JDOUnivers\Helpers;

/**
 * Collection of methods for working with textes.
 */
class Texte
{
    /**
     * Show max a number of characters of a text and put "..." at the end
     *
     * @param  string $text "texte a afficher"
     * @return int $nbChar "number of characters"
     */
    public static function HTMLtoText($html)
    {
        // Remove the HTML tags
        $text = strip_tags($html);
        // Convert HTML entities to single characters
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

        return $text;
    }

    /**
     * Show max a number of characters of a text and put "..." at the end
     *
     * @param  string $text "texte a afficher"
     * @return int $nbChar "number of characters"
     */
    public static function getTextLimited($text, $nbChar)
    {
        $text = Texte::HTMLtoText($text);
        if(strlen($text)<=$nbChar)
            return $text;
        else
            return mb_substr($text, 0, ($nbChar - 4), 'UTF-8') . " ...";
    }
}
