<?php

namespace TechnicalServiceLayer\Roles;

final class Roles
{

    public const LANDLORD = \Delight\Auth\Role::PUBLISHER;
    public const BASIC_USER = \Delight\Auth\Role::CONSUMER;
    public const ADMIN = \Delight\Auth\Role::ADMIN;

    private function __construct()
    {
    }
}
