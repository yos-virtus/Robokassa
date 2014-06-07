<?php

namespace spec\Yos\Robokassa;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MerchantSpec extends ObjectBehavior
{
    function let()
    {
        $credentials = [
            'login' => 'login',
            'password1' => 'password1',
            'password2' => 'password2',
        ];

        $this->beConstructedWith($credentials);

        $this->beConstructedWith($credentials, true);
        $this->beConstructedWith($credentials, false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Yos\Robokassa\Merchant');
    }
}
