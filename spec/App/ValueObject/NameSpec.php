<?php

namespace spec\App\ValueObject;

use App\ValueObject\Name;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NameSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('John', 'Doe');
        $this->shouldHaveType(Name::class);
        $this->firstName()->shouldReturn('John');
        $this->lastName()->shouldReturn('Doe');
    }

    function it_should_throw_exception_if_firstName_is_to_short()
    {
        $this->beConstructedWith('J', 'Doe');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_should_throw_exception_if_firstName_is_to_long()
    {
        $this->beConstructedWith(str_repeat('J', 65), 'Doe');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_should_throw_exception_if_lastName_is_to_short()
    {
        $this->beConstructedWith('John', 'D');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_should_throw_exception_if_lastName_is_to_long()
    {
        $this->beConstructedWith('John', str_repeat('D', 65));
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}
