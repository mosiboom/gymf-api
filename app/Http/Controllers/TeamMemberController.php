<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamMemberRequest;
use App\Models\TeamMember;
use App\Services\TeamMemberService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TeamMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param TeamMemberRequest $request
     * @return Response|object
     */
    public function index(TeamMemberRequest $request)
    {
        return $this->response(TeamMemberService::list($request->all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeamMemberRequest $request
     * @return Response|object
     */
    public function store(TeamMemberRequest $request)
    {
        return $this->response(TeamMemberService::save($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function show($id)
    {
        return $this->response(TeamMemberService::getOne($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TeamMemberRequest $request
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function update(TeamMemberRequest $request, $id)
    {
        return $this->response(TeamMemberService::save($request->all(), $id));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse|Response|object
     */
    public function destroy($id)
    {
        return $this->response(ReturnCorrect(TeamMember::destroy($id)));
    }
}
