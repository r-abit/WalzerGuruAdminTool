<?php

namespace App\Jobs;

use App\Models\EventParticipation;
use App\Models\LikedUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DateTime;
use Illuminate\Support\Facades\Log;

class MatchDancers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $event_id;
    private $age_range;
    private $height_range;
    private $level_range;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event_id, $age_range = 3, $height_range = 5, $level_range = 1)
    {
        $this->event_id = $event_id;
        $this->age_range = $age_range;
        $this->height_range = $height_range;
        $this->level_range = $level_range;
    }

    private function sortingAge($user, $list): array {
        $now = new DateTime();
        $user_age = $now->diff(new DateTime($user->user['birthday']))->y;

        $sorted = array();
        $i = 0;
        while (sizeof($list)) {
            foreach ($list as $key => $dancer) {
                $date = new DateTime($dancer['birthday']);
                $dancer_age = $now->diff($date)->y;

                if ($dancer_age + $i == $user_age || $dancer_age - $i == $user_age){
                    $sorted[] = $dancer;
                    unset($list[$key]);
                }
            }
            $i++;
        }
        return $sorted;
    }

    private function sorting_height_or_level($user, $list, $sort): array {
        $sorted = array();
        $i = 0;
        while (sizeof($list)) {
            foreach ($list as $key => $dancer) {
                if ($sort == 'height'){
                    if ($dancer['height'] + $i == $user->user->height || $dancer['height'] - $i == $user->user->height){
                        $sorted[] = $dancer;
                        unset($list[$key]);
                    }
                }
                elseif ($sort == 'level'){
                    if ($dancer['dancing_level'] + $i == $user->user->dancing_level || $dancer['dancing_level'] - $i == $user->user->dancing_level) {
                        $sorted[] = $dancer;
                        unset($list[$key]);
                    }
                }
            }
            $i++;
        }
        return $sorted;
    }

    private function get_age_by_range($user_birthday, &$list): array {
        $now = new DateTime();
        $user_birthday = $now->diff(new DateTime($user_birthday))->y;

        $filtered_list = array();
        foreach ($list as $pos => $person){
            $persons_age = $now->diff(new DateTime($person->user->birthday))->y;
            if ($persons_age >= $user_birthday - $this->age_range && $persons_age <= $user_birthday + $this->age_range) {
                $filtered_list[] = $person->user->toArray();
                unset($list[$pos]);
            }
        }

        return $filtered_list;
    }

    private function filter_by_range($user, &$list, $type): array {
        $filtered_list = array();
        foreach ($list as $pos => $person){
            if ($type == 'height') {
                if ($person->user->height >= $user - $this->height_range && $person->user->height <= $user + $this->height_range) {
                    $filtered_list[] = $person->user->toArray();
                    unset($list[$pos]);
                }
            } elseif ($type == 'level') {
               if ($person->user->dancing_level >= $user - $this->level_range && $person->user->dancing_level <=
                   $user + $this->level_range) {
                    $filtered_list[] = $person->user->toArray();
                    unset($list[$pos]);
                }
            }
        }

        return $filtered_list;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** Created list to separate males and females */
        $males = array();
        $females = array();

        /**
         * This will be the priority table ex. $male_priority = [ [m1 = [f1, f2, ...]], [...], ...]
         * This is needed to perform the Gale-Shapley algorithm
         */
        $male_priority = array();
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
//                if ($male->user_id == 158) dd($male_priority[$male->user->id]);
//                var_dump($male->user_id);
//                if (sizeof($male_priority) >=3 ) dd($male->user->id);
            }

            foreach(json_decode($male->priorities) as $priority) {
                if (!array_key_exists($male->user->id, $male_priority)) {
                    $male_priority[$male->user->id] = array();
                    break;
                }

                switch($priority) {
                    case 'age':
                        $male_priority[$male->user->id] = $this->sortingAge($male, $male_priority[$male->user->id]);
                        break 2;
                    case 'height':
                        $male_priority[$male->user->id] = $this->sorting_height_or_level($male, $male_priority[$male->user->id], 'height');
                        break 2;
                    case 'level':
                        $male_priority[$male->user->id] = $this->sorting_height_or_level($male, $male_priority[$male->user->id], 'level');
                        break 2;
                }
            }
//            $male_priority[$male->user->id][] = "-1----------------------------------------";

            /**
             * This will add sorted (best to worst) persons that match the range and
             * with there already defined preference of the dancer to dance with.
             */
//            $copy_females;
            foreach(json_decode($male->priorities) as $priority) {

                switch($priority) {
                    case 'age':
//                        $male_priority[$male->user->id][] = "-2----------------------------------------";
                        $list = $this->get_age_by_range($male->user->birthday, $copy_females);
                        $list = $this->sortingAge($male, $list);
                        foreach ($list as $female) $male_priority[$male->user->id][] = $female;
                        $list = null;
                        break;
                    case 'height':
//                        $male_priority[$male->user->id][] = "-3----------------------------------------";
                        $list = $this->filter_by_range($male->user->height, $copy_females, 'height');
                        $list = $this->sorting_height_or_level($male, $list, 'height');
                        foreach ($list as $female) $male_priority[$male->user->id][] = $female;
                        break;
                    case 'level':
//                        var_dump("----------   FIRST   ----------");
//                        var_dump('before:' . sizeof($copy_females));
//                        var_dump('before:' . sizeof($male_priority[$male->user->id]));
//                        $male_priority[$male->user->id][] = "-4----------------------------------------";
                        $list = $this->filter_by_range($male->user->dancing_level, $copy_females, 'level');
                        $list = $this->sorting_height_or_level($male, $list, 'level');
                        foreach ($list as $female) $male_priority[$male->user->id][] = $female;
//                        var_dump('after:' . sizeof($copy_females));
//                        var_dump('after:' . sizeof($male_priority[$male->user->id]));
//                        var_dump("----------    END    ----------");
                        break;
                }
            }
//$male_priority[$male->user->id][] = "-5----------------------------------------";

            /**
             * These are the rest of the persons that could not be matched.
             * This list will be shuffled and added at the end
             */
            shuffle($copy_females);
            foreach ($copy_females as $pos => $female){
                $male_priority[$male->user->id][] = $female->user->toArray();
                unset($copy_females[$pos]);
            }
        }
//        Log::info($male_priority);
        foreach($females as $female) {
            $female_priority[] = $female->user->id;
        }
    }
}
