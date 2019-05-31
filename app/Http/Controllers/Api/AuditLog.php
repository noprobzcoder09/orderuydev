<?php namespace App\Http\Controllers\Api;

use App\Models\AuditLog as Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transformers\AuditTrailTransformer;

class AuditLog extends ApiController
{

    function __construct(AuditTrailTransformer $transformer)
    {
        $this->transformer = $transformer;
    } 
       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->search   = isset($request->search) ? $request->search : '';

        $this->page     = isset($request->page)   ? $request->page   : 1;

        $this->rows     = isset($request->rows) && $request->rows <= 50 ? $request->rows : 50;

        $this->offset   = ($this->page - 1) * $this->rows;

        $this->customer_id = isset($request->customer_id) ? $request->customer_id : null;

        $total = Model::with(['user'])
                ->whereHas('user', function($query){
                    $query->where('name', 'LIKE', '%' . $this->search . '%');
                    if (!is_null($this->customer_id)) {
                        $query->where('id', $this->customer_id);
                    }
                })->count();

        $data = Model::with(['user'])
                ->whereHas('user', function($query){
                    $query->where('name', 'LIKE', '%' . $this->search . '%');
                    if (!is_null($this->customer_id)) {
                        $query->where('id', $this->customer_id);
                    }
                })
                ->skip($this->offset)
                ->take($this->rows)
                ->orderBy('created_at', 'DESC')
                ->get();

        return $this->respondSuccessfulWithData('Request Granted.', ['data' => $this->transformer->transformCollection($data->all()), 'total' => $total]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $data = Model::with(['user'])->find($id);

        return $this->respondSuccessfulWithData('Request Granted.', $this->transformer->transform($data));
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
        //
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

