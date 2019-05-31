<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;
use App\Services\CRUD;
use Request;

class Coupons extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Timing Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a CRUD Services for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
    const createUrl = 'coupons/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'coupons/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'coupons/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'coupons/delete/';

    /**
     * Contains verify name url
     *
     * @return var
     */
    const verifyNameUrl = 'coupons/verify-name';

    /**
     * Contains list all url
     *
     * @return var
     */
    const masterlistUrl = 'coupons/all-coupons';

    /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.setup.coupons.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\CouponsRepository);
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {
    	return view(self::view.'new')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'actionUrl'     => self::createUrl,
            'masterlistUrl' => self::masterlistUrl,
            'verifyNameUrl' => self::verifyNameUrl,
            'edit'          => false
    	]);
    }

    /**
     * Show's the application edit page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): string
    {   
        $response = [
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::updateUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => true
        ];

        $response = array_merge($response, ['data' => $this->services->get($id)]);

        return view(self::view.'edit')->with($response);
    }

    /**
     * Show's the application list all page
     *
     * @return \Illuminate\Http\Response
     */
    public function masterlist(): string
    {
    	return view(self::view.'index')->with([
    		'breadcrumb' 	=> $this->breadcrumb(),
    		'view'			=> self::view,
            'editUrl'       => self::editUrl,
            'deleteUrl'     => self::deleteUrl,
            'data'          => $this->services->getAll()
    	]);
    }

    /**
     * Handle a create request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function create(): array
    {
        $data = Request::all();

        if (isset($data['discount_type']) && $data['discount_type'] == 'Percent') {
            
            if ($data['discount_value'] < 0) {
                return [
                    "status" => 200, 
                    "success" => false,
                    "message" => "Discount will not be a negative value."
                ];
            }

            $data['discount_value'] = abs($data['discount_value']);

            if ($data['discount_value'] >= 100) {
                return [
                    "status" => 200, 
                    "success" => false,
                    "message" => "Discount Value will not be greater that 100%."
                ];
            }
           
        }else{
            $data['discount_value'] = abs($data['discount_value']);
        }


        $data['min_order'] = abs($data['min_order']);
        $data['max_uses']  = abs($data['max_uses']);     

        $data['solo'] = Request::get('solo') === 'on' ? 1 : 0;
        $data['onetime'] = Request::get('onetime') === 'on' ? 1 : 0;
        $data['recur'] = Request::get('recur') === 'on' ? 1 : 0;

        return $this->services->store($data);
    }

    /**
     * Handle a update request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(): array
    {   
        $data = Request::all();

        $this->services->setId(Request::get('id'));

        if (isset($data['discount_type']) && $data['discount_type'] == 'Percent') {
            
            if ($data['discount_value'] < 0) {
                return [
                    "status" => 200, 
                    "success" => false,
                    "message" => "Discount will not be a negative value."
                ];
            }

            $data['discount_value'] = abs($data['discount_value']);

            if ($data['discount_value'] >= 100) {
                return [
                    "status" => 200, 
                    "success" => false,
                    "message" => "Discount Value will not be greater that 100%."
                ];
            }
           
        }else{
            $data['discount_value'] = abs($data['discount_value']);
        }


        $data['min_order'] = abs($data['min_order']);
        $data['max_uses']  = abs($data['max_uses']);
        
        $data['solo'] = Request::get('solo') === 'on' ? 1 : 0;
        $data['onetime'] = Request::get('onetime') === 'on' ? 1 : 0;
        $data['recur'] = Request::get('recur') === 'on' ? 1 : 0;
        
        return $this->services->update($data);
    }

    /**
     * Handle a delete request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(int $id): array
    {   
        $response =  $this->services->delete($id);

        return array_merge($response, [
            'content' => view(self::view.'table')->with([
                'data' => $this->services->getAll(),
                'editUrl'       => self::editUrl,
                'deleteUrl'     => self::deleteUrl
            ])->render()
        ]);
    }

    /**
     * Handle a verify name request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifyName(): string
    {   
        return $this->services->verify(Request::get('zone_name'));
    }

}
