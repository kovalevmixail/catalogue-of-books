<?php

use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create('ru_RU');

        $booksCount = 101;

        for ($i = 0; $i < $booksCount; $i++) {

            //случайность года и обложки
        	$year = rand(1, 3) == 3 ? '' : $faker->numberBetween(1850, 2016);
        	$cover = rand(1, 3) == 3 ? '' : $faker->imageUrl(200, 200);

	        DB::table('books')->insert([
	            'name' => $faker->realText(50),
	            'author' => $faker->name(),
	            'year' => $year,
	            'description' => $faker->text(1500),
	            'cover' => $cover
	        ]);
    	}
    }
}
