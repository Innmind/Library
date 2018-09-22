<?php
declare(strict_types = 1);

namespace Tests\Web\Authentication\ViaAuthorization;

use Web\{
    Authentication\ViaAuthorization\ApiKey,
    Exception\InvalidApiKey,
};
use Innmind\HttpAuthentication\{
    ViaAuthorization\Resolver,
    Identity,
};
use Innmind\Http\Header\AuthorizationValue;
use PHPUnit\Framework\TestCase;

class ApiKeyTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Resolver::class, new ApiKey('foo'));
    }

    public function testThrowWhenNotBearer()
    {
        $this->expectException(InvalidApiKey::class);

        $check = new ApiKey('foo');

        $check(new AuthorizationValue('Basic', 'foo'));
    }

    public function testThrowWhenInvalidParameter()
    {
        $this->expectException(InvalidApiKey::class);

        $check = new ApiKey('foo');

        $check(new AuthorizationValue('Bearer', 'bar'));
    }

    public function testValidate()
    {
        $check = new ApiKey('foo');

        $identity = $check(new AuthorizationValue('Bearer', 'foo'));

        $this->assertInstanceOf(Identity::class, $identity);
        $this->assertSame('authorized client', (string) $identity);
    }
}
