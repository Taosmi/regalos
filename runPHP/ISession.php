<?php

namespace runPHP;

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
interface ISession {

    /**
     * Authorize the current user. Any previous session data will be erased.
     * The session ID will be regenerated.
     */
    public function authorize ();

   /**
     * Get the data for a key on the current session. If there is no session
     * data or the key does not exist, return null.
     * 
     * @param  string  $key  A key name.
     * @return array         The session data requested or null.
     */
    public function get ($key);

    /**
     * Get all the session data. If no session is available return null.
     *
     * @return array  All the session data or null.
     */
    public function getAll ();

    /**
     * Check if the current user has an authorized session.
     *
     * @return boolean  True if the user is authorized, otherwise false.
     */
    public function isAuthorized ();

    /**
     * Set a key value pair on the session data.
     * 
     * @param string  $key    A key name to set on the session.
     * @param object  $value  The corresponding value.
     */
    public function set ($key, $value);

    /**
     * Destroy the current authorized session and the cookie session. This
     * method must be executed before any header is sent to the browser.
     */
    public function unauthorized ();

}