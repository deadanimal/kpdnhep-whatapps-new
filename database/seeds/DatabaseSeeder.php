<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('feed_templates')->truncate();
        $this->call(FeedTemplatesTableSeeder::class);
    }
}
