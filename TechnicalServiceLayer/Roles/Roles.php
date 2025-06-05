<?php

namespace TechnicalServiceLayer\Roles;

final class Roles
{

    const LANDLORD = \Delight\Auth\Role::PUBLISHER;
    const BASIC_USER = \Delight\Auth\Role::CONSUMER;

    const ADMIN = \Delight\Auth\Role::ADMIN;

    private function __construct()
    {
    }

    public function isLandlord(): bool
    {

    }
}
