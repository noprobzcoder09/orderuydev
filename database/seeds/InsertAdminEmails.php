<?php

use Illuminate\Database\Seeder;

class InsertAdminEmails extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {	
        
        $data = array (
            'slug' => 'report_admin_email',
            'name' => 'Report Admin Email',
            'value' => 'noel@fusedsoftware.com,victor@fusedsoftware.com,jerson@fusedsoftware.com'
        );

        if (DB::table('configurations')->where('slug','report_admin_email')->count() <= 0) {
            DB::table('configurations')->insert($data);
        } else {
            DB::table('configurations')
            ->where('slug','report_admin_email')
            ->update($data);
        }
        
    }
}
