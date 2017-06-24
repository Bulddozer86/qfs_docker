<?php


namespace FlatParserBundle\Resources\Services;


class UrlHelper
{
    public static function restoreBasicEntities($str)
    {
        return str_replace(
          ['[&]', '[&amp;]', '[lt]', '[gt]', '[nbsp]', '[-]'],
          ['&amp;', '&amp;', '&lt;', '&gt;', '&nbsp;', '&shy;'],
          $str
        );
    }

    public function standardize($str)
    {
        $arrSearch = ['/[^a-zA-Z0-9 \.\&\/_-]+/', '/[ \.\&\/-]+/'];
        $arrReplace = ['', '-'];

        $str = html_entity_decode($str, ENT_QUOTES, 'utf-8');
        $str = utf8_romanize($str);
        $str = preg_replace($arrSearch, $arrReplace, $str);

        if (is_numeric(substr($str, 0, 1))) {
            $str = 'id-'.$str;
        }

        $str = strtolower($str);

        return trim($str, '-');
    }

    public static function slugify($string)
    {
       // $string = self::restoreBasicEntities($string);
        // replace all non letters or digits by -
        $string = preg_replace('/\W+/', '-', $string);

        // trim and lowercase
        $string = strtolower(trim($string, '-'));

        return $string;

    }
}