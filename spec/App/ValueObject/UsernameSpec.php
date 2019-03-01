<?php

namespace spec\App\ValueObject;

use App\ValueObject\Username;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class UsernameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('legalusername');
        $this->shouldHaveType(Username::class);
        $this->username()->shouldReturn('legalusername');
    }

    function it_should_throw_exception_if_username_is_too_short()
    {
        $this->beConstructedWith('sho');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
