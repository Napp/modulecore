<?php

namespace Napp\Core\Module\Tests\Unit;

use Napp\Core\Module\Extension\ExtensionRegistrar;
use Napp\Core\Module\Tests\Stubs\ProviderStub;
use Napp\Core\Module\Tests\Stubs\StubInterface;
use Napp\Core\Module\Tests\Stubs\StubProvider;
use Napp\Core\Module\Tests\TestCase;

class CoreProviderTest extends TestCase
{
    /** @test */
    public function it_registeres_correctly()
    {
        ExtensionRegistrar::getInstance();

        $this->assertCount(0, ExtensionRegistrar::getInstance()->getAllExtensions());

        $this->app->register(ProviderStub::class);

        $provider = $this->app->make(StubInterface::class);

        $this->assertInstanceOf(StubProvider::class, $provider);

        $this->assertCount(2, ExtensionRegistrar::getInstance()->getAllExtensions());
    }
}
