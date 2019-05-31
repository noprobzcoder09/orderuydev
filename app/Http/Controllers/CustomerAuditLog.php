<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerAuditLog extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($customer_id)
    {
        return view('pages.audittrail.customer.index', ['customer_id' => $customer_id]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($customer_id, $id)
    {
        return view('pages.audittrail.customer.view', ['id' => $id, 'customer_id' => $customer_id,]);
    }
    
}

