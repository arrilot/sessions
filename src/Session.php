<?php

namespace Arrilot\Sessions;

use Illuminate\Support\Arr;

class Session
{
    /**
     * Age flash data.
     *
     * @return void
     */
    public static function ageFlashData()
    {
        $new = static::pull('flash.new', []);

        static::set('flash.old', $new);
    }

    /**
     * Get all of the items from the session.
     *
     * @return array
     */
    public static function all()
    {
        return $_SESSION;
    }

    /**
     * Remove all of the items from the session.
     *
     * @return array
     */
    public static function clear()
    {
        static::flush();
    }

    /**
     * Flash data for a single request.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function flash($key, $value)
    {
        static::put($key, $value);

        static::push('flash.new', $key);

        static::removeFromOldFlashData([$key]);
    }

    /**
     * Remove all of the items from the session.
     *
     * @return array
     */
    public static function flush()
    {
        session_unset();
    }

    /**
     * Remove an item from the session.
     *
     * @param string $key
     *
     * @return void
     */
    public static function forget($key)
    {
        Arr::forget($_SESSION, $key);
    }

    /**
     * Get an item from the session.
     *
     * @param $name
     * @param $default
     *
     * @return mixed
     */
    public static function get($name, $default = null)
    {
        return Arr::get($_SESSION, $name, $default);
    }

    /**
     * Determining if an item exists in the session.
     *
     * @param $name
     *
     * @return bool
     */
    public static function has($name)
    {
        return !is_null(static::get($name));
    }

    /**
     * Reflash a subset of the current flash data.
     *
     * @param $keys
     *
     * @return void
     */
    public static function keep($keys = null)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        static::mergeWithCurrentFlashes($keys);
        static::removeFromOldFlashData($keys);
    }

    /**
     * Get the value of a given key and then forget it.
     *
     * @param $key
     * @param $default
     *
     * @return mixed
     */
    public static function pull($key, $default = null)
    {
        return Arr::pull($_SESSION, $key, $default);
    }

    /**
     * Push a value onto a session array.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function push($key, $value)
    {
        $array = static::get($key, []);
        $array[] = $value;

        static::put($key, $array);
    }

    /**
     * Put a key / value pair in the session.
     *
     * @param $key
     * @param $value
     *
     * @return void
     */
    public static function put($key, $value = null)
    {
        if (!is_array($key)) {
            $key = [$key => $value];
        }

        foreach ($key as $arrayKey => $arrayValue) {
            static::set($arrayKey, $arrayValue);
        }
    }

    /**
     * Set an item in the session.
     *
     * @param $name
     * @param $value
     *
     * @return void
     */
    public static function set($name, $value)
    {
        Arr::set($_SESSION, $name, $value);
    }

    /**
     * Reflash all flash data for one more request.
     */
    public static function reflash()
    {
        $old = static::pull('flash.old', []);

        static::mergeWithCurrentFlashes($old);
    }

    /**
     * Remove all old flash data from the session.
     *
     * @return void
     */
    public static function removeOldFlashData()
    {
        foreach (static::get('flash.old', []) as $key) {
            static::forget($key);
        }
    }

    /**
     * Merge new flash keys into the new flash array.
     *
     * @param array $keys
     *
     * @return void
     */
    protected static function mergeWithCurrentFlashes(array $keys)
    {
        $current = static::get('flash.new', []);

        static::put('flash.new', array_unique(array_merge($current, $keys)));
    }

    /**
     * Remove the given keys from the old flash data.
     *
     * @param array $keys
     *
     * @return void
     */
    protected static function removeFromOldFlashData(array $keys)
    {
        $old = static::get('flash.old', []);

        static::put('flash.old', array_diff($old, $keys));
    }
}
