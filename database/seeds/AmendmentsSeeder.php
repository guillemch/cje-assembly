<?php

use Illuminate\Database\Seeder;

class AmendmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = App\User::where('role', '!=', 'admin')->get();

        factory(App\Amendment::class, 50)->create()->each(function (App\Amendment $amendment) use ($users) {
            foreach($users as $user) {
                $vote = new App\Vote;
                $vote->amendment_id = $amendment->id;
                $vote->user_id = $user->id;
                $vote->vote_for = rand(1, 3);
                $vote->ip = '';
                $vote->user_agent = '';
                $vote->save();
            }
        });
    }
}
