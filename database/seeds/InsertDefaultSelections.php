<?php

use Illuminate\Database\Seeder;

class InsertDefaultSelections extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        foreach(DB::table('meal_plans')->get() as $row)
        {
            if (DB::table('cycles_meal_plans')->where('meal_plans_id',$row->id)->count() <= 0) 
            {   
                $data = [];
                foreach(DB::table('meals')->where('vegetarian',$row->vegetarian)->get() as $meal)
                {
                    $data[] = $meal->id;
                }

                DB::table('cycles_meal_plans')
                    ->insert([
                            'cycle_id'  => 1,
                            'meal_plans_id' => $row->id,
                            'default_selections' => json_encode($data)
                        ]);
            }
        }
    }
}
