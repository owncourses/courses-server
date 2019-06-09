<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Security\RequestDecoder;
use Behat\Gherkin\Node\PyStringNode;
use Behatch\Context\RestContext;
use Behatch\HttpCall\Request;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

final class RestJwtContext extends RestContext
{
    private $jwtEncoder;

    private $securitySecret;

    public function __construct(Request $request, JWTEncoderInterface $jwtEncoder, string $securitySecret)
    {
        parent::__construct($request);

        $this->jwtEncoder = $jwtEncoder;
        $this->securitySecret = $securitySecret;
    }

    /**
     * @Given I am authenticated as :username
     */
    public function iAmAuthenticatedAs(string $username)
    {
        $token = $this->jwtEncoder->encode(['username' => $username]);

        $this->request->setHttpHeader('Authorization', 'Bearer '.$token);
    }

    /**
     * @BeforeScenario
     */
    public function restoreAuthHeader()
    {
        $this->request->setHttpHeader('Authorization', null);
    }

    /**
     * Sends a HTTP request with a signed body.
     *
     * @Given I send a :method request to :url with signed body:
     */
    public function iSendARequestToWithSignedBody($method, $url, PyStringNode $body)
    {
        $token = hash_hmac('sha1', $body->getRaw(), $this->securitySecret);
        $this->request->setHttpHeader(RequestDecoder::REQUEST_TOKEN_KEY, 'sha1='.$token);

        return $this->iSendARequestTo($method, $url, $body);
    }
}
