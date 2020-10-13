<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteCategoryRequest;
use App\Models\SiteCategory;
use App\Services\SiteCategoryService;
use Illuminate\Http\JsonResponse;

class SiteCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse|object
     */
    public function index()
    {
        return $this->response(ReturnCorrect(SiteCategory::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SiteCategoryRequest $request
     * @return JsonResponse|object
     */
    public function store(SiteCategoryRequest $request)
    {
        return $this->response(SiteCategoryService::add($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|object
     */
    public function show($id)
    {
        return $this->response(SiteCategoryService::getDataById($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SiteCategoryRequest $request
     * @param int $id
     * @return JsonResponse|object
     */
    public function update(SiteCategoryRequest $request, $id)
    {
        return $this->response(SiteCategoryService::save($request->all(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        return $this->response(SiteCategoryService::delete($id));
    }
}
