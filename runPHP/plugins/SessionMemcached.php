<?php

namespace runPHP\plugins;

use runPHP\ISession, runPHP\Logger;
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
     * @var string  Session ID on memcached.
     */
    private $key;

    /**
     * @var Memcache  Memcache object.
    */
    private $mem;

    /**
     * @var array  Session info.
     */
    private $session = array();


    public function __construct () {
        Logger::sys(__('Init memcached server "%s" ...', 'system'), getenv("MEMCACHIER_SERVERS"));
        // Using Memcached client.
        $this->mem = new Memcached("memcached_pool");
        $this->mem->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE);
        // Enable no-block for some performance gains but less certainty that data has been stored.
        $this->mem->setOption(Memcached::OPT_NO_BLOCK, TRUE);
        // Failover automatically when host fails.
        $this->mem->setOption(Memcached::OPT_AUTO_EJECT_HOSTS, TRUE);
        // Adjust timeouts.
        $this->mem->setOption(Memcached::OPT_CONNECT_TIMEOUT, 2000);
        $this->mem->setOption(Memcached::OPT_POLL_TIMEOUT, 2000);
        $this->mem->setOption(Memcached::OPT_RETRY_TIMEOUT, 2);
        // SASL config.
        $this->mem->setSaslAuthData(getenv("MEMCACHIER_USERNAME"), getenv("MEMCACHIER_PASSWORD"));
        // Add in the servers first time.
        if (!$this->mem->getServerList()) {
            // Parse config.
            $servers = explode(",", getenv("MEMCACHIER_SERVERS"));
            for ($i = 0; $i < count($servers); $i++) {
                $servers[$i] = explode(":", $servers[$i]);
            }
            $this->mem->addServers($servers);
        }
        Logger::sys(__('Init memcached server Ok.', 'system'));
        // Inits memcached session.
        session_name('taosmi');
        session_set_cookie_params(null, null, null, true, true);
        session_start();
        $this->key = session_id();
        Logger::sys(__('Session set with key "%s".', 'system'), $this->key);
        $this->session = $this->mem->get($this->key) ?: array();
        Logger::sys(__('Session data retrieved: "%s".', 'system'), serialize($this->session));
    }


    /**
     * Authorize the current user. Any previous session data will be erased.
     * The session ID will be regenerated.
     */
    public function authorize () {
        // Regenerate the session ID.
        $this->mem->delete($this->key);
        session_regenerate_id(true);
        $this->key = session_id();;
        // Reset memcached session.
        $this->session = array();
        $this->session['fingerprint'] = $this->getFingerPrint();
        $this->mem->add($this->key, $this->session);
    }

   /**
     * Get the data for a key on the current session. If there is no session
     * data or the key does not exist, return null.
     * 
     * @param  string  $key  A key name.
     * @return array         The session data requested or null.
     */
    public function get ($key) {
        return $this->isAuthorized() ? $this->session[$key] : null;
    }

    /**
     * Get all the session data. If no session is available return null.
     *
     * @return array  All the session data or null.
     */
    public function getAll () {
        return $this->isAuthorized() ? $this->session : null;
    }

    /**
     * Check if the current user has an authorized session.
     *
     * @return boolean  True if the user is authorized, otherwise false.
     */
    public function isAuthorized () {
        if (array_key_exists('fingerprint', $this->session)) {
            Logger::sys(__('Session fingerprint match is %s (stored:"%s" shoud be "%s").', 'system'), $this->session['fingerprint'] === $this->getFingerPrint(), $this->session['fingerprint'], $this->getFingerPrint());
            return ($this->session['fingerprint'] === $this->getFingerPrint());
        }
        return false;
    }

    /**
     * Set a key value pair on the session data.
     * 
     * @param string  $key    A key name to set on the session.
     * @param object  $value  The corresponding value.
     */
    public function set ($key, $value) {
        if ($this->isAuthorized()) {
            $this->session[$key] = $value;
            $this->mem->set($this->key, $this->session);
        }
    }

    /**
     * Destroy the current authorized session and the cookie session. This
     * method must be executed before any header is sent to the browser.
     */
    public function unauthorized () {
        // Erase the session cookie.
        if (isset($_COOKIE[session_name()])) {
            $ckData = session_get_cookie_params();
            setcookie(session_name(), '', -1, $ckData['path'], $ckData['domain'], $ckData['secure'], $ckData['httponly']);
        }
        // Erase the session and the session data.
        session_destroy();
        $this->mem->delete($this->key);
        $this->session = array();
    }


    /**
     * Retrieve the finger print for the current user.
     *
     * @return string  The current finger print.
     */
    private function getFingerPrint () {
        return sha1(APP.$_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
    }

}