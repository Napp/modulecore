<?php

namespace Napp\Core\Module\Tests\Unit;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Napp\Core\Module\Extension\ExtensionRegistrar;
use Napp\Core\Module\Tests\TestCase;

class ExtensionRegistrarTest extends TestCase
{
    public function test_it_can_get_all_extensions_with_a_locale()
    {
        $loader = (new ArrayLoader())->addMessages('en', 'foo', ['label' => 'foo extension']);

        $this->app->instance('translator.loader', $loader);
        $this->app->instance('translator', new Translator($loader, 'en'));

        ExtensionRegistrar::addExtension('extension.foo', 'foo.label', true);

        $extensions = ExtensionRegistrar::getAllExtensions();

        $this->assertCount(3, $extensions);

        $this->assertContains([
            'key'   => 'extension.foo',
            'label' => 'foo extension',
        ], $extensions);
    }

    public function test_it_can_get_all_features_with_a_locale()
    {
        $loader = (new ArrayLoader())->addMessages('en', 'foo', ['label' => 'foo feature']);

        $this->app->instance('translator.loader', $loader);
        $this->app->instance('translator', new Translator($loader, 'en'));

        ExtensionRegistrar::addFeature('feature.foo', 'foo.label', true);

        $extensions = ExtensionRegistrar::getAllFeatures();

        $this->assertCount(1, $extensions);

        $this->assertEquals([
            [
                'key'   => 'feature.foo',
                'label' => 'foo feature',
            ],
        ], $extensions);
    }

    public function test_when_adding_features_or_extensions_with_settings_it_has_them_correctly()
    {
        ExtensionRegistrar::addFeature('foo.feature', 'some', true);
        ExtensionRegistrar::addExtension('foo.extension', 'some', true);

        $this->assertTrue(ExtensionRegistrar::hasSettings('foo.feature'));
        $this->assertTrue(ExtensionRegistrar::hasSettings('foo.extension'));

        ExtensionRegistrar::reset();
    }
}
