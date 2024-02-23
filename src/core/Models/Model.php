<?php

namespace Core\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * The model factory instance.
     *
     * @var string
     */
    protected $factory;

    /**
     * List of fields to be searched during a search query.
     *
     * @var string[]
     */
    protected $searchable = [];

    /**
     * Attributes the model can be filtered by.
     *
     * @var array[]
     */
    protected $filterable = [];

    /**
     * Attributes the model can be sorted by.
     *
     * @var array[]
     */
    protected $sortable = [];

    /**
     * Get default chunck size for bulk queries.
     *
     * @var integer
     */
    protected $chunkSize = 100;
}
