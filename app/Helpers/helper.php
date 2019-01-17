<?php

use Illuminate\Support\Str;


if (! function_exists('user_slug')) {
    /**
     * Generate the slug for the authenticated user's name
     *
     * @param  string  $name
     *
     * @return string
     */
    function user_slug($name = '')
    {
        return str_slug( $name ?: auth()->user()->name );
    }
}

if (! function_exists('str_words')) {
    /**
     * Limit the number of words in a string
     *
     * @param  string  $value
     * @param  int  $words
     * @param  string  $end
     *
     * @return string
     */
    function str_words($value, $words = 100, $end = '...')
    {
        return Str::words($value, $words, $end);
    }
}

