<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterDomainHandler,
    Command\RegisterDomain,
    Entity\Domain,
    Entity\Domain\IdentityInterface,
    Repository\DomainRepositoryInterface,
    Specification\AndSpecification,
    Specification\Domain\Name,
    Specification\Domain\TopLevelDomain
};
use Domain\Specification;
use Innmind\Url\Authority\Host;
use Innmind\Immutable\{
    Set,
    SetInterface
};
use Pdp\{
    Parser,
    PublicSuffixListManager
};

class RegisterDomainHandlerTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDomain()
    {
        $handler = new RegisterDomainHandler(
            $repository = $this->createMock(DomainRepositoryInterface::class),
            new Parser(
                (new PublicSuffixListManager)->getList()
            )
        );
        $command = new RegisterDomain(
            $this->createMock(IdentityInterface::class),
            new Host('www.example.co.uk')
        );

        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Name &&
                    $spec->right() instanceof TopLevelDomain &&
                    $spec->left()->value() === 'example' &&
                    $spec->right()->value() === 'co.uk';
            }))
            ->willReturn(new Set(Domain::class));
        $repository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(Domain $domain) use ($command): bool {
                return (string) $domain === 'example.co.uk' &&
                    $domain->identity() === $command->identity();
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\DomainAlreadyExistException
     */
    public function testThrowWhenDomainAlreadyExist()
    {
        $handler = new RegisterDomainHandler(
            $repository = $this->createMock(DomainRepositoryInterface::class),
            new Parser(
                (new PublicSuffixListManager)->getList()
            )
        );
        $command = new RegisterDomain(
            $this->createMock(IdentityInterface::class),
            new Host('www.example.co.uk')
        );

        $repository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Name &&
                    $spec->right() instanceof TopLevelDomain &&
                    $spec->left()->value() === 'example' &&
                    $spec->right()->value() === 'co.uk';
            }))
            ->willReturn(
                $set = $this->createMock(SetInterface::class)
            );
        $repository
            ->expects($this->never())
            ->method('add');
        $set
            ->expects($this->once())
            ->method('size')
            ->willReturn(1);

        $handler($command);
    }
}
