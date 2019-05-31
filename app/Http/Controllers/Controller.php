<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Request;
use Auth;
use App\Traits\Auditable;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Auditable;


    public function breadcrumb($removeIndex = ''): array
    {
    	$url = url()->current();
    	$base = url('/');
    	
    	$path = explode('/',str_replace($base, '', $url));
    	$path[0] = 'home';

    	$path = array_map( function($data) { return ucfirst($data);  }, $path);
        
        if ($removeIndex == 'last') {
            $path = array_splice($path, 0, count($path)-1);
        }
    	
    	return $path;
    }

    public function getDeliveryTimingByZone($id)
    {   
        $data = [];
        $model = new \App\Models\DeliveryZone;
        $object = new \App\Repository\CycleRepository;
        // $activeBatch = (new \Configurations)->getActiveBatch();

        foreach($model->timings()->where('delivery_zone_id',$id)->get() as $row) {
            $deliverydate = date('l jS F Y', strtotime($row->delivery_date));
            
            $data[] = [
                'id'    => $row->delivery_zone_timings_id,
                'date'  => $deliverydate
            ];
        }

        return $data;
    }

    public function verifyEmail()
    {
        $repository = new \App\Repository\UsersRepository;

        if (empty(Request::get('email'))) 
            throw new \Exception("Email is Required.", 1);
            

        return $repository->verify(Request::get('email')) ? 1 : 0;
    }

    public function account()
    {
        $repository = new \App\Repository\CustomerRepository;
        return $repository->getAccount(Auth::id());
    }

    public function getcards()
    {   
        $id = empty(Auth::id()) ? 0 : Auth::id();
        if(Request::get('id')) {
            $id = (int)Request::get('id');
        }

        return [
            'cards'=> (new \App\Services\Cards)->getSavedCards($id), 
            'default' => (new \App\Repository\CustomerRepository)->defaultCard($id)
        ];
    }
    
    public function updateDefaultCard()
    {
        $id = empty(Auth::id()) ? 0 : Auth::id();
        if(Request::get('id')) {
            $id = (int)Request::get('id');
        }

        //$cardId = Request::get('defaultCard');
        $cardId = Request::get('my_card');
        if (!empty($cardId)) {

            $old_card = (new \App\Models\UserDetails)->where('user_id', $id)->first();

            $this->audit('User Change Default Card', $old_card->user->name.' changed default card from '.$old_card->default_card.' into '.$cardId, '');
            
            return (string)(new \App\Services\Cards)->updateDefaultCard($id, $cardId);
        }
        return 'false';
    }
}
