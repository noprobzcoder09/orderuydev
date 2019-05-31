    <?php

use Illuminate\Database\Seeder;

class InsertCountry extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (DB::table('country')->where('country_code','AU')->count() <= 0) {
            DB::table('country')
            	->insert([
            			'country_code' => 'AU',
            			'country' => 'Australia'
            		]);
        }
    }
}
