<?php

namespace App\Notification;

use Data\Mission;
use Data\User;

/**
 * Summary of NotificationInterface.
 */
interface NotificationInterface
{
    ///**
    // * Specificy if the inherited notification handler has the ability to send
    // * attachment
    // * @var bool
    // */
    //public static $hasAttachmentAbility;

    ///**
    // * The body of the notification
    // * @var string
    // */
    //protected $body;

    /**
     * Construct the notification by handeling a given mission.
     * Should build the main body.
     *
     * @param \Data\Mission $mission
     * @param \Data\User    $user
     */
    public function __construct(Mission $mission, User $user);

    /**
     * Handle the attachment to send it within the notification.
     * Should be empty if `$hasAttachmentAbility` is false.
     *
     * @param string $raw
     *
     * @see $hasAttachmentAbility
     */
    public function attach(string $raw);

    /**
     * The notification sender to the user.
     */
    public function send();
}
