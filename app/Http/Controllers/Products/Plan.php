<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Services\CRUD;
use Request;

class Plan extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Plan Controller
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
	const createUrl = 'products/plan/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'products/plan/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'products/plan/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'products/plan/delete/';

    /**
     * Contains verify name url
     *
     * @return var
     */
    const verifyNameUrl = 'products/plan/verify-name';

    /**
     * Contains verify sku url
     *
     * @return var
     */
    const verifySkuUrl = 'products/plan/verify-sku';

    /**
     * Contains all plans url
     *
     * @return var
     */
    const masterlistUrl = 'products/plan/all-plans';

    /**
     * Contains cycle search url
     *
     * @return var
     */
    const cycleSearchUrl = 'products/plan/cycle-search';

    /**
     * Contains cycle search url
     *
     * @return var
     */
    const manageCycleMealsUrl = 'products/plan/manage-meals-status';
    
    const saveMealsChangeStatusUrl = 'products/plan/save-meals-status';

    const listPlansScheduleUrl = 'products/plan/schedule-list';

    /**
     * Contains plan path view
     *
     * @return var
     */
    const view = 'pages.products.plan.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\ProductPlanRepository);
        $this->cycle = new \App\Services\Cycle;
        $this->config = new \Configurations;
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {   
        // print_r($this->services->repository->getINFSProduct());die();   
        return view(self::view.'new')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::createUrl,
            'masterlistUrl' => self::masterlistUrl,
            'verifyNameUrl' => self::verifyNameUrl,
            'verifySkuUrl'  => self::verifySkuUrl,
            'edit'          => false,
            'products'      => $this->services->repository->getINFSProduct()
        ]);
    }

    /**
     * Show's the application edit page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): string
    {   
        // echo "<pre>";
        // print_r($this->services->repository->getINFSProduct());die();  
        $response = [
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::updateUrl,
            'masterlistUrl' => self::masterlistUrl,
            'edit'          => true,
            'products'      => $this->services->repository->getINFSProduct()
        ];

        $response = array_merge($response, ['data' => $this->services->get($id)]);

        return view(self::view.'edit')->with($response);
    }

    /**
     * Show's the application masterlist page
     *
     * @return \Illuminate\Http\Response
     */
    public function masterlist(): string
    {
        return view(self::view.'index')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'editUrl'       => self::editUrl,
            'deleteUrl'     => self::deleteUrl,
            'data'         => $this->services->getAll()
        ]);
    }

    /**
     * Show's the application scheduler page
     *
     * @return \Illuminate\Http\Response
     */
    public function scheduler(): string
    {
        return view(self::view.'scheduler')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'cycleSearchUrl' => self::cycleSearchUrl,
            'manageCycleMealsUrl' => self::manageCycleMealsUrl,
            'saveMealsChangeStatusUrl' => self::saveMealsChangeStatusUrl,
            'listPlansScheduleUrl'   => self::listPlansScheduleUrl,
            'batch'         => $this->config->getActiveBatch(),
            'batchList'     => $this->cycle->getBatch()
        ]);
    }

    /**
     * Show's the application manage meals status page
     *
     * @return \Illuminate\Http\Response
     */
    public function manageMeals(int $id): string
    {
        return view(self::view.'manage-meals')->with([
            'view' => self::view,
            'cycle' => $this->cycle->get($id),
            'active' => $this->cycle->activeMeals(),
            'vegetarian' => $this->cycle->getVegetarianMeals(),
            'inactive' => $this->cycle->inactiveMeals(),
            'idsadded' => $this->cycle->currentMealsAdd($id),
            'idsremoved' => $this->cycle->currentMealsRemove($id),
        ]);
    }
    

    /**
     * Handle a save meal status change request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveMealStatus($cycleId): array
    {
        return $this->cycle->saveMealStatusChange($cycleId, Request::all());
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
        
        $data['vegetarian'] = Request::get('vegetarian') === 'true' ? 1 : 0;
        $data['sku'] = $this->trim($data['sku']);
        
        $response = $this->services->store($data);

        if( $response['success'] == true) 
        {
            if (isset($data['meal_plan_image'])) 
            {
                $this->services->storeFile(
                    $this->services->repository->id, 
                    Request::file('meal_plan_image'),
                    'public/images/plans'
                );
            }
        }
        return $response;
    }

    /**
     * Handle update request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(): array
    {   
        $data = Request::all();

        $data['vegetarian'] = Request::get('vegetarian') === 'true' ? 1 : 0;
        $data['sku'] = $this->trim($data['sku']);

        $this->services->setId(Request::get('id'));

        $response = $this->services->update($data);

        if (isset($data['meal_plan_image']) && !empty($data['meal_plan_image'])) 
        {
            $file = $this->services->storeFile(
                $this->services->repository->id, 
                Request::file('meal_plan_image'),
                'public/images/plans'
            );
        }

        return $response;
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

        $check_if_active = (new \App\Models\Subscriptions)->whereIn('status', ['paused', 'active', 'billing issue'])->first();

        if ($check_if_active) {
            return ['status' => 200, 'message' => 'This Meal Plan is currently active, you cannot delete it.', 'success' => false];
        }

        return array_merge($this->services->delete($id), [
            'content' => view(self::view.'table')->with([
                'data' => $this->services->getAll(),
                'editUrl'       => self::editUrl,
                'deleteUrl'     => self::deleteUrl
            ])->render()
        ]);
    }

    /**
     * Handle a verify sku request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verifySku(): string
    {   
        return $this->services->verify(Request::get('sku'));
    }    

    /**
     * Handle a masterlist request to the application
     *
     * @return string
     */
    public function scheduleList(): string
    {
        return view(self::view.'scheduler-cycles')->with([
            'cycles'         => $this->cycle->getAllByStatusWithActiveTiming(Request::get('status'), Request::get('batch'))
        ]);
    }

    private function trim($value)
    {
        return str_replace(' ', '', $value);
    }
}
