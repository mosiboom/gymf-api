<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Services\Admin\RoleService;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array|JsonResponse
     */
    public function index()
    {
        return $this->response(RoleService::list());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RoleRequest $request
     * @return array|JsonResponse
     */
    public function store(RoleRequest $request)
    {
        return $this->response(RoleService::save($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        return $this->response(RoleService::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RoleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(RoleRequest $request, $id)
    {
        return $this->response(RoleService::save($request->all(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return $this->response($id);
    }
}
