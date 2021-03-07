<?php

namespace apis\v1;
use runPHP\ApiController, runPHP\Response, runPHP\RunException, runPHP\plugins\Email;
use domain\User;

/**
 * Service to recover a user password.
 */
class recover extends ApiController {

    /**
     * Send the password to an user email address.
     *
     * @param  array  $params  The request parameters.
     * @return Response        An Ok response.
     * @throws RunException    If the email could not be sent.
     */
    public function get ($params) {
        // Get input data.
        $email = $this->inputGet('email');
        // Find a user by email.
        $userRepo = $this->repository('domain\User');
        $user = $userRepo->findOne(array(
            'status' => eq('active'),
            'email' => eq($email)
        ));
        // Check the user exist.
        if (!$user) {
            throw new RunException(400, __('The email address doest no exists in TAOSMI Regalos.'));
        }
        // Send email.
        $result = Email::fromFile(VIEWS.'/emails/recoverPassword.txt', array(
            'from' => 'regalos@taosmi.es',
            'to' => $email,
            'userName' => $user->name,
            'password' => $user->password
        ));

        // Return the Ok response.
        return new Response(array(
            'result' => __('Done!')
        ));
    }

}