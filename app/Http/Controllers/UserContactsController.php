<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserContactsRequest;
use App\Models\UserContacts;
use App\Services\UserContactsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserContactsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param UserContactsRequest $request
     * @return JsonResponse|Response|object
     */
    public function index(UserContactsRequest $request)
    {
        return $this->response(UserContactsService::list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserContactsRequest $request
     * @return JsonResponse|Response|object
     */
    public function store(UserContactsRequest $request)
    {
        $input = $request->all();
        $input['ip'] = $request->getClientIp();
        return $this->response(UserContactsService::save($input));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function show($id)
    {
        return $this->response(UserContactsService::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function destroy($id)
    {
        return $this->response(ReturnCorrect(UserContacts::destroy($id)));

    }
}
