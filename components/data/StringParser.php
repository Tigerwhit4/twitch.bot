<?php
/**
 * Created by PhpStorm.
 * User: terazoid
 * Date: 7/30/14
 * Time: 10:59 PM
 */

namespace app\components\data;


use yii\base\ErrorException;

class StringParser {
    public static function getStringBetween($before, $after, $haystack)
    {
        $posBefore = strpos($haystack, $before);
        if(false === $posBefore) {
            throw new ErrorException('string $before not found in $haystack');
        }
        $posAfter = strpos($haystack, $after, $posBefore + strlen($before));
        if(false === $posAfter) {
            throw new ErrorException('string $before not found in $haystack');
        }
        return substr($haystack, $posBefore+strlen($before), $posAfter - ($posBefore + strlen($before)));
    }
} 
