<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;

use App\Services\Cutover\Generate;
use App\Services\Cutover\Generator\WhenEmpty;
use App\Services\Sync\Sync\DeliveryTiming\DeliveryTimingSync;
use App\Services\Sync\Sync;
use App\Services\Sync\Lock;
use App\Services\CRUD;
use Request;

class Timing extends Controller
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
    const createUrl = 'delivery/timing/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'delivery/timing/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'delivery/timing/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'delivery/timing/delete/';

    /**
     * Contains verify name url
     *
     * @return var
     */
    const verifyNameUrl = 'delivery/timing/verify-name';

    /**
     * Contains list all url
     *
     * @return var
     */
    const masterlistUrl = 'delivery/timing/all-timings';

    /**
     * Contains disabled url
     *
     * @return var
     */
    const disabledUrl = 'delivery/timing/disabled';    

    /**
     * Contains list all url
     *
     * @return var
     */
    const listAllUrl = 'delivery/timing/list';    

    /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.setup.timing.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = new \App\Repository\DeliveryZoneTimingRepository;
        $this->services = new CRUD($this->repository);
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

        try
        {
            if ($this->repository->hasCustomerAttached($id)) {
                throw new \Exception("Editing is locked due to some customers are still using this data.", 1);
            }

            $response = array_merge($response, ['data' => $this->services->get($id)]);

            return view(self::view.'edit')->with($response);

        }

        catch(\Exception $e) {
            return $e->getMessage();
        }
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
            'disabledUrl'   => self::disabledUrl,
            'listAllUrl'    => self::listAllUrl
    	]);
    }

     /**
     * Show's the application list all page
     *
     * @return \Illuminate\Http\Response
     */
    public function listAll(): string
    {
        return view(self::view.'table')->with([
            'editUrl'       => self::editUrl,
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
        
        $data['cutoff_time'] = date('H:i',strtotime($data['cutofftime_hour'].':'.$data['cutofftime_minute'].' '.$data['cutofftime_a']));
        
        $data['timing'] = [
            $data['delivery_day'],
            $data['cutoff_day'],
            $data['cutoff_time']
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
        $data = Request::all();

        $data['cutoff_time'] = date('H:i',strtotime($data['cutofftime_hour'].':'.$data['cutofftime_minute'].' '.$data['cutofftime_a']));
        
        $data['timing'] = [
            $data['delivery_day'],
            $data['cutoff_day'],
            $data['cutoff_time']
        ];

        $this->services->setId(Request::get('id'));

        try
        { 
            if ($this->repository->hasCustomerAttached(Request::get('id'))) {
                throw new \Exception("Updating is locked due to some customers are still using this data.", 1);
            }

            $out = $this->services->update($data);

            if ($out['success']) {
                $this->repository->clearFutureCyclesByTimingId(Request::get('id'));
                $generator = new WhenEmpty;
                $generator->create();
            }

            return $out;
        }

        catch(\Exception $e) {
            throw $e;
            return \Helper::failed($e->getMessage());
        }
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
        try
        {
            if ($this->repository->hasCustomerAttached($id)) {
                throw new \Exception("Deleting is locked due to some customers are still using this data.", 1);
            }
            
            $response =  $this->services->delete($id);

            if ($response['success']) {
                $this->repository->clearFutureCyclesByTimingId(Request::get('id'));
            }

            return array_merge($response, [
                'content' => view(self::view.'table')->with([
                    'data' => $this->services->getAll(),
                    'editUrl'       => self::editUrl,
                    'deleteUrl'     => self::deleteUrl
                ])->render()
            ]);
        }

        catch(\Exception $e) {
            return \Helper::failed($e->getMessage());
        }
    }

     /**
     * Handle a disabled request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function disabled(int $id): string
    {   
        return $this->services->disabled($id, Request::get('enabled') == 'true' ? 0 : 1);
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


    public function listByDeliveryZoneId() 
    {
        return $this->respondWithSuccess('Retrieving Delivery Zone Timing', $this->repository->getTimingsByDeliveryZoneId((int)Request::route('delivery_zone_id')));
    }

}
