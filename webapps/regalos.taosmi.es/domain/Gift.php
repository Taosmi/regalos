<?php

namespace domain;

/**
 * Gift Business Logic.
 * Implements all the Gifts business logic procedures.
 */
class Gift {

    public $giftId, $title, $description, $image, $link, $userid, $username, $status, $createdOn, $privacy;

    /**
     * Create a new gift.
     */
    public function __construct () {
        // Set now as the creation time.
        // $this->createdOn = date('Y-m-d H:i:s');
        // Set the gift as active.
        // $this->status = 'active';
    }

    /**
     * Mark the gift as deleted.
     */
    public function delete () {
        $this->status = 'delete';
    }
}