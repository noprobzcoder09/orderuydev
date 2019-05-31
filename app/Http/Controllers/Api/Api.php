<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\InfusionsoftAccount as Model;

class Api extends ApiController
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $env_type
     * @return \Illuminate\Http\Response
     */
    public function show($env_type)
    {
        strtolower($env_type);

        $data = Model::where('environment', $env_type)->first();

        return $this->respondSuccessfulWithData('Request Granted.', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if (!isset($request->client_id) || empty($request->client_id)) {
            return $this->respondUnprocessable('Client ID is required.');
        }

        if (!isset($request->client_secret) || empty($request->client_secret)) {
            return $this->respondUnprocessable('Client Secret is required.');
        }

        if (!isset($request->redirect_url) || empty($request->redirect_url)) {
            return $this->respondUnprocessable('Redirect URL is required.');
        }

        $data = Model::find($id);
        $data->client_id        = $request->client_id;
        $data->client_secret    = $request->client_secret;
        $data->redirect_url     = $request->redirect_url;
        $data->save();

        return $this->respondSuccessful('API Setting has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
