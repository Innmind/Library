<?php
declare(strict_types = 1);

namespace Tests\Domain\Handler;

use Domain\{
    Handler\RegisterHtmlPageHandler,
    Command\RegisterHtmlPage,
    Repository\HtmlPageRepository,
    Repository\HostResourceRepository,
    Entity\HtmlPage,
    Entity\HostResource,
    Entity\Host,
    Entity\HtmlPage\Identity,
    Entity\HostResource\Identity as RelationIdentity,
    Entity\Host\Identity as HostIdentity,
    Entity\Host\Name,
    Specification\AndSpecification,
    Specification\HttpResource\Path,
    Specification\HttpResource\Query,
    Specification\HostResource\InResources,
    Specification\HostResource\Host as HostSpec,
    Event\HtmlPageRegistered
};
use Innmind\TimeContinuum\{
    TimeContinuumInterface,
    PointInTimeInterface
};
use Innmind\Url\{
    PathInterface,
    QueryInterface
};
use Innmind\Immutable\Set;
use PHPUnit\Framework\TestCase;

class RegisterHtmlPageHandlerTest extends TestCase
{
    public function testExecution()
    {
        $handler = new RegisterHtmlPageHandler(
            $htmlPageRepository = $this->createMock(HtmlPageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $htmlPageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(new Set(HtmlPage::class));
        $relationRepository
            ->expects($this->never())
            ->method('matching');
        $htmlPageRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HtmlPage $htmlPage) use ($command): bool {
                return $htmlPage->identity() === $command->identity() &&
                    $htmlPage->path() === $command->path() &&
                    $htmlPage->query() === $command->query() &&
                    $htmlPage->recordedEvents()->size() === 1 &&
                    $htmlPage->recordedEvents()->current() instanceof HtmlPageRegistered;
            }));
        $relationRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostResource $relation) use ($command, $now): bool {
                return $relation->identity() === $command->relation() &&
                    $relation->host() === $command->host() &&
                    $relation->resource() === $command->identity() &&
                    $relation->foundAt() === $now;
            }));

        $this->assertNull($handler($command));
    }

    public function testLookForRelations()
    {
        $handler = new RegisterHtmlPageHandler(
            $htmlPageRepository = $this->createMock(HtmlPageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');
        $command
            ->host()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('host uuid');

        $clock
            ->expects($this->once())
            ->method('now')
            ->willReturn(
                $now = $this->createMock(PointInTimeInterface::class)
            );
        $htmlPageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(
                (new Set(HtmlPage::class))->add(
                    new HtmlPage(
                        $identity = $this->createMock(Identity::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('HtmlPage uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left()->value() === ['HtmlPage uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(new Set(Host::class));
        $htmlPageRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HtmlPage $htmlPage) use ($command): bool {
                return $htmlPage->identity() === $command->identity() &&
                    $htmlPage->path() === $command->path() &&
                    $htmlPage->query() === $command->query() &&
                    $htmlPage->recordedEvents()->size() === 1;
            }));
        $relationRepository
            ->expects($this->once())
            ->method('add')
            ->with($this->callback(function(HostResource $relation) use ($command, $now): bool {
                return $relation->identity() === $command->relation() &&
                    $relation->host() === $command->host() &&
                    $relation->resource() === $command->identity() &&
                    $relation->foundAt() === $now;
            }));

        $this->assertNull($handler($command));
    }

    /**
     * @expectedException Domain\Exception\HtmlPageAlreadyExistException
     */
    public function testThrowWhenResourceAlreadyExist()
    {
        $handler = new RegisterHtmlPageHandler(
            $htmlPageRepository = $this->createMock(HtmlPageRepository::class),
            $relationRepository = $this->createMock(HostResourceRepository::class),
            $clock = $this->createMock(TimeContinuumInterface::class)
        );
        $command = new RegisterHtmlPage(
            $this->createMock(Identity::class),
            $this->createMock(HostIdentity::class),
            $this->createMock(RelationIdentity::class),
            $this->createMock(PathInterface::class),
            $this->createMock(QueryInterface::class)
        );
        $command
            ->path()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('/path');
        $command
            ->query()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('?query');
        $command
            ->host()
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('host uuid');

        $clock
            ->expects($this->never())
            ->method('now');
        $htmlPageRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof Path &&
                    $spec->right() instanceof Query &&
                    $spec->left()->value() === '/path' &&
                    $spec->right()->value() === '?query';
            }))
            ->willReturn(
                (new Set(HtmlPage::class))->add(
                    new HtmlPage(
                        $identity = $this->createMock(Identity::class),
                        $this->createMock(PathInterface::class),
                        $this->createMock(QueryInterface::class)
                    )
                )
            );
        $identity
            ->expects($this->once())
            ->method('__toString')
            ->willReturn('page uuid');
        $relationRepository
            ->expects($this->once())
            ->method('matching')
            ->with($this->callback(function(AndSpecification $spec): bool {
                return $spec->left() instanceof InResources &&
                    $spec->right() instanceof HostSpec &&
                    $spec->left()->value() === ['page uuid'] &&
                    $spec->right()->value() === 'host uuid';
            }))
            ->willReturn(
                (new Set(Host::class))->add(
                    new Host(
                        $this->createMock(HostIdentity::class),
                        new Name('some.domain.tld')
                    )
                )
            );
        $htmlPageRepository
            ->expects($this->never())
            ->method('add');
        $relationRepository
            ->expects($this->never())
            ->method('add');

        $handler($command);
    }
}
