<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuditLog extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.audittrail.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('pages.audittrail.view', ['id' => $id]);
    }
    
}

