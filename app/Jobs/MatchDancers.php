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

    private function sortingAge($user, &$list): array {
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

    private function sorting_height_or_level($user, &$list, $sort): array {
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
        $all_persons = array();

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
                $all_persons['male'][] = $user;
            else
                $all_persons['female'][] = $user;
        }
        $all_participants = null;

        foreach($all_persons as $key => $group) {
            if ($key == 'male')
                $person_group = $all_persons['male'];
            else
                $person_group = $all_persons['female'];

            foreach($person_group as $person) {
                if ($key == 'male')
                    $copy = $all_persons['female'];
                elseif ($key == 'female')
                    $copy = $all_persons['male'];

                /**
                 * If previous dancer is preferred this will get them //and shuffle them//
                 * and will be chosen randomly to be added to priority list.
                 */
                if (!!$person['previous_dancer']) {
                    $liked_users = LikedUsers::where('user', $person->user->id)->get()->toArray();
                    foreach ($liked_users as $liked_user) {
                        foreach ($copy as $idx => $likes) {
                            if ($liked_user['likes'] == $likes->user_id) {
                                if ($key == 'male')
                                    $male_priority[$person->user->id][] = $likes->user;
                                elseif ($key == 'female')
                                    $female_priority[$person->user->id][] = $likes->user;
                                unset($copy[$idx]);
                            }
                        }
                    }
                }

                foreach(json_decode($person->priorities) as $priority) {
                    if ($key == 'male' && !array_key_exists($person->user->id, $male_priority)) {
                        $male_priority[$person->user->id] = array();
                        break;
                    }
                    elseif ($key == 'female' && !array_key_exists($person->user->id, $female_priority)) {
                        $female_priority[$person->user->id] = array();
                        break;
                    }

                    switch($priority) {
                        case 'age':
                            if ($key == 'male') {
                                $temp_list = $this->sortingAge($person, $male_priority[$person->user->id]);
                                foreach ($temp_list as $temp_user)
                                    $male_priority[$person->user->id][] = $temp_user['id'];
                            } elseif ($key == 'female'){
                                $temp_list = $this->sortingAge($person, $female_priority[$person->user->id]);
                                foreach ($temp_list as $temp_user)
                                    $female_priority[$person->user->id][] = $temp_user['id'];
                            }
                            break 2;
                        case 'height':
                            if ($key == 'male') {
                                $temp_list = $this->sorting_height_or_level($person, $male_priority[$person->user->id], 'height');
                                foreach ($temp_list as $temp_user)
                                    $male_priority[$person->user->id][] = $temp_user['id'];
                            } elseif ($key == 'female') {
                                $temp_list = $this->sorting_height_or_level($person, $female_priority[$person->user->id], 'height');
                                foreach ($temp_list as $temp_user)
                                    $female_priority[$person->user->id][] = $temp_user['id'];
                            }
                            break 2;
                        case 'level':
                            if ($key == 'male') {
                                $temp_list = $this->sorting_height_or_level($person, $male_priority[$person->user->id], 'level');
                                foreach ($temp_list as $temp_user)
                                    $male_priority[$person->user->id][] = $temp_user['id'];
                            } elseif ($key == 'female') {
                                $temp_list = $this->sorting_height_or_level($person, $female_priority[$person->user->id], 'level');
                                foreach ($temp_list as $temp_user)
                                    $female_priority[$person->user->id][] = $temp_user['id'];
                            }
                            break 2;
                    }
                }

                /**
                 * This will add sorted (best to worst) persons that match the range and
                 * with there already defined preference of the dancer to dance with.
                 */
                foreach(json_decode($person->priorities) as $priority) {

                    switch($priority) {
                        case 'age':
                            $list = $this->get_age_by_range($person->user->birthday, $copy);
                            $list = $this->sortingAge($person, $list);
                            foreach ($list as $each) {
                                if ($key == 'male')
                                    $male_priority[$person->user->id][] = $each['id'] . " _> " . $each['birthday'];
                                elseif ($key == 'female')
                                    $female_priority[$person->user->id][] = $each['id'] . " _> " . $each['birthday'];
                            }
                            $list = null;
                            break;
                        case 'height':
                            $list = $this->filter_by_range($person->user->height, $copy, 'height');
                            $list = $this->sorting_height_or_level($person, $list, 'height');
                            foreach ($list as $each) {
                                if ($key == 'male')
                                    $male_priority[$person->user->id][] = $each['id'] . " _> " . $each['height'];
                                elseif ($key == 'female')
                                    $female_priority[$person->user->id][] = $each['id'] . " _> " . $each['height'];
                            }
                            break;
                        case 'level':
                            $list = $this->filter_by_range($person->user->dancing_level, $copy, 'level');
                            $list = $this->sorting_height_or_level($person, $list, 'level');
                            foreach ($list as $each) {
                                if ($key == 'male')
                                    $male_priority[$person->user->id][] = $each['id'] . " _> " . $each['dancing_level'];
                                elseif ($key == 'female')
                                    $female_priority[$person->user->id][] = $each['id'] . " _> " . $each['dancing_level'];
                            }
                            break;
                    }
                }

            /**
             * These are the rest of the persons that could not be matched.
             * This list will be shuffled and added at the end
             */
                shuffle($copy);
                foreach ($copy as $pos => $user){
                    if ($key == 'male')
                        $male_priority[$person->user->id][] = $user->user->id;
                    elseif ($key == 'female')
                        $female_priority[$person->user->id][] = $user->user->id;

                    unset($copy[$pos]);
                }
            }
        }

        Log::info($male_priority);
        Log::info("-----------------------------------------------------------------------");
        Log::info($female_priority);
    }
}
