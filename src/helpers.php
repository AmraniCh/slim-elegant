<?php

if (!function_exists('config')) {

    function config($key)
    {
        global $app;

        $settings = $app->getContainer()->get('settings');

        if (!$settings->has($key)) {
            throw new \LogicException("'$key' configuration variable not found.");
        }

        $value = $settings->get($key);

        if ($value instanceof \Closure) {
            return call_user_func($value);
        }
        
        if ($value === 'true') {
            return true;
        }
        
        if ($value === 'false') {
            return false;
        }

        return $value;
    }
}
