<?php
namespace ItForFree\rusphp\Network\Http;
use ItForFree\rusphp\Network\Url\Url;

/**
 * Для работы с http-запросом
 *
 */
class Request
{
    /**
     * Возвращает ссылку на домен источник запроса, если его удалось определить,
     *  в противном случае ip адрес.
     * Если заголовок HTTP_ORIGIN не установлен -- проверяются другие.
     * 
     * @return \ItForFree\rusphp\Network\Url\Url
     */
    public static function getOrigin()
    {
        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $origin = $_SERVER['HTTP_REFERER'];
        } else {
            $origin = $_SERVER['REMOTE_ADDR'];
        }
        $originUrl = new Url($origin);
        return $originUrl;
    }        

}
