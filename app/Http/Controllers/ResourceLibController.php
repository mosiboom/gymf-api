<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResourceLibraryRequest;
use App\Models\ResourceLibrary;
use App\Services\ResourceLibService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResourceLibController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ResourceLibraryRequest $request
     * @return JsonResponse|Response|object
     */
    public function index(ResourceLibraryRequest $request)
    {
        return $this->response(ResourceLibService::list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ResourceLibraryRequest $request
     * @return JsonResponse|Response|object
     */
    public function store(ResourceLibraryRequest $request)
    {
        return $this->response(ResourceLibService::save($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function show($id)
    {
        return $this->response(ResourceLibService::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ResourceLibraryRequest $request
     * @param int $id
     * @return JsonResponse|object
     */
    public function update(ResourceLibraryRequest $request, $id)
    {
        return $this->response(ResourceLibService::save($request->all(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function destroy($id)
    {
        return $this->response(ReturnCorrect(ResourceLibrary::destroy($id)));
    }

    /**
     * 资源分类
     * @return JsonResponse|object
     */
    public function category()
    {
        return $this->response(ReturnCorrect(ResourceLibService::CATEGORY_MAP));
    }
}
