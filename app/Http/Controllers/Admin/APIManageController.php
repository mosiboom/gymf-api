<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminApi;
use Illuminate\Http\Request;

class APIManageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(APIManageRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admin\AdminApi  $adminApi
     * @return \Illuminate\Http\Response
     */
    public function show(AdminApi $adminApi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admin\AdminApi  $adminApi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminApi $adminApi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admin\AdminApi  $adminApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminApi $adminApi)
    {
        //
    }
}
