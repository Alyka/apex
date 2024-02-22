<?php

namespace Core\Commands;

use Illuminate\Console\Command;
use Core\Contracts\Configurable;
use Core\Helpers\Helper;

class SetConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all default configurations in the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // We will get all classes that use the Configurable trait.
        $classes = Helper::getSubClasses(Configurable::class);

        foreach ($classes as $class) {
            /** @var Configurable */
            $instance = app($class);

            $results = $instance->setDefaultConfig();

            foreach ($results as $result) {
                $this->{$result['level']}($result['message']);
            }
        }

        return 0;
    }
}
