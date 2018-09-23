<?php
declare(strict_types = 1);

namespace Web\Authentication\ViaAuthorization;

use Web\Exception\InvalidApiKey;
use Innmind\HttpAuthentication\{
    ViaAuthorization\Resolver,
    Identity,
};
use Innmind\Http\Header\AuthorizationValue;

final class ApiKey implements Resolver
{
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function __invoke(AuthorizationValue $value): Identity
    {
        if ($value->scheme() !== 'Bearer') {
            throw new InvalidApiKey;
        }

        if ($value->parameter() !== $this->apiKey) {
            throw new InvalidApiKey;
        }

        // this will not be used elsewhere in the app so no need to create a real class
        return new class implements Identity {
            public function __toString(): string
            {
                return 'authorized client';
            }
        };
    }
}
