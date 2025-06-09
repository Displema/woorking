<?php

namespace TechnicalServiceLayer\Roles;

use Delight\Auth\Role;

final class Roles
{

    public const LANDLORD = Role::PUBLISHER;
    public const BASIC_USER = Role::CONSUMER;
    public const ADMIN = Role::ADMIN;

    private function __construct()
    {
    }
}
