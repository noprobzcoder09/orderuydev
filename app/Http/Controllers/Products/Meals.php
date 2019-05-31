<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Services\CRUD;
use App\Services\MetaServices;
use Request;

class Meals extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Meals Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling meals and meta
    | includes a CRUD and Meta Services for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
	const createUrl = 'products/meals/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'products/meals/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'products/meals/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'products/meals/delete/';

    /**
     * Contains verify sku url
     *
     * @return var
     */
    const verifySkuUrl = 'products/meals/verify-sku';

    /**
     * Contains masterlist url
     *
     * @return var
     */
    const masterlistUrl = 'products/meals/all-meals';

    /**
     * Contains meta new url
     *
     * @return var
     */
    const metaNewUrl = 'products/meta/create/';

    /**
     * Contains meta update url
     *
     * @return var
     */
    const metaUpdateUrl = 'products/meta/update/';

    /**
     * Contains meta edit url
     *
     * @return var
     */
    const metaEditUrl = 'products/meta/edit/';

    /**
     * Contains meta delete url
     *
     * @return var
     */
    const metaDeleteUrl = 'products/meta/delete/';

    /**
     * Contains search field url
     *
     * @return var
     */
    const metaSearchFieldUrl = 'products/meta/search-field/';

    /**
     * Contains product meals view
     *
     * @return var
     */
    const view = 'pages.products.meals.';

    const listUrl = 'products/meals/list';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\MealsRepository);
        $this->metaCRUDServices = new CRUD(new \App\Repository\MetaRepository);
        $this->metaServices = new MetaServices(new \App\Repository\MetaRepository);
    }

    /**
     * Show's the application new page
     *
     * @return \Illuminate\Http\Response
     */
    public function new(): string
    {
    	return view(self::view.'new')->with([
    		'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::createUrl,
            'editUrl'       => self::editUrl,
            'masterlistUrl' => self::masterlistUrl,
            'verifySkuUrl'  => self::verifySkuUrl,
            'edit'          => false
    	]);
    }

    /**
     * Show's the application meta page
     *
     * @return \Illuminate\Http\Response
     */
    public function meta(): string
    {      
        $meals = new MealsServices(new \App\Repository\MealsRepository);

        $response = [
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view
        ];

        $response = array_merge($response, $meals->meals());

    	return view(self::view.'meta-new')->with($response);
    }

    /**
     * Show's the application meals masterlist page
     *
     * @return \Illuminate\Http\Response
     */
    public function masterlist(): string
    {
    	return view(self::view.'index')->with([
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'masterlistUrl' => self::masterlistUrl,
            'listUrl'       => self::listUrl,
            'editUrl'       => self::editUrl,
            'deleteUrl'     => self::deleteUrl
        ]);
    }

    /**
     * Show's the application meals edit page
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id): string
    {   
        $response = [
            'id'            => $id,
            'breadcrumb'    => $this->breadcrumb(),
            'view'          => self::view,
            'actionUrl'     => self::updateUrl,
            'masterlistUrl' => self::masterlistUrl,
            'metaNewUrl'    => self::metaNewUrl,
            'metaDeleteUrl' => self::metaDeleteUrl,
            'metaSearchFieldUrl'    => self::metaSearchFieldUrl,
            'metaEditUrl'   => self::metaEditUrl,
            'metaUpdateUrl' => self::metaUpdateUrl,
            'edit'          => true
        ];

        $response = array_merge($response, [
            'data'  => $this->services->get($id),
            'metas' => $this->metaCRUDServices->get($id)
        ]);

        return view(self::view.'edit')->with($response);
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
        $data['meal'] = [
            Request::get('meal_sku'),
            Request::get('meal_name'),
        ];

        $data['vegetarian'] = Request::get('vegetarian') == 'on' ? 1 : 0;

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
        $data['meal'] = [
            Request::get('meal_sku'),
            Request::get('meal_name'),
        ];

        $data['vegetarian'] = Request::get('vegetarian') == 'on' ? 1 : 0;
        $data['status'] = Request::get('status') == 'on' ? 1 : 0;

        $this->services->setId(Request::get('id'));
        
        return $this->services->update($data);
    }

    /**
     * Handle a delete request to the application
     *
     * @param int $int
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
     * Handle a verify sku request to the application
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function verifySku(): string
    {   
        return $this->services->verify(Request::get('meal_sku'));
    }

    /**
     * Handle active meals request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function active(): string
    {   
        return $this->services->repository->getActive();
    }

    /**
     * Handle inactive meals request to the application
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function inactive(): string
    {   
        return $this->services->repository->getInactive();
    }

    /**
     * Handle a masterlist request to the application
     *
     * @return string
     */
    public function list(): string
    {
        return view(self::view.'table')->with([
            'editUrl'       => self::editUrl,
            'data'         => $this->services->repository->getAllByStatus(Request::get('status'))
        ]);
    }

}
