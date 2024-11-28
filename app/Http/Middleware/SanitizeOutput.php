<?php

/**
 * Возвращает итоговый HTML в сжатом состоянии: удаляются лишние пробелы, переносы строки и т.п.
 */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeOutput
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isLocal()) {
            return $next($request);
        }

        $response = $next($request);

        $buffer = $response->getContent();

        // Searching textarea and pre
        preg_match_all('#\<textarea.*\>.*\<\/textarea\>#Uis', $buffer, $foundTxt);
        preg_match_all('#\<pre.*\>.*\<\/pre\>#Uis', $buffer, $foundPre);

        // replacing both with <textarea>$index</textarea> / <pre>$index</pre>
        $buffer = str_replace(
            $foundTxt[0],
            array_map(function ($el) {
                return '<textarea>' . $el . '</textarea>';
            }, array_keys($foundTxt[0])),
            $buffer
        );
        $buffer = str_replace(
            $foundPre[0],
            array_map(function ($el) {
                return '<pre>' . $el . '</pre>';
            }, array_keys($foundPre[0])),
            $buffer
        );

        // your stuff
        $search = array(
            '/\>[^\S ]+/s',  // strip whitespaces after tags, except space
            '/[^\S ]+\</s',  // strip whitespaces before tags, except space
            '/(\s)+/s'       // shorten multiple whitespace sequences
        );

        $replace = array(
            '>',
            '<',
            '\\1'
        );

        // Если на сервере выдается ошибка PREG_RECURSION_LIMIT_ERROR,
        // то в .htaccess добавляется параметр: php_value pcre.recursion_limit 100000
        // Если возникает ошибка, то вернем исходное состояние.
        $buffer = preg_replace($search, $replace, $buffer);
        if (preg_last_error() != PREG_NO_ERROR) {
            return $response;
        }

        // Replacing back with content
        $buffer = str_replace(
            array_map(function ($el) {
                return '<textarea>' . $el . '</textarea>';
            }, array_keys($foundTxt[0])),
            $foundTxt[0],
            $buffer
        );

        $buffer = str_replace(
            array_map(function ($el) {
                return '<pre>' . $el . '</pre>';
            }, array_keys($foundPre[0])),
            $foundPre[0],
            $buffer
        );

        $buffer = str_replace('> <', '><', $buffer);

        $response->setContent($buffer);

        return $response;
    }
}
