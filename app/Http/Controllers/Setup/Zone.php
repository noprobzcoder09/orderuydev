<?php

namespace App\Http\Controllers\Setup;

use App\Http\Controllers\Controller;

use App\Repository\UsersRepository;
use App\Services\InfusionsoftV2\InfusionsoftFactory;
use App\Services\Sync\Sync\DeliveryZone\DeliveryZoneSync;
use App\Services\Sync\Sync;
use App\Services\Sync\Lock;
use App\Services\CRUD;

use Request;

class Zone extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Zone Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a CRUD services for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
    const createUrl = 'delivery/zone/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'delivery/zone/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'delivery/zone/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'delivery/zone/delete/';

    /**
     * Contains verify name url
     *
     * @return var
     */
    const verifyNameUrl = 'delivery/zone/verify-name';

    /**
     * Contains list all url
     *
     * @return var
     */
    const masterlistUrl = 'delivery/zone/all-zones';

    
    /**
     * Contains disabled url
     *
     * @return var
     */
    const disabledUrl = 'delivery/zone/disabled';    

    /**
     * Contains list all url
     *
     * @return var
     */
    const listAllUrl = 'delivery/zone/list';    

    /**
     * Contains view path
     *
     * @return var
     */
	const view = 'pages.setup.zone.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->repository = new \App\Repository\ZoneRepository;
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
            // if ($this->repository->hasCustomerAttached($id)) {
            //     throw new \Exception("Editing is locked due to some customers are still using this data.", 1);
            // }

            $deliveryZoneSync = new DeliveryZoneSync();

            $deliveryZoneSync->locked(new Lock);

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
        $api = (new InfusionsoftFactory('oauth2'))->service();

        $out = $this->services->store(Request::all());

        $locations = array();
        foreach($this->services->getAll() as $row) {
            array_push($locations, $row->zone_name);
        }
        
        $deliveryZoneSync = new DeliveryZoneSync(
           $api
        );

        $deliveryZoneSync->syncDeliveryZone($locations);

        return $out;
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
        
        try
        {   
            // if ($this->repository->hasCustomerAttached(Request::get('id'))) {
            //     throw new \Exception("Updating is locked due to some customers are still using this data.", 1);
            // }

            $deliveryZoneSync = new DeliveryZoneSync();

            $deliveryZoneSync->locked(new Lock);

            $zone = $this->services->get(Request::get('id'));
            $oldZoneName = $zone->zone_name;
            $oldDeliveryAddress = $zone->delivery_address;
            
            $out = $this->services->update(Request::all());

            $zone = $this->services->get(Request::get('id'));
            $newZoneName = $zone->zone_name;
            $newDeliveryAddress = $zone->delivery_address;
                
            $locations = array();
            foreach($this->services->getAll() as $zone) {
                array_push($locations, $zone->zone_name);
            }

            $api = (new InfusionsoftFactory('oauth2'))->service();
            $deliveryZoneSync = new DeliveryZoneSync(
                $api
            );
            $sync = new Sync($deliveryZoneSync);
            $sync->handle(
                $locations, 
                $oldZoneName, 
                $newZoneName, 
                $oldDeliveryAddress, 
                $newDeliveryAddress
            );

            return $out;
        }

        catch(\Exception $e) {
            return \Helper::failed($e->getMessage());
        }
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
            
            $out =  $this->services->delete($id);

            if ($out) {
                $usersRepo = new UsersRepository;
                $usersRepo->updateCustomersCancelledAttachedDeliveryZoneToDefault($id, 0);
            }

            return $out;

        }

        catch(\Exception $e) {
            return \Helper::failed($e->getMessage());
        }
    }

}
