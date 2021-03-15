<?php

namespace apis\v1;
use runPHP\ApiController, runPHP\Response, runPHP\RunException, runPHP\Session;
use domain\User as oUser;

/**
 * User REST API.
 */
class user extends ApiController {

    /**
     * Get a user profile. A user ID must by specified in the URL.
     *
     * @param  array  $params  The request parameters.
     * @return Response        A response with the user info.
     * @throws RunException    If something goes wrong.
     */
    public function get ($session, $params) {
        // Get a user ID from the URL.
        $id = current($params);
        if (!$id) {
            throw new RunException(400, __('The user ID is mandatory'));
        }
        // Get the user from the repository.
        $userRepo = $this->repository('\domain\User');
        $user = $userRepo->findOne(array(
            'id' => eq($id),
            'status' => eq('active')
        ));
        // Return a response with the user data.
        return new Response(array(
            'user' => $user
        ));
    }

    /**
     * Create a new user.
     *
     * @param  array  $params  The request parameters.
     * @return Response        A welcome message.
     * @throws RunException    If something goes wrong.
     */
    public function post ($session, $params) {
        $userRepo = $this->repository('\domain\User');
        // Get input data.
        $name = $this->inputGet('name');
        $email = $this->inputGet('email');
        $password = $this->inputGet('password');
        $birthday = $this->inputGet('birthday');
        $policy = $this->inputGet('policy');
        // Check mandatory info.
        if (!$name) {
            throw new RunException(400, __('The name is mandatory'));
        }
        if (!$email) {
            throw new RunException(400, __('The email is mandatory'));
        }
        if (!$password) {
            throw new RunException(400, __('The password is mandatory'));
        }
        // Check the user with this email already exist.
        $user = $userRepo->findOne(array(
            'status' => eq('active'),
            'email' => eq($email)
        ));
        if ($user) {
            throw new RunException(400, __('This user is already a member of TAOSMI Regalos'));
        }
        // Create the user.
        $user = new oUser();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->birthday = $birthday;
        $user->policy = $policy;
        // Store the user.
        $user = $userRepo->add($user);
        // Store the avatar.
        copy(STATICS.'/imgs/user.png', STATICS.'/imgs/users/'.$user->id);

        // Return a Ok response.
        return new Response(array(
            'result' => __('Welcome!')
        ));
    }

    /**
     * Modify a user.
     *
     * @param  array  $params  The request parameters.
     * @return Response        A welcome message.
     * @throws RunException    If something goes wrong.
     */
    public function put ($session, $params) {
        // Get the user data.
        $id = current($params);
        $name = $this->inputGet('name');
        $email = $this->inputGet('email');
        $birthday = $this->inputGet('birthday');
        $policy = $this->inputGet('policy');
        $image = $this->inputGet('image');
        // Check the ID is equal to the session ID.
        if ($id != Session::get('id')) {
            throw new RunException(400, __('The user is not available'));
        }
        // Check the user already exist.
        $userRepo = $this->repository('\domain\User');
        $user = $userRepo->findOne(array(
            'id' => eq($id),
            'status' => eq('active')
        ));
        if (!$user) {
            throw new RunException(400, __('The user is not available'));
        }
        // Modify the user info.
        $user->name = $name;
        $user->email = $email;
        $user->birthday = $birthday;
        $user->policy = $policy;
        // Store the user.
        $userRepo->select('name,email,birthday,policy')->modify($user);
        // Store the avatar.
        if ($image) {
            list($mime, $data) = explode(';', $image);
            list($type,) = explode('/', $mime);
            list(, $data) = explode(',', $data);
            if ($type === 'data:image') {
                file_put_contents(STATICS . '/imgs/users/' . $id, base64_decode($data));
            }
        }
        // Return an Ok response.
        return new Response(array(
            'result' => 'ok'
        ));
    }
}