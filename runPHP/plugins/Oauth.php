<?php

namespace runPHP\plugins;
use domain\oauth\AuthCode;
use domain\User;
use runPHP\IRepository, runPHP\RunException;
use domain\oauth\Client;

/**
 * Class Oauth
 * @package runPHP\plugins
 */
class Oauth {

    private static $grantTypes = array('password', 'authorization_code');
    private static $responseTypes = array('code', 'token');
    private static $scopes = array();


    /**
     * Check an authorization code. If the code is wrong return null, otherwise
     * return the Authorization code object.
     *
     * @param string       $code      An authorization code.
     * @param IRepository  $codeRepo  An authorization code repository.
     * @return AuthCode               An authorization code object.
     * @throws RunException           An exception with the error info.
     */
    public static function validateAuthorizationCode ($code, $codeRepo) {
        // Validate parameters.
        if (!$code) {
            throw new RunException(400, 'invalid_request', array(
                'description' => 'The code is empty'
            ));
        }
        // Check the code.
        $authCode = $codeRepo->findOne(array(
            'code' => eq($code)
        ));
        if (!$authCode) {
            return null; //'Authorization code doesn\'t exist or is invalid for the client');
        }
        if (strtotime($authCode->expires) < time()) {
            return null; //'The authorization code has expired');
        }
        // Return the authorization code.
        return $authCode;
    }

    /**
     * Check the authorization request. Return null if the authorization request
     * is valid. Otherwise return the error.
     *
     * @param  Client  $client        A client.
     * @param  string  $redirectUri   A redirect URI.
     * @param  string  $responseType  A response type.
     * @param  string  $scope         A scope.
     * @return string                 An error message or an empty string.
     */
    public static function validateAuthorizeRequest ($client, $redirectUri, $responseType, $scope) {
        // Validate redirect URI.
        if (!$redirectUri && !$client->redirectUri) {
            return 'invalid_request';
        }
        if ($redirectUri && ($redirectUri != $client->redirectUri)) {
            return 'invalid_request';
        }
        // Check client grant type.
        if (!Oauth::validateGrantType($client->grantTypes, 'authorization_code')) {
            return 'unauthorized_client';
        }
        // Validate response type.
        if (!$responseType) {
            return 'unsupported_response_type';
        }
        if (!Oauth::checkResponseType($responseType)) {
            return 'unsupported_response_type';
        }
        // Check requested scope.
        if ($scope) {
            if (!Oauth::checkScope($scope)) {
                return 'invalid_scope';
            }
            if (!Oauth::checkRestrictedScope($client->scope, $scope)) {
                return 'invalid_scope';
            }
        }
        // Return no error.
        return '';
    }

    /**
     * Check a client ID. If the client ID is wrong return null, otherwise
     * return the client info.
     *
     * @param  string       $clientId    Client ID.
     * @param  IRepository  $clientRepo  Client repository.
     * @return Client                    A client or null.
     */
    public static function validateClient ($clientId, $clientRepo) {
        // Check client ID.
        if (!$clientId) {
            return null;
        }
        // Validate the client ID.
        $client = $clientRepo->findOne(array(
            'id' => eq($clientId)
        ));
        if (!$client) {
            return null;
        }
        return $client;
    }

    /**
     * Check if the requested grant types meet the client grant types.
     * If no grant type defined, none are restricted.
     *
     * @param  string  $clientGrants    A list of client grant types, space separated.
     * @param  string  $requestedGrant  A list of requested grant types, space separated.
     * @return bool                     True if the requested grant types are allowed.
     */
    public static function validateGrantType ($clientGrants, $requestedGrant) {
        // Check if a grant type is supported.
        if (in_array($requestedGrant, self::$grantTypes)) {
            // Check if a grant type is allowed to a client.
            if ($clientGrants) {
                $grants = explode(' ', $clientGrants);
                return in_array($requestedGrant, $grants);
            }
            // If no grant type defined, none are restricted.
            return true;
        }
        return false;
    }

    /**
     * @param $clientScope
     * @param $requestedScope
     * @return bool
     */
    public static function validateScope ($clientScope, $requestedScope) {
        // Check if the requested scope is available for this client.
        if ($clientScope) {
            $clientScopes = explode(' ', $clientScope);
            $requestScopes = explode(' ', $requestedScope);
            return (count(array_diff($clientScopes, $requestScopes)) == 0);
        }
        // Return true if no scope is available.
        return true;
    }

    /**
     * Check the user credentials. If the user credentials are wrong return null,
     * otherwise return the user info.
     *
     * @param $username
     * @param $password
     * @param $userRepo
     * @return User
     */
    public static function validateUserCredentials ($username, $password, $userRepo) {
        // Validate parameters.
        if (!$username || !$password) {
            return null;
        }
        // Check the user exist.
        $user = $userRepo->findOne(array(
            'name' => eq($username)
        ));
        // Check user credentials.
        if (!$user || !$user->checkCredentials($password)) {
            return null;
        }
        // Return the user info.
        return $user;
    }







    private static function checkResponseType ($responseType) {
        return in_array($responseType, self::$responseTypes);
    }

    private static function checkScope ($scope) {
        return empty(self::$scopes) ? true : in_array($scope, self::$scopes);
    }











}