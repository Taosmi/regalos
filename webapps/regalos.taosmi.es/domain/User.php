<?php

namespace domain;

/**
 * User Business Logic.
 * Implements all the user business logic functionality.
 */
class User {

    public $id, $email, $password, $name, $birthday, $policy, $createdOn, $connectedOn, $canceledOn, $status, $mode;

    /**
     * Create a user.
     */
    public function __construct () {
        $this->createdOn = date('Y-m-d H:i:s');
        $this->status = 'active';
        $this->mode = 'user';
    }
}