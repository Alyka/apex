<?php

namespace Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface Service extends Identifiable, Configurable
{
    /**
     * Display a listing the resources
     *
     * @param array $requestAttributes
     * @return LengthAwarePaginator
     */
    public function index($requestAttributes): LengthAwarePaginator;

    /**
     * Show details of the specified resource.
     *
     * @param array $request
     * @param int $id
     * @return Model
     */
    public function show(array $request, int $id): Model;

    /**
     * Create a new resource in storage.
     *
     * @param array $request
     * @return Model
     */
    public function store(array $request): Model;

    /**
     * Update the specified resource.
     *
     * @param array $request
     * @param int $id
     * @return bool
     */
    public function update(array $request, int $id): bool;

    /**
     * Delete the specified resource from storage.
     *
     * @param array $request
     * @param int $id
     * @return bool
     */
    public function destroy(array $request, int $id): bool;
}
