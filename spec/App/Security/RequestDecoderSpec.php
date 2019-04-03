<?php

namespace spec\App\Security;

use App\Security\RequestDecoder;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;

class RequestDecoderSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('secretKey');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(RequestDecoder::class);
    }

    public function it_should_fail_on_unsigned_request()
    {
        $request = new Request();
        $this->decode($request)->shouldReturn(false);
    }

    public function it_should_verify_signed_request()
    {
        $content = '{"a": "b"}';
        $request = new Request([], [], [], [], [], [], $content);
        $request->headers->add([RequestDecoder::REQUEST_TOKEN_KEY => 'sha1='.\hash_hmac('sha1', $content, 'secretKey')]);
        $this->decode($request)->shouldReturn(true);
    }
}
