<?php

namespace Modules\Survey\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;

class SurveyServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Survey';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'survey';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    protected array $commands = [
        \Modules\Survey\Console\DispatchSurveysCommand::class,
    ];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
