<?php

namespace Testing\Fixtures;

/**
 * This class purpose is only to store the user ids generated by the library delight-im/PHP-Auth.
 * Starting from DoctrineFixturesBundle 3.x all references are serialized, hence only mapped classes or superclasses can
 * be stored inside the reference repositories.
 */
class FixtureState
{
    public static array $userIds = [];
}
