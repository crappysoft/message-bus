<?php
declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     messenger=true,
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     outputClass=false
 * )
 */
final class ResetPasswordRequest
{
    /**
     * @var string
     * @Assert\NotNull()
     */
    public $username;
}
