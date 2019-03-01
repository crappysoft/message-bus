<?php

namespace spec\App\ValueObject;

use App\ValueObject\Password;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PasswordSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('legalpassword');
        $this->shouldHaveType(Password::class);
        $this->__toString()->shouldReturn('legalpassword');
    }

    function it_should_throw_exception_if_password_is_too_short()
    {
        $this->beConstructedWith('short');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
