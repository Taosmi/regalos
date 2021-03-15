<?php

namespace runPHP\plugins;
use runPHP\ISession;
use MemCachier\MemcacheSASL;
use \Memcached;

/**
 * Manage user authentication and session data storage.
 *
 * @author Miguel Angel Garcia
 *
 * Copyright 2014 TAOSMI Technology
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
class SessionMemcached implements ISession  {

    /**
     * Authorize the current user. Any previous session data will be erased.
     * The session ID will be regenerated.
     */
    public static function authorize () {
        // Erase previous session data and regenerate the session ID.
        $_SESSION = array();
        session_regenerate_id(true);
        // Set the session finger print.
        $_SESSION['fingerprint'] = self::getFingerPrint();
    }

   /**
     * Get the data for a key on the current session. If there is no session
     * data or the key does not exist, return null.
     * 
     * @param  string  $key  A key name.
     * @return array         The session data requested or null.
     */
    public static function get ($key) {
        return self::isAuthorized() ? $_SESSION[$key] : null;
    }

    /**
     * Get all the session data. If no session is available return null.
     *
     * @return array  All the session data or null.
     */
    public static function getAll () {
        return self::isAuthorized() ? $_SESSION : null;
    }

    public static function init () {
        // Using Memcached client.
        $m = new Memcached("memcached_pool");
        $m->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE);
        // Enable no-block for some performance gains but less certainty that data has been stored.
        $m->setOption(Memcached::OPT_NO_BLOCK, TRUE);
        // Failover automatically when host fails.
        $m->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, TRUE);
        // Adjust timeouts.
        $m->setOption(Memcached::OPT_CONNECT_TIMEOUT, 2000);
        $m->setOption(Memcached::OPT_POLL_TIMEOUT, 2000);
        $m->setOption(Memcached::OPT_RETRY_TIMEOUT, 2);
        // SASL config.
        $m->setSaslAuthData(getenv("MEMCACHIER_USERNAME"), getenv("MEMCACHIER_PASSWORD"));
        // Add in the servers first time.
        if (!$m->getServerList()) {
            // Parse config.
            $servers = explode(",", getenv("MEMCACHIER_SERVERS"));
            for ($i = 0; $i < count($servers); $i++) {
                $servers[$i] = explode(":", $servers[$i]);
            }
            $m->addServers($servers);
        }
        // Enable MemCachier session support
        session_start();
    }

    /**
     * Check if the current user has an authorized session.
     *
     * @return boolean  True if the user is authorized, otherwise false.
     */
    public static function isAuthorized () {
        if (array_key_exists('fingerprint', $_SESSION)) {
            return ($_SESSION['fingerprint'] === self::getFingerPrint());
        }
        return false;
    }

    /**
     * Set a key value pair on the session data.
     * 
     * @param string  $key    A key name to set on the session.
     * @param object  $value  The corresponding value.
     */
    public static function set ($key, $value) {
        if (self::isAuthorized()) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Destroy the current authorized session and the cookie session. This
     * method must be executed before any header is sent to the browser.
     */
    public static function unauthorized () {
        // Erase the session cookie.
        if (isset($_COOKIE[session_name()])) {
            $ckData = session_get_cookie_params();
            setcookie(session_name(), '', -1, $ckData['path'], $ckData['domain'], $ckData['secure'], $ckData['httponly']);
        }
        // Erase the session and the session data.
        $_SESSION = array();
        session_destroy();
    }


    /**
     * Retrieve the finger print for the current user.
     *
     * @return string  The current finger print.
     */
    private static function getFingerPrint () {
        return sha1(APP.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
    }

}