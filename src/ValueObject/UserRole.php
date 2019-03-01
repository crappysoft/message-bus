<?php
declare(strict_types = 1);

namespace App\ValueObject;

/**
 * @author Dmitry Bykov <dmitry.bykov@sibers.com>
 */
final class UserRole
{
    private const ROLE_USER  = 'ROLE_USER';
    private const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    private $role;

    /**
     * @return UserRole
     */
    public static function admin(): UserRole
    {
        return new static(self::ROLE_ADMIN);
    }

    /**
     * @return UserRole
     */
    public static function user(): UserRole
    {
        return new static(self::ROLE_USER);
    }

    /**
     * @param string $role
     */
    private function __construct(string $role)
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function role(): string
    {
        return $this->role;
    }
}
