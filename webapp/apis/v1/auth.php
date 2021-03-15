<?php

namespace apis\v1;
use runPHP\ApiController, runPHP\Session, runPHP\Response, runPHP\RunException;
use domain\User;

/**
 * Authentication REST API.
 */
class auth extends ApiController {

    /**
     * Unauthorize the current user.
     *
     * @param  array  $params  The request parameters.
     * @return Response        An Ok response if the user is authorize.
     */
    public function delete ($session, $params) {
        // Unauthorize the current session.
        $session->unauthorized();
        // Return an Ok response.
        return new Response(array(
            'result' => 'ok'
        ));
    }

    /**
     * Authorize a user.
     *
     * @param  array  $params  The request parameters.
     * @return Response        An Ok response if the user is authorize.
     * @throws RunException    If the user could not be authorize.
     */
    public function post ($session, $params) {
        // Get input data.
echo $_SESSION;
        $email = $this->inputGet('email');
        $password = $this->inputGet('pwd');
        // Find an active user by email.
        $userRepo = $this->repository('domain\User');
        $user = $userRepo->findOne(array(
            'email' => eq($email),
            'status' => eq('active')
        ));
        // Check the user and password are correct.
        if (!$user || ($user->password != $password)) {
            throw new RunException(400, __('The email or password are wrong.'));
        }
        // Update the user last connection info.
        $user->connectedOn = date('Y-m-d H:i:s');
        $userRepo->select('connectedOn')->modify($user);
        // Authorize the user.
        $session->authorize();
        $session->set('id', $user->id);
        $session->set('name', $user->name);
        // Return an Ok response.
        return new Response(array(
            'result' => 'ok'
        ));
    }
}