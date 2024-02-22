<?php

namespace Core\Services;

use Illuminate\Database\Eloquent\Model;
use Core\Contracts\Repository;
use Core\Contracts\Service as ServiceContract;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class Service implements ServiceContract
{
    use Identifiable, Configurable;

    /**
     * The Repository instance.
     *
     * @var Repository
     */
    protected $repository;

    /**
     * Create a new instance of the service.
     *
     * @param Repository $repository
     * @return void
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing the resources
     *
     * @param array $request
     * @return LengthAwarePaginator
     */
    public function index($request): LengthAwarePaginator
    {
        return $this->repository
            ->latest()
            ->paginate(@$request['limit']);
    }

    /**
     * Show details of the specified resource.
     *
     * @param array $request
     * @param int $id
     * @return Model
     */
    public function show(array $request, int $id): Model
    {
        return $this->repository->find($id);
    }

    /**
     * Create a new resource in storage.
     *
     * @param array $request
     * @return Model
     */
    public function store(array $request): Model
    {
        return $this->repository->create($request);
    }

    /**
     * Update the specified resource.
     *
     * @param array $request
     * @param int $id
     * @return Model
     */
    public function update(array $request, int $id): Model
    {
        $model = $this->repository->findOrFail($id);
        $model->update($request);

        return $model->refresh();
    }

    /**
     * Delete the specified resource from storage.
     *
     * @param array $request
     * @param int $id
     * @return bool
     */
    public function destroy(array $request, int $id): bool
    {
        return $this->repository->destroy($id);
    }
}
