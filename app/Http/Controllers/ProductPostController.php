<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductPostRequest;
use App\Models\ProductPost;
use App\Services\ProductPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ProductPostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ProductPostRequest $request
     * @return JsonResponse|Response|object
     */
    public function index(ProductPostRequest $request)
    {
        return $this->response(ProductPost::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductPostRequest $request
     * @return JsonResponse|Response|object
     */
    public function store(ProductPostRequest $request)
    {
        return $this->response(ProductPostService::save($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function show($id)
    {
        return $this->response(ProductPostService::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductPostRequest $request
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function update(ProductPostRequest $request, $id)
    {
        return $this->response(ProductPostService::save($request->all(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function destroy($id)
    {
        return $this->response(ReturnCorrect(ProductPost::destroy($id)));
    }
}
