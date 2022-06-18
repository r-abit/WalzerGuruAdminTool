<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\EventParticipation;
use App\Models\EventPartner;
use App\Models\LikedUsers;
use App\Models\PreviousDancePartner;
use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
                    $copy = $all_persons['female']; elseif ($key == 'female')
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
                                    $male_priority[$person->user->id][] = $likes->user; elseif ($key == 'female')
                                    $female_priority[$person->user->id][] = $likes->user;
                                unset($copy[$idx]);
                            }
                        }
                    }
                }

                foreach (json_decode($person->priorities) as $priority) {
                    if ($key == 'male' && !array_key_exists($person->user->id, $male_priority)) {
                        $male_priority[$person->user->id] = array();
                        break;
                    } elseif ($key == 'female' && !array_key_exists($person->user->id, $female_priority)) {
                        $female_priority[$person->user->id] = array();
                        break;
                    }

                    switch ($priority) {
                        case 'age':
                            if ($key == 'male') {
                                $temp_list = $this->sortingAge($person, $male_priority[$person->user->id]);
                                foreach ($temp_list as $temp_user)
                                    $male_priority[$person->user->id][] = $temp_user['id'];
                            } elseif ($key == 'female') {
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
                 * This commented code was used previously to define priorities by range.
                 * Now it is handled by score. In case that we need this function in the future I left it here.
                 */ //                foreach(json_decode($person->priorities) as $priority) {
                //
                //                    switch($priority) {
                //                        case 'age':
                //                            $list = $this->get_age_by_range($person->user->birthday, $copy);
                //                            $list = $this->sortingAge($person, $list);
                //                            foreach ($list as $each) {
                //                                if ($key == 'male')
                //                                    $male_priority[$person->user->id][] = $each['id'];
                //                                elseif ($key == 'female')
                //                                    $female_priority[$person->user->id][] = $each['id'];
                //                            }
                //                            $list = null;
                //                            break;
                //                        case 'height':
                //                            $list = $this->filter_by_range($person->user->height, $copy, 'height');
                //                            $list = $this->sorting_height_or_level($person, $list, 'height');
                //                            foreach ($list as $each) {
                //                                if ($key == 'male')
                //                                    $male_priority[$person->user->id][] = $each['id'];
                //                                elseif ($key == 'female')
                //                                    $female_priority[$person->user->id][] = $each['id'];
                //                            }
                //                            break;
                //                        case 'level':
                //                            $list = $this->filter_by_range($person->user->dancing_level, $copy, 'level');
                //                            $list = $this->sorting_height_or_level($person, $list, 'level');
                //                            foreach ($list as $each) {
                //                                if ($key == 'male')
                //                                    $male_priority[$person->user->id][] = $each['id'];
                //                                elseif ($key == 'female')
                //                                    $female_priority[$person->user->id][] = $each['id'];
                //                            }
                //                            break;
                //                    }
                //                }

                /**
                 * Implementing the second (or first depending on previous liked dancers) to order prio by score.
                 * The first prio will get 60% points, second 30% and the last prio only 30%.
                 * These are the weighting for the preference for the dancers.
                 */
                $now = new DateTime();
                $user_age = $now->diff(new DateTime($person->user->birthday))->y;

                foreach ($copy as $idx => $dancer) {

                    $copy[$idx]['score'] = 0;
                    foreach (json_decode($person->priorities) as $pos => $priority) {
                        $score = 0;
                        switch ($priority) {
                            case 'age':
                                $now = new DateTime();
                                $dancer_age = $now->diff(new DateTime($dancer->user->birthday))->y;
                                $difference = 10 - abs($user_age - $dancer_age);
                                $score = ($difference > 0) ? $difference : 0;
                                break;
                            case 'height':
                                $dancer_height = $dancer->user->height;
                                $dancer_height = ($dancer->user->gender == 'male') ? $dancer_height - 10 : $dancer_height + 10;
                                $difference = 0;
                                if ($dancer->user->gender == 'male' && $person->user->heigh <= $dancer_height)
                                    $difference = 10 - abs($dancer_height - $person->user->height);
                                elseif ($dancer->user->gender == 'female' && $person->user->height >= $dancer_height)
                                    $difference = 10 - abs($dancer_height - $person->user->height);
                                $score = ($difference > 0) ? $difference : 0;
                                break;
                            case 'level':
                                $difference = abs($person->user->dancing_level - $dancer->user->dancing_level);
                                if ($difference == 0)
                                    $score = 10; elseif ($difference == 1)
                                    $score = 5;
                                break;
                        }

                        switch ($pos) {
                            case 0:
                                $copy[$idx]['score'] += round($score * 0.6, 2);
                                break;
                            case 1:
                                $copy[$idx]['score'] += round($score * 0.3, 2);
                                break;
                            case 2:
                                $copy[$idx]['score'] += round($score * 0.1, 2);
                                break;
                        }
                    }

                    $copy[$idx]['score'] = round($copy[$idx]['score'], 2);
                    $copy[$dancer->user->id] = $copy[$idx]['score'];
                    unset($copy[$idx]);
                }
                arsort($copy);
                foreach($copy as $dancer_id => $score) {
                    if ($score > 0) {
                        if ($key == 'male')
                            $male_priority[$person->user->id][] = $dancer_id;
                        elseif ($key == 'female')
                            $female_priority[$person->user->id][] = $dancer_id;
                        unset($copy[$dancer_id]);
                    }
                    else {
                        unset($copy[$dancer_id]);
                        $copy[] = $dancer_id;
                    }
                }
            /**
             * These are the rest of the persons that could not be matched.
             * This list will be shuffled and added at the end
             */
            shuffle($copy);
            foreach ($copy as $pos => $user){
                if ($key == 'male')
                    $male_priority[$person->user->id][] = $user;
                elseif ($key == 'female')
                    $female_priority[$person->user->id][] = $user;
                unset($copy[$pos]);
            }
            if ($key == 'male')
                $male_priority[$person->user->id] = array('found' => false) + array("list" =>
                        $male_priority[$person->user->id]);
            elseif ($key == 'female')
                $female_priority[$person->user->id] = array('found' => false) + array("list" =>
                        $female_priority[$person->user->id]);
            }
        }


        /**
         * This will decide which of the lists should be run first male or female.
         * It is necessary to start from the smaller list for the algorithm.
         * Otherwise, it would have never known when the algorithm is done.
         */
        if (sizeof($female_priority) <= sizeof($male_priority)){
            $table_a = $female_priority;
            $female_priority = null;
            $table_b = $male_priority;
            $male_priority = null;
        } else {
            $table_a = $male_priority;
            $male_priority = null;
            $table_b = $female_priority;
            $female_priority = null;
        }

        $completed = false;

        while (!$completed) {
            foreach ($table_a as $a_id => $item) {
                $completed = true;
                if (!$item['found']) {
                    $completed = false;
                    $possible_partner = reset($item['list']);
                    if (!$table_b[$possible_partner]['found']) {
                        foreach (array_reverse($table_b[$possible_partner]['list']) as $id) {
                            if ($a_id == $id) {
                                $table_a[$a_id]['found'] = true;
                                $table_b[$possible_partner]['found'] = true;
                                break;
                            } else
                                array_pop($table_b[$possible_partner]['list']);
                        }
                    } else {
                        if (in_array($a_id, $table_b[$possible_partner]['list'])) {
                            $tmp = array();
                            foreach ($table_b[$possible_partner]['list'] as $i => $id) {
                                $tmp[] = $id;
                                if ($a_id != $id)
                                    unset($table_b[$possible_partner]['list'][$id]); else
                                    break;
                            }

                            $change_found = array_pop($table_b[$possible_partner]['list']);
                            $table_a[$a_id]['found'] = true;
                            $table_a[$change_found]['found'] = false;
                            array_shift($table_a[$change_found]['list']);
                            $table_b[$possible_partner]['list'] = $tmp;
                        }
                    }
                }
            }
        }

        $data = array();
        $result = array('match' => array(), 'no_match' => array());

        foreach ($table_b as $i => $person_b){
            if ($person_b['found'] == true){
                $partner_id = array_pop($person_b['list']);
                $result['match'][$i] = $partner_id;
                $data[] = array('user' => $i, 'partner' => $partner_id, 'event_id' => $this->event_id);
                $data[] = array('user' => $partner_id, 'partner' => $i, 'event_id' => $this->event_id);
            }
            else
                $result['no_match'][] = $i;
        }

        $event_date = Event::where('id', $this->event_id)->first('date')->toArray()['date'];
        $date_now = date('Y-m-d H:i:s',strtotime('now'));
        if ($event_date <= $date_now){
            foreach ($data as $row)
                if(!PreviousDancePartner::where('user', $row['user'])->where('partner', $row['partner'])->exists())
                    PreviousDancePartner::insert($row);
        }

        EventPartner::insert($data);

        Log::info($result);
    }
}
