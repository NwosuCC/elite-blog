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
        if(!$name && !auth()->user()){
            return '';
        }

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

if (! function_exists('set_flash')) {
    /**
     * Set a flash message with the alert type
     *
     * @param  string  $message The message to display
     * @param  string  $type    'danger' | 'warning' | 'info' | 'success'
     *
     * @return void
     */
    function set_flash($message, $type = 'success')
    {
        session()->flash('message', trim($message) . '|' . trim($type));
    }
}

if (! function_exists('get_flash')) {
    /**
     * Retrieve a previously set flash message for display
     *
     * @return array
     */
    function get_flash()
    {
        if(session('message')) {
            $flash = explode('|', session('message'));

            return array_combine( ['message', 'type'], array_pad($flash, 2, ''));
        }

        return [];
    }
}

