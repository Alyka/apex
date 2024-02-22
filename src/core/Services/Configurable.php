<?php

namespace Core\Services;

use Generator;

trait Configurable
{
    /**
     * Get the module's configurations.
     *
     * @param string|null $key
     * @return mixed
     */
    public static function config($key = '')
    {
        $key = $key ? ".{$key}" : '';
        return config(static::name()."{$key}");
    }

    /**
     * Set the default config values in the database.
     *
     * @return Generator
     */
    public function setDefaultConfig(): Generator
    {
        $defaultConfigs = $this->config('defaults');

        if (empty($defaultConfigs)) return;

        yield [
            'message' => 'Setting '.$this->name().'...',
            'level' => 'info'
        ];

        foreach ($defaultConfigs as $config) {
            if (! $this->repository->where('name', $config['name'])->first()) {

                $this->store($config);

                yield [
                    'message' => $config['name'] . " set successfully\n",
                    'level' => 'info'
                ];
            } else {
                yield [
                    'message' => $config['name'] . " already exists",
                    'level' => 'error'
                ];
            }
        }
    }
}
