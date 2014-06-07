<?php

namespace spec\Yos\Robokassa;

use Mockery;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Yos\Robokassa\Merchant;

class PaymentSpec extends ObjectBehavior
{
    public $paymentStage = 'init';

    function let(Merchant $merchant)
    {
        $merchant->getLogin()->willReturn('login');
        $merchant->getFirstPassword()->willReturn('password1');
        $merchant->getSecondPassword()->willReturn('password2');

        $this->beConstructedWith($merchant);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Yos\Robokassa\Payment');
    }

    function it_sets_payment_stage_to_a_given_stage()
    {
        $this->setPaymentStage('result');
        $this->getPaymentStage()->shouldReturn('result');

        $this->setPaymentStage('success');
        $this->getPaymentStage()->shouldReturn('success');

    }

    function it_throws_an_exception_if_given_non_existent_payment_stage()
    {
        $this->shouldThrow('Yos\Robokassa\Exceptions\NonExistentPaymentStageException')
            ->duringSetPaymentStage('jiberish');
    }

    function it_builds_signature_for_initialization_payment_stage()
    {
        $this->setPaymentStage('init');
        $this->buildSignature(1.1, 23, false)->shouldReturn('57f76df67f337196e9b11869c85a2a16');
    }

    function it_builds_signature_for_result_payment_stage()
    {
        $this->setPaymentStage('result');
        $this->buildSignature(1.1, 23, false)->shouldReturn('0d63c059a8ac7610c56150760bbc7490');
    }

    function it_builds_signature_for_success_payment_stage()
    {
        $this->setPaymentStage('success');
        $this->buildSignature(1.1, 23, false)->shouldReturn('b277f9808e571fcc5e9afb1cf19625e4');
    }

    function it_gets_payment_stage_status()
    {
        $this->setPaymentStage('success');
        $this->getPaymentStage()->shouldReturn('success');

        $this->setPaymentStage('result');
        $this->getPaymentStage()->shouldReturn('result');
    }
}
