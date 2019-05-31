<?php 

namespace App\Http\Controllers;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class FeatureTests implements FromView
{
    public function view(): View
    {
        $subscriptions = \App\Models\SubscriptionsSelections::with('subscriptions')->get();
        return view('tests.exports.excel', [
             'subscriptions' => $subscriptions
        ]);
    }

    public function export() 
    {
        $store = \Excel::store(new FeatureTests, request('name').'.xlsx', 'tests');
        if ($store) {
            return 1;
        }
    }
}