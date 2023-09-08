<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classe::insert([
            [

                "name" => "salle_1",


            ],
            [
                "name" => "salle_2",

            ],
            [
                "name" => "salle_3",

            ],
            [
                "name" => "salle_4",

            ],
            [
                "name" => "salle_5",

            ],
            [
                "name" => "salle_6",

            ],
        ]);
    }
}
