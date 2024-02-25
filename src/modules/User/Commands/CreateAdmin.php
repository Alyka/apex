<?php

namespace Modules\User\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Modules\Role\Models\Role;
use Modules\User\Facades\UserService;
use Modules\User\Http\Requests\StoreRequest;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

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
        Request::merge([
            'name' => $this->ask('Enter your name'),
            'email' => $this->ask('Enter your email'),
            'password' => $this->secret('Choose password'),
            'password_confirmation' => $this->secret('Confirm password'),
            'roles' => [Role::ADMIN]
        ]);

        try {
            $data = app(StoreRequest::class)->validated();
        } catch(ValidationException $e) {
            $this->error($e->getMessage());
            return;
        }

        $admin = UserService::store($data);

        $this->info('User created successfully.');

        $tableData = $admin->toArray();
        unset($tableData['id']);

        $this->table(['name', 'email'], [$tableData]);

        return 0;
    }
}
