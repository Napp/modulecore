<?php

namespace Napp\Core\Module\Tests\Stubs;

use Napp\Core\Module\Provider\CoreServiceProvider;

class ProviderStub extends CoreServiceProvider
{
    protected $extensions = [
        'extension.with-settings' => [
            'extension.with-settings-label',
            true,
        ],
        'extension.without-settings' => 'extension.with-settings-label',
    ];

    protected $repositories = [
        StubInterface::class => StubProvider::class,
    ];

    protected $permissions = [
        'stub-permission',
    ];
}
