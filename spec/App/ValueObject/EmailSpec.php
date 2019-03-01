<?php

namespace spec\App\ValueObject;

use App\ValueObject\Email;
use Assert\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmailSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('admin@example.net');
        $this->shouldHaveType(Email::class);
        $this->email()->shouldReturn('admin@example.net');
    }

    function it_should_throw_exception_for_invalid_emails()
    {
        $this->beConstructedWith('invalid_email');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }
}

