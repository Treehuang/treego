<?php

use Illuminate\Database\Seeder;

class CertificatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Certificat::class, 70)->create();
    }
}
