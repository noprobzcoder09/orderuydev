<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;

use App\Services\CRUD;
use Request;

class ZT extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Zone Timing Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a CRUD for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
    const createUrl = 'delivery/zone/timing/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'delivery/zone/timing/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'delivery/zone/timing/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'delivery/zone/timing/delete/';

    /**
     * Contains verify name url
     *
     * @return var
     */
    const verifyNameUrl = 'delivery/zone/timing/verify-name';

    /**
     * Contains list all url
     *
     * @return var
     */
    const masterlistUrl = 'delivery/zone/timing/all-zone-timings';

    /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.setup.zone-timing.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\ZTRepository);
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {
        $response = [
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::createUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => false
        ];

            $response = array_merge($response, [
                'zones' => $this->services->repository->getZones(),
                'timings' => $this->services->repository->getTimings()
            ]);
    	return view(self::view.'new')->with($response);
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

        $response = array_merge($response, [
            'zones' => $this->services->repository->getZones(),
            'timings' => $this->services->repository->getTimings(),
            'data' => $this->services->get($id)
        ]);


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
            'data'         => $this->services->getAll()
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
        $data['zone_timing'] = [
            Request::get('delivery_zone_id'),
            Request::get('delivery_timings_id')
        ];
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
        $this->services->setId(Request::get('id'));
        $data = Request::all();
        $data['zone_timing'] = [
            Request::get('delivery_zone_id'),
            Request::get('delivery_timings_id')
        ];
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
}
