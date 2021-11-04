<?php

declare(strict_types=1);

namespace SolumDeSignum\Deviation;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use SolumDeSignum\Deviation\Actions\DirectoriesAction;
use SolumDeSignum\Deviation\Providers\EventServiceProvider;

class DeviationServiceProvider extends ServiceProvider
{
    public string $nameSpace = 'solumdesignum/deviation';

    private DirectoriesAction $directoriesAction;

    /**
     * Create a new service provider instance.
     *
     * @param Application $app
     *
     * @return void
     */
    public function __construct($app)
    {
        $this->directoriesAction = new DirectoriesAction();
        parent::__construct($app);
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            $this->nameSpace
        );

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->directoriesAction->run();

        $this->mergeConfigFrom(__DIR__ . '/../config/deviation.php', 'deviation');

        // Register the service the package provides.
        $this->app->singleton('deviation', function ($app) {
            return new Deviation;
        });

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['deviation'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__ . '/../config/deviation.php' => config_path('deviation.php'),
        ], "$this->nameSpace.config");
    }
}
