<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\Admin\AdminUser;
use App\Services\Admin\UserServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param AdminUserRequest $request
     * @return JsonResponse|Response|object
     */
    public function index(AdminUserRequest $request)
    {
        return $this->response(UserServices::list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminUserRequest $request
     * @return JsonResponse|Response|object
     */
    public function store(AdminUserRequest $request)
    {
        return $this->response(UserServices::save($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function show($id)
    {
        return $this->response(UserServices::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AdminUserRequest $request
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function update(AdminUserRequest $request, $id)
    {
        return $this->response(UserServices::save($request->all(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function destroy($id)
    {
        return $this->response(ReturnCorrect(AdminUser::destroy($id)));
    }

}
