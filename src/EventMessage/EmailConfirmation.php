<?php

namespace App\EventMessage;

interface EmailConfirmation
{
    /**
     * @return string
     */
    public function getUserId(): string;
}
