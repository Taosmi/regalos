<?php

namespace runPHP;
use runPHP\plugins\RepositoryPDO;

/**
 * This class provides the functionality to manage a REST HTTP request and its
 * four verbs: put, post, get and delete.
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
abstract class ApiController {

    /**
     * @var string  A list of allowed HTML tags on input data.
     */
    private static $TAGS_ALLOWED = '<a><b><br><img><p><ul><li>';

    /**
     * @var array  A request input data.
     */
    private $input;

    /**
     * @var array  A request info.
     */
    private $request;

    /**
     * @var array  The repositories configuration
     */
    private $repos;


    /**
     * The controller get a reference to the request information.
     *
     * @param  array  $repos    The repositories configuration.
     * @param  array  $request  The request information.
     */
    public function __construct ($repos, $request) {
        $this->repos = $repos;
        $this->request = $request;
        $this->input = $_REQUEST;
        // Get the body request input data.
        if ($request['mime'] === 'application/json' && $request['method'] != 'GET') {
            $bodyParams = json_decode(file_get_contents('php://input'), true);
            if ($bodyParams) {
                $this->input = array_merge($this->input, $bodyParams);
            }
        }
    }


    /**
     * This function is derived on four other functions, each one match one
     * of the four HTTP verbs available and must be implemented by the developer.
     *
     * @param  array  $params  The parameters when the URL is a backward URL.
     * @return Response        A Response with the output data.
     * @throws RunException    If the HTTP verb is not available.
     */
    public function main ($params = array()) {
        switch ($this->request['method']) {
            case 'GET':
                Logger::sys('Running an API GET method.');
                return $this->get($params);
            case 'PUT':
                 Logger::sys('Running an API PUT method.');
                return $this->put($params);
            case 'POST':
                 Logger::sys('Running an API POST method.');
                return $this->post($params);
            case 'DELETE':
                 Logger::sys('Running an API DELETE method.');
                return $this->delete($params);
            default:
                throw new RunException(500, __('The HTTP verb used is not available.'), array(
                    'code' => 'RPP-020',
                    'helpLink' => 'http://runphp.taosmi.es/faq/rpp020'
                ));
        }
    }

    /**
     * Requests with GET HTTP verb will run this method. Developer should
     * override this method with some logic. By default, this method sends a
     * HTTP error code 404 Page not found.
     *
     * @param  array  $params  The parameters.
     * @return Response        A Response with the output data.
     */
    public function get ($params) {
        return new Response(null, 404);
    }

    /**
     * Requests with POST HTTP verb will run this method. Developer should
     * override this method with some logic. By default, this method sends a
     * HTTP error code 404 Page not found.
     *
     * @param  array  $params  The parameters.
     * @return Response        A Response with the output data.
     */
    public function post ($params) {
        return new Response(null, 404);
    }

    /**
     * Requests with PUT HTTP verb will run this method. Developer should
     * override this method with some logic. By default, this method sends a
     * HTTP error code 404 Page not found.
     *
     * @param  array  $params  The parameters.
     * @return Response        A Response with the output data.
     */
    public function put ($params) {
        return new Response(null, 404);
    }

    /**
     * Requests with DELETE HTTP verb will run this method. Developer should
     * override this method with some logic. By default, this method sends a
     * HTTP error code 404 Page not found.
     *
     * @param  array  $params  The parameters.
     * @return Response        A Response with the output data.
     */
    public function delete ($params) {
        return new Response(null, 404);
    }


    /**
     * Get the value corresponding to the key from the input data. If the key
     * does not exists, return null.
     *
     * @param  string  $key  The input data key name.
     * @return string        The corresponding value or null.
     */
    public function inputGet ($key) {
        // Parse the input data to avoid XSS attacks.
        if (array_key_exists($key, $this->input)) {
            return htmlentities(stripslashes(strip_tags($this->input[$key], self::$TAGS_ALLOWED)), ENT_QUOTES);
        }
        // If no input data, return null.
        return null;
    }

    /**
     * Get the repository for the class specified.
     *
     * @param  string  $className  Class name of the repository objects.
     * @return RepositoryPDO       The object repository for the class name given.
     * @throws RunException        If fails.
     */
    public function repository ($className) {
        // Get the connection string.
        if (!isset($this->repos['connection'])) {
            throw new RunException(500, __('No connection string defined', 'system'), array(
                'code' => 'RPP-012',
                'helpLink' => 'http://runphp.taosmi.es/faq/rpp012'
            ));
        }
        // Get the repository parameters from the app.cfg configuration.
        $connectString = $this->repos['connection'];
        $pks = array_key_exists($className, $this->repos)
            ? $this->repos[$className]
            : null;
        // Get a repository.
        if (class_exists($className.'Repository')) {
            // Get a specific repository.
            $repoClassName = $className.'Repository';
            $repo = new $repoClassName($connectString, $className, $pks);
        } else {
            // Get a generic repository.
            $repo = new RepositoryPDO($connectString, $className, $pks);
        }
        // Set the parameters from the request.
        $fields = $this->inputGet('fields');
        if ($fields) {
            $repo->select($fields);
        }
        $limit = $this->inputGet('limit');
        $offset = $this->inputGet('offset');
        if ($limit){
            $repo->paginate($limit, $offset);
        }
        return $repo;
    }
}