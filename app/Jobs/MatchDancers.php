<?php

namespace App\Jobs;

use App\Models\EventParticipation;
use App\Models\LikedUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MatchDancers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $event_id;
    private $age_range;
    private $height_range;

    private function sortingAge($user, $list): array {
        $user_age = explode("-", $user->user['birthday']);
        $user_age = (integer)date('Y') - (integer)$user_age[0];

        $sorted = array();
        $i = 0;
        while (sizeof($list)) {
            foreach ($list as $key => $dancer) {
                $dancer_age = explode("-", $dancer['birthday']);
                $dancer_age = (integer)date('Y') - (integer)$dancer_age[0];

                if ($dancer_age + $i == $user_age || $dancer_age - $i == $user_age){
                    $sorted[] = $dancer;
                    unset($list[$key]);
                }
            }
            $i++;
        }
        return $sorted;
    }

     private function sortingHeight($user, $list): array {
        DD("CURRENTLY WORKING ON IT");
        dd($user->user);
        $user_age = explode("-", $user->user['birthday']);
        $user_age = (integer)date('Y') - (integer)$user_age[0];

        $sorted = array();
        $i = 0;
        while (sizeof($list)) {
            foreach ($list as $key => $dancer) {
                $dancer_age = explode("-", $dancer['birthday']);
                $dancer_age = (integer)date('Y') - (integer)$dancer_age[0];

                if ($dancer_age + $i == $user_age || $dancer_age - $i == $user_age){
                    $sorted[] = $dancer;
                    unset($list[$key]);
                }
            }
            $i++;
        }
        return $sorted;
     }

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event_id, $age_range = 3, $height_range = 5)
    {
        $this->event_id = $event_id;
        $this->age_range = $age_range;
        $this->height_range = $height_range;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $males = array();
        $male_priority = array();
        $females = array();
        $female_priority = array();
        $all_participants = EventParticipation::where('event_id', $this->event_id)->get();

        /**
         * Separates the males and females and set $all_participants to null
         * to free up space from the memory to avoid any memory leaks
         */
        foreach($all_participants as $user) {
            if ($user->user->gender == 'male')
                $males[] = $user;
            else
                $females[] = $user;
        }
        $all_participants = null;

        foreach($males as $male) {
            $copy_females = $females;

            /**
             * If previous dancer is preferred this will get them //and shuffle them//
             * and will be chosen randomly to be added to priority list.
             */
            if (!!$male->previous_dancer) {
                $liked_users = LikedUsers::where('user', $male->user->id)->get()->toArray();
                foreach ($liked_users as $liked_user) {
                    foreach ($copy_females as $idx => $female) {
                        if ($liked_user['likes'] == $female->user_id) {
                            $male_priority[$male->user->id][] = $female->user->toArray();
                            unset($copy_females[$idx]);
                        }
                    }
                }
            }

            foreach(json_decode($male->priorities) as $priority) {
                switch($priority) {
                    case 'age':
//                        $male_priority[$male->user->id] = $this->sortingAge($male, $male_priority[$male->user->id]);
                        break;
                    case 'height':
                        $male_priority[$male->user->id] = $this->sortingHeight($male, $male_priority[$male->user->id]);
                        break;
                    case 'level':
                        break;
                }
            }
            dd($male_priority[$male->user->id]);

            /**
             * This will add randomly persons that matches the range and
             * already defined preference to choose the dancer to dance
             */

            die();

            var_dump('+++++++++++++++++++++++++++++++++++++++++++++++++');
            $birthDate = explode("-", $male->user['birthday']);
            $age = (integer)date('Y') - (integer)$birthDate[0];
            var_dump('id: ' . $male->id);
            var_dump('uid: ' . $male->user->toArray()['id']);
            var_dump('age: ' . $age);
            var_dump('height: ' . $male->user->toArray()['height']);
            var_dump('dancing_level: ' . $male->user->toArray()['dancing_level']);
            var_dump('prio: ' . ($male->priorities));
            var_dump('+++++++++++++++++++++++++++++++++++++++++++++++++');
            foreach ($copy_females as $female){
                $girly = $female->user->toArray();
                $birthDate = explode("-", $girly['birthday']);
                $age = (integer)date('Y') - (integer)$birthDate[0];

                var_dump('-----------------------------------------');
                var_dump('id: ' . $girly['id']);
                var_dump('age: ' . $age);
                var_dump('height: ' . $girly['height']);
                var_dump('dancing_level: ' . $girly['dancing_level']);
            };
            die();

            /**
             * This will add randomly persons that matches the range and
             * already defined preference to choose the dancer to dance
             */
        }

        foreach($females as $female) {
            $female_priority[] = $female->user->id;
        }
var_dump("....");
            dd($male_priority);
    }
}
