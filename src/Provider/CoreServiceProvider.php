<?php

namespace Napp\Core\Module\Provider;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Napp\Core\Acl\PermissionRegistrar;
use Napp\Core\Module\Extension\ExtensionRegistrar;

/**
 * Class CoreServiceProvider
 * @package Napp\Core\Module\Provider
 */
class CoreServiceProvider extends ServiceProvider
{
    /**
     * The root namespace to assume when generating URLs to actions.
     *
     * @var string
     */
    protected $routeNamespace = '';

    /**
     * Bind Repositories to interfaces
     *
     * @var array
     */
    protected $repositories = [];

    /**
     * @var array
     */
    protected $events = [];

    /**
     * @var array
     */
    protected $eventSubscribers = [];

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var array
     */
    protected $observers = [];

    /**
     * @var array
     */
    protected $extensions = [];

    /**
     * @var array
     */
    protected $commands = [];

    /**
     * @var array
     */
    protected $features = [];

    /**
     * @return void
     */
    public function register()
    {
        // Bind Repositories
        $this->registerRepositories($this->repositories);

        // Routes
        $this->loadRoutes();

        // Console Commands
        $this->commands($this->commands);
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Event Listeners
        $this->eventListeners($this->events);

        // Event Subscriptions
        $this->eventSubscriptions($this->eventSubscribers);

        // Observers
        $this->addObservers($this->observers);

        // Extensions
        $this->registerExtensions($this->extensions);

        // Features
        $this->registerFeatures($this->features);

        // Permissions
        $this->registerPermissions($this->permissions);
    }

    /**
     * @param array $repositories
     */
    protected function registerRepositories(array $repositories): void
    {
        foreach ($repositories as $key => $repository) {
            $this->app->bind($key, $repository);
        }
    }

    /**
     * @param array $events
     */
    protected function eventListeners(array $events): void
    {
        foreach ($events as $event => $listener) {
            if (\is_array($listener)) {
                foreach ($listener as $subListener) {
                    $this->app['events']->listen($event, $subListener);
                }

                continue;
            }
            $this->app['events']->listen($event, $listener);
        }
    }

    /**
     * @param array $subscribers
     */
    protected function eventSubscriptions(array $subscribers): void
    {
        foreach ($subscribers as $subscriber) {
            $this->app['events']->subscribe($subscriber);
        }
    }

    /**
     * @param array $observers
     */
    protected function addObservers(array $observers): void
    {
        foreach ($observers as $key => $observer) {
            if (\is_array($observer)) {
                foreach ($observer as $subObserver) {
                    app($key)::observe($subObserver);
                }

                return;
            }
            app($key)::observe($observer);
        }
    }

    /**
     * @param array $extensions
     */
    protected function registerExtensions(array $extensions): void
    {
        foreach ($extensions as $key => $translation) {
            if (\is_array($translation)) {
                ExtensionRegistrar::addExtension($key, $translation[0], $translation[1]);
                continue;
            }
            ExtensionRegistrar::addExtension($key, $translation);
        }
    }

    /**
     * @param array $features
     */
    protected function registerFeatures(array $features): void
    {
        foreach ($features as $key => $translation) {
            if (\is_array($translation)) {
                ExtensionRegistrar::addFeature($key, $translation[0], $translation[1]);
                continue;
            }

            ExtensionRegistrar::addFeature($key, $translation);
        }
    }

    /**
     * @param array $permissions
     */
    protected function registerPermissions(array $permissions): void
    {
        PermissionRegistrar::register($permissions);
    }

    /**
     * @return string
     */
    protected function getCMSRoutes() {

    }

    /**
     * @return string
     */
    protected function getApiRoutes() {

    }

    /**
     * @return string
     */
    protected function getFrontRoutes() {

    }

    /**
     * @return string
     */
    protected function getFrontApiRoutes() {

    }

    /**
     * Load route files if not cached
     */
    protected function loadRoutes()
    {
        if (!$this->app->routesAreCached()) {
            $this->routes($this->app->make(Router::class));
        }
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function routes(Router $router)
    {
        $baseUrl = config('module.http.base_url');

        $router->group([
            'namespace' => $this->routeNamespace,
            'prefix' => config('module.http.prefix.cms'),
            'middleware' => config('module.http.middleware.cms')
        ], function (Router $router) {
            $routes = $this->getCMSRoutes();
            if ($routes && file_exists($routes)) {
                require $routes;
            }
        });

        $router->group([
            'domain' => "{client}.{$baseUrl}",
            'namespace' => $this->routeNamespace,
            'prefix' => config('module.http.prefix.api'),
            'middleware' => config('module.http.middleware.api')
        ], function (Router $router) {
            $routes = $this->getApiRoutes();
            if ($routes && file_exists($routes)) {
                require $routes;
            }
        });

        $router->group([
            'namespace' => $this->routeNamespace,
            'prefix' => config('module.http.prefix.front'),
            'middleware' => config('module.http.middleware.front')
        ], function (Router $router) {
            $routes = $this->getFrontRoutes();
            if ($routes && file_exists($routes)) {
                require $routes;
            }
        });

        $router->group([
            'domain' => "{client}.{$baseUrl}",
            'namespace' => $this->routeNamespace,
            'prefix' => config('module.http.prefix.frontApi'),
            'middleware' => config('module.http.middleware.frontApi')
        ], function (Router $router) {
            $routes = $this->getFrontApiRoutes();
            if ($routes && file_exists($routes)) {
                require $routes;
            }
        });
    }
}