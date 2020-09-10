<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
        /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $faker = Faker::create('id_ID');


        $data = [
            [1,'admin@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],//1
            [2,'vickyhermawan99@mail.ugm.ac.id', bcrypt('12345678'),'2020-05-20 14:32:10'],//2
            [2,'zainulmutaqin15352@gmail.com', bcrypt('12345678'),'2020-05-20 15:32:10'],    //3       
            [3,'Icinbachan15@gmail.com', bcrypt('12345678'),'2020-05-20 15:32:10'], //4
            [3,'hv3344588@gmail.com', bcrypt('12345678'),'2020-05-20 15:32:10'], //5
            [3,'hv334453388@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'], //6
            [3,'ftovihe@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'], //7
            [4,'panutmulyono6169@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'], //8
            [3,'deni123@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
            [3,'tania123@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
            [3,'putri123@gmail.com', bcrypt('12345678'),'2020-05-20 15:32:10'],
            [3,'dendi123@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
            [3,'masdan123@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
            [3,'hv33445588@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
            [4,'nadiem123@gmail.com', bcrypt('12345678'),'2020-05-20 13:32:10'],
            [2,'osissma1@gmail.com', bcrypt('12345678'),'2020-05-20 14:32:10'],
        ];

        for ($i=0; $i < count($data); $i++) {
            $id_role = $data[$i][0];
            $email = $data[$i][1];
            $password = $data[$i][2];     
            $created_at = $data[$i][3];
            $updated_at = Carbon::now();

            DB::table('users')->insert([
                'id_role' => $id_role,
                'email' => $email,
                'password' => $password,     
                'created_at' => $created_at,
                'updated_at' => $updated_at
            ]);
        }
    }
}
