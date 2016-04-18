<?php

namespace Firestarter\Auth;

/**
 * Password hashing helper.
 */
class PasswordHasher
{
    /**
     * The algorithm to use with the password hashing API.
     * Used when creating new hashes.
     *
     * @var int
     */
    private static $algo = PASSWORD_DEFAULT;

    /**
     * The algorithm-specific options to use with the password hashing API.
     * Used when creating new hashes or updating hashes.
     *
     * @return array
     */
    private static $options = ['cost' => 10];

    /**
     * Hashes a given input string.
     *
     * @param string $input
     * @return string
     */
    public static function hash($input)
    {
        return password_hash($input, self::$algo, self::$options);
    }

    /**
     * Verifies user input against a hash.
     *
     * @param string $input
     * @param string $hash
     * @return bool
     */
    public static function verify($input, $hash)
    {
        return password_verify($input, $hash);
    }

    /**
     * Determines whether a password needs a rehash due to changed algorithms or difficulties.
     *
     * @param string $hash
     * @return bool
     */
    public static function needsRehash($hash)
    {
        return password_needs_rehash($hash, self::$algo, self::$options);
    }
}