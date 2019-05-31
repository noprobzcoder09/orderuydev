<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;

use App\Services\CRUD;
use App\Services\MetaServices;
use Request;

class Meta extends Controller
{	

    /*
    |--------------------------------------------------------------------------
    | Meta Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Meta
    | includes a CRUD and Meta Services for assisting the application events and actions
    |
    */

    /**
     * Contains create url
     *
     * @return var
     */
	const createUrl = 'products/meta/create/';

    /**
     * Contains edit url
     *
     * @return var
     */
    const editUrl = 'products/meta/edit';

    /**
     * Contains update url
     *
     * @return var
     */
    const updateUrl = 'products/meta/update/';

    /**
     * Contains delete url
     *
     * @return var
     */
    const deleteUrl = 'products/meta/delete/';

    /**
     * Contains verfy sku url
     *
     * @return var
     */
    const verifySkuUrl = 'products/meals/verify-sku';

    /**
     * Contains masterlist url
     *
     * @return var
     */
    const masterlistUrl = 'products/meta/all-meta';

    /**
     * Contains search field url
     *
     * @return var
     */
    const searchFieldUrl = 'products/meta/search-field/';

    /**
     * Contains view path page
     *
     * @return var
     */
    const view = 'pages.products.meals.';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->services = new CRUD(new \App\Repository\MetaRepository);
        $this->metaServices = new MetaServices(new \App\Repository\MetaRepository);
    }
    
    /**
     * Handle search field request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function searchField(): array
    {
        return $this->metaServices->searchField((string)Request::get('search'));
    }

    /**
     *  Handle create request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): array
    {   

        if (!empty(Request::get('id')) && empty(Request::get('search_field'))) {
            return $this->update();
        }

        $data = Request::all();

        $data['create_new'] = Request::get('create_new') == 'on' ? 1 : 0;

        $response = $this->metaServices->store($data);

        return array_merge($response, [
            'table' => view(self::view.'table-meta', [
                'metas' => $this->services->get(Request::get('meal_id'))])->render(),
                'metaEditUrl'       => self::editUrl,
                'metaDeleteUrl'     => self::deleteUrl
        ]);
    }

    /**
     * Handle update request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function update(): array
    {   
        $data = Request::all();
       
        $this->services->setId(Request::get('id'));
        
        $response = $this->services->update($data);

        return array_merge($response, [
            'table' => view(self::view.'table-meta', [
                'metas' => $this->services->get(Request::get('meal_id'))])->render(),
                'metaEditUrl'       => self::editUrl,
                'metaDeleteUrl'     => self::deleteUrl
        ]);
    }

    /**
     * Handle delete request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(int $mealId, int $id): array
    {   
        $response =  $this->services->delete($id);

        return array_merge($response, [
            'table' => view(self::view.'table-meta')->with([
                'metas' => $this->services->get($mealId),
                'metaEditUrl'       => self::editUrl,
                'metaDeleteUrl'     => self::deleteUrl
            ])->render()
        ]);
    }

    /**
     * Handle the edit request to the application
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id): array
    {   
        return $this->metaServices->edit($id);
    }
}
