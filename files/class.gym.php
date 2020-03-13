<?php
    class gym {
        private $app;
        private $list;
        private $challenge;

        public function __construct($app) {
            $this->app = $app;
        }

        public function getList($uid=null, $category=null) {
            // Check cache
            $challenges = json_decode($this->app->cache->get('challenge_list', 10));

            if (!$challenges) {
                $sql = 'SELECT challenges.challenge_id AS `id`, CONCAT(challenges_groups.title, " Day ", challenges.name) as `title`, challenges.name, challenges_groups.title as `group`,
                LOWER(CONCAT("/challenges/", CONCAT_WS("/", challenges_groups.title, challenges.name))) as `uri`, challenges_completed.completed as `total_completed`,
                challenges_data.value AS `reward`
                FROM challenges
                INNER JOIN challenges_groups
                ON challenges_groups.title = challenges.group
                LEFT JOIN challenges_data
                ON challenges_data.challenge_id = challenges.challenge_id AND challenges_data.key = "reward"
                LEFT JOIN (SELECT COUNT(user_id) AS `completed`, challenge_id FROM users_challenges WHERE completed > 0 AND user_id != 69 GROUP BY challenge_id) `challenges_completed`
                ON challenges_completed.challenge_id = challenges.challenge_id 
                ORDER BY challenges_groups.order ASC, challenges.challenge_id ASC';

                $st = $this->app->db->prepare($sql);
                $st->execute();
                $challenges = $st->fetchAll();

                $this->app->cache->set('challenge_list', json_encode($challenges));
            }

            // Get list of completed challenges
            $sql = 'SELECT challenge_id, IF(users_challenges.completed > 0, 2, 1) as `completed` FROM users_challenges WHERE user_id = :uid';
            $st = $this->app->db->prepare($sql);
            $st->bindValue(':uid', $uid?$uid:$this->app->user->uid);
            $st->execute();
            $user_challenges = $st->fetchAll();

            // Create list
            $list = array();
            foreach ($challenges AS &$challenge) {
                // Check filter
                if ($category && trim(strtolower(str_replace('+', '', $challenge->group))) != trim($category)) {
                    continue;
                }

                // Assign progress based on $users_challenges
                $challenge->progress = 0;
                foreach ($user_challenges AS $l) {
                    if ($l->challenge_id == $challenge->id) {
                        $challenge->progress = $l->completed;
                        break;
                    }
                }

                if (!array_key_exists($challenge->group, $list)) {
                    $list[$challenge->group] = new stdClass();
                    $list[$challenge->group]->challenges = array();
                }
                array_push($list[$challenge->group]->challenges, $challenge);
            }

            return $list;
        }

        public function getGroups() {
            $st = $this->app->db->prepare('SELECT title FROM challenges_groups ORDER BY `order` ASC, `title` ASC');
            $st->execute();
            return $st->fetchAll();       
        }

        public function getChallengeFromID($id) {
            $st = $this->app->db->prepare('SELECT `group`, `name` FROM challenges WHERE challenge_id = :id LIMIT 1');
            $st->bindValue(':id', $id);
            $st->execute();
            $res = $st->fetch();

            if ($res) {
                return $this->getChallenge($res->group, $res->name, true);
            } else{
                return false;
            }
        }

        public function getChallenge($group, $name, $noSkip=false) {
            // Check cache for challenge data
            $cacheKey = 'challenge_data_' . $group . '_' . $name;
            $cache = $this->app->cache->get($cacheKey, 5);

            if ($cache):
                $challenge = unserialize($cache);

                $sql = "SELECT
                            IF(users_challenges.completed > 0, 1, 0) as `completed`,
                            users_challenges.completed as `completed_time`,
                            users_challenges.started,
                            IFNULL(users_challenges.attempts, 0) as `attempts`
                        FROM users_challenges
                        WHERE challenge_id = :challengeid AND user_id = :uid";

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':challengeid' => $challenge->challenge_id, ':uid' => $this->app->user->uid));
                $tmpChallenge = $st->fetch();

                // Merge users stats into cache response
                $challenge = (object) array_merge((array) $challenge, (array) $tmpChallenge);

                //Check if user has access
                if (isset($challenge->challenge_before_uri) && strtolower($challenge->group) == 'main') {
                    $sql = 'SELECT IF(users_challenges.completed > 0, 1, 0) as `completed` FROM challenges
                            LEFT JOIN users_challenges
                            ON users_challenges.user_id = :uid AND users_challenges.challenge_id = challenges.challenge_id
                            WHERE `group` = :group AND challenges.challenge_id < :challenge_id
                            ORDER BY challenges.challenge_id DESC
                            LIMIT 1';
                    
                    $st = $this->app->db->prepare($sql);
                    $st->execute(array(':challenge_id'=>$challenge->challenge_id, ':group'=>$group, ':uid'=>$this->app->user->uid));
                    $previous = $st->fetch();

                    if (!$noSkip && (!$previous || !$previous->completed)) {
                        header("Location: $challenge->challenge_before_uri?skipped");
                        die();
                    }
                }

                $this->challengeView($challenge->challenge_id);

                return $challenge;

            else:
                $before_after_sql = 'SELECT `challenge_id`, `name`, LOWER(CONCAT("/challenges/", CONCAT_WS("/", challenges_groups.title, challenges.name))) as `uri`
                                     FROM challenges
                                     INNER JOIN challenges_groups
                                     ON challenges_groups.title = challenges.group
                                     WHERE `group` = :group
                                     ORDER BY challenge_id';

                $sql = "SELECT challenges.challenge_id, challenges.name, challenges_groups.title AS `group`, CONCAT(`group`, ' Day ', challenges.name) as `title`,
                            IF(users_challenges.completed > 0, 1, 0) as `completed`, users_challenges.completed as `completed_time`, `started`,
                            IFNULL(users_challenges.attempts, 0) as `attempts`,
                            challenges_before.uri AS `challenge_before_uri`, challenges_after.uri AS `challenge_after_uri`
                        FROM challenges
                        INNER JOIN challenges_groups
                        ON challenges_groups.title = challenges.group
                        LEFT JOIN ({$before_after_sql} DESC) challenges_before
                        ON challenges_before.challenge_id < challenges.challenge_id
                        LEFT JOIN ({$before_after_sql} ASC) challenges_after
                        ON challenges_after.challenge_id > challenges.challenge_id
                        LEFT JOIN users_challenges
                        ON users_challenges.user_id = :uid AND users_challenges.challenge_id = challenges.challenge_id
                        WHERE challenges.name = :challenge AND challenges.group = :group";

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':challenge'=>$name, ':group'=>$group, ':uid'=>$this->app->user->uid));
                $challenge = $st->fetch();        

                if ($challenge) {
                    //Check if user has access
                    if (isset($challenge->challenge_before_uri) && strtolower($challenge->group) == 'main') {
                        $sql = 'SELECT IF(users_challenges.completed > 0, 1, 0) as `completed` FROM challenges
                                LEFT JOIN users_challenges
                                ON users_challenges.user_id = :uid AND users_challenges.challenge_id = challenges.challenge_id
                                WHERE `group` = :group AND challenges.challenge_id < :challenge_id
                                ORDER BY challenges.challenge_id DESC
                                LIMIT 1';
                        
                        $st = $this->app->db->prepare($sql);
                        $st->execute(array(':challenge_id'=>$challenge->challenge_id, ':group'=>$group, ':uid'=>$this->app->user->uid));
                        $previous = $st->fetch();

                        if (!$noSkip && (!$previous || !$previous->completed)) {
                            header("Location: $challenge->challenge_before_uri?skipped");
                            die();
                        }
                    }

                    $this->challengeView($challenge->challenge_id);
                } else {
                    return false;
                }

                // Build challenge data
                $sql = 'SELECT `key`, `value`, users.username
                        FROM challenges_data
                        LEFT JOIN users
                        ON challenges_data.value = users.user_id AND challenges_data.key = "author"
                        WHERE challenge_id = :lid';

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$challenge->challenge_id));
                $data = $st->fetchAll();

                $challenge->data = array();

                foreach($data as $d) {
                    //Find all non-value entries
                    foreach($d as $k=>$v) {
                        if ($v && $k !== 'key' && $k !== 'value')
                            $d->value = $v;
                    }

                    $challenge->data[$d->key] = $d->value;
                }

                if (isset($challenge->data['code']) && $challenge->data['code']) {
                    $challenge->data['code'] = json_decode($challenge->data['code']);
                }

                // Set page details
                $this->app->page->title = ucwords($challenge->title);

                // Get stats
                $sql = "SELECT COUNT(user_id) AS `completed` FROM users_challenges WHERE completed > 0 AND challenge_id = :lid AND user_id != 69";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$challenge->challenge_id));
                $result = $st->fetch();
                $challenge->count = $result->completed;

                // If challenge has uptime code, check challenge status
                if (isset($challenge->data['uptime']) && $challenge->data['uptime']) {
                    // Second cache used for high completion challenges, limits the online check from happening every time someone completes the challenge
                    $challenge->online = $this->app->cache->get('challenge_uptime_' . $challenge->data['uptime'], 5);

                    if (!$challenge->online) {
                        //$status = file_get_contents("https://api.uptimerobot.com/getMonitors?apiKey=".$challenge->data['uptime']."&format=json&noJsonCallback=1");
                        $status = file_get_contents("https://api.uptimerobot.com/getMonitors?apiKey=m782594776-810c12c1e53a35b261e25ad4&format=json&noJsonCallback=1");
                        
                        $status = json_decode($status);
                        $challenge->online = $status->monitors->monitor[0]->status == 2 ? 'online' : 'offline';

                        $this->app->cache->set('challenge_uptime_' . $challenge->data['uptime'], $challenge->online);
                    }
                }

                // Get last user to complete challenge
                $sql = "SELECT username, completed FROM users INNER JOIN (SELECT `user_id`, `completed` FROM users_challenges WHERE completed > 0 AND challenge_id = :lid AND user_id != 69 ORDER BY `completed` DESC LIMIT 1) `a` ON `a`.user_id = users.user_id;";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$challenge->challenge_id));
                $result = $st->fetch();
                $challenge->last_completed = $result->completed;
                $challenge->last_user = $result->username;

                // Get first user to complete challenge
                $sql = "SELECT username, completed FROM users INNER JOIN (SELECT `user_id`, `completed` FROM users_challenges WHERE completed > 0 AND challenge_id = :lid AND user_id != 69 ORDER BY `completed` ASC LIMIT 1) `a` ON `a`.user_id = users.user_id;";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$challenge->challenge_id));
                $result = $st->fetch();
                $challenge->first_completed = $result->completed;
                $challenge->first_user = $result->username;

                // Cache challenge data 
                $this->app->cache->set($cacheKey, serialize($challenge));

                return $challenge;

            endif; // End cache check
        }

        // Mark that the user has viewed a challenge
        function challengeView($challenge_id) {
            $st = $this->app->db->prepare('INSERT IGNORE INTO users_challenges (`user_id`, `challenge_id`) VALUES (:uid, :lid)');
            $st->execute(array(':lid'=> $challenge_id, ':uid' => $this->app->user->uid));
        }

        // Check if supplied answer is correct
        function check($challenge) {
            if (!isset($challenge->data['answer']))
                return false;

            $answers = json_decode($challenge->data['answer']);

            $attempted = false;
            $correct = false;
            $incorrect = 0;

            foreach($answers AS $answer) {
                $valid = false;

                if (strtolower($answer->method) == 'post') {
                    if (isset($_POST[$answer->name])) {
                        $attempted = true;
                        if (isset($answer->type) && $answer->type == 'regex') {
                            if (preg_match($answer->value, $_POST[$answer->name])) {
                                $valid = true;
                                if ($incorrect === 0) {
                                    $correct = true;
                                }
                            } else {
                                $correct = false;
                            }
                        } else if ($_POST[$answer->name] === $answer->value) {
                            $valid = true;
                            if ($incorrect === 0) {
                                $correct = true;
                            }
                        } else {
                            $correct = false;
                        }
                    } else {
                        $correct = false;
                    }
                } else if (strtolower($answer->method) == 'get') {
                    if (isset($_GET[$answer->name])) {
                        $attempted = true;
                        if (isset($answer->type) && $answer->type == 'regex') {
                            if (preg_match($answer->value, $_GET[$answer->name])) {
                                $valid = true;
                                if ($incorrect === 0) {
                                    $correct = true;
                                }
                            } else {
                                $correct = false;
                            }
                        } else if ($_GET[$answer->name] === $answer->value) {
                            $valid = true;
                            if ($incorrect === 0) {
                                $correct = true;
                            }
                        } else {
                            $correct = false;
                        }
                    } else {
                        $correct = false;
                    }
                }

                if (!$valid) {
                    $incorrect++;
                }
            }

            if ($attempted) {
                $challenge->attempt = $correct;
                $this->attempt($challenge, $correct);

                if ($challenge->challenge_id == 53) {
                    $challenge->errorMsg = (3 - $incorrect) . ' out of 3 answers correct';
                }
            }

            return $correct;
        }

        // Record attempt to complete challenge
        function attempt($challenge, $correct=false) {
            if (!$challenge->completed) {
                $challenge->attempts = $challenge->attempts + 1;
                $challenge->completed_time = 'now';
                if ($correct) {
                    $challenge->completed = true;
                    $challenge->count++;
                    $challenge->last_user = $this->app->user->username;
                    $challenge->last_completed = "now";
                   
                    //Update user score (temporary)
                    $this->app->user->score = $this->app->user->score + $challenge->data['reward'];
                    $st = $this->app->db->prepare('UPDATE users_challenges SET completed = NOW(), attempts=attempts+1 WHERE challenge_id = :lid AND user_id = :uid');
                    $st->execute(array(':lid'=> $challenge->challenge_id, ':uid' => $this->app->user->uid));

                    // Setup GA event
                    $this->app->ssga->set_event('challenge', 'completed', $challenge->challenge_id, $this->app->user->uid);
                    $this->app->ssga->send();

                    // Send feed thingy
                    $this->app->feed->call($this->app->user->username, 'challenge', ucwords($challenge->group.' '.$challenge->name), '/challenges/'.strtolower($challenge->group).'/'.strtolower($challenge->name));
                    
                    // Update WeChall
                    file_get_contents("http://wechall.net/remoteupdate.php?sitename=ht&username=".$this->app->user->username);
                } else {
                    // Record attempt
                    $st = $this->app->db->prepare('UPDATE users_challenges SET attempts=attempts+1 WHERE challenge_id = :lid AND user_id = :uid');
                    $st->execute(array(':lid'=> $challenge->challenge_id, ':uid' => $this->app->user->uid));
                }
            }
        }

        function user_data($challenge_id, $data=null) {
            if ($data !== null) {
                $st = $this->app->db->prepare('INSERT INTO users_challenges_data (`user_id`, `challenge_id`, `data`) VALUES (:uid, :lid, :data) ON DUPLICATE KEY UPDATE `data` = :data, `time` = now()');
                return $st->execute(array(':lid' => $challenge_id, ':uid' => $this->app->user->uid, ':data' => $data));
            } else {
                $st = $this->app->db->prepare('SELECT * FROM users_challenges_data WHERE `user_id` = :uid AND `challenge_id` = :lid');
                $st->execute(array(':lid' => $challenge_id, ':uid' => $this->app->user->uid));
                return $st->fetch();
            }
        }


        // ADMIN FUNCTIONS
        function addCategory($title) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            $st = $this->app->db->prepare('INSERT INTO challenges_groups (`title`) VALUES (:title)');
            return $st->execute(array(':title'=> $title));
        }

        function editChallenge($id, $new = false) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            $changes = array();

            if (!$new) {
                if (!$this->app->checkCSRFKey("challenge-editor", $_POST['token']))
                    return false;

                if (isset($_POST['category']) && strlen($_POST['category'])) {
                    $group = $_POST['category'];

                    $st = $this->app->db->prepare('UPDATE IGNORE challenges SET `group` = :g WHERE challenge_id = :id LIMIT 1');
                    $res = $st->execute(array(':id'=> $id, ':g'=>$group));
                }
            }

            if (isset($_POST['reward']) && is_numeric($_POST['reward'])) {
                $changes['reward'] = $_POST['reward'];
            }
            if (isset($_POST['uptime']) && strlen($_POST['uptime'])) {
                $changes['uptime'] = $_POST['uptime'];
            }
            if (isset($_POST['description']) && strlen($_POST['description'])) {
                $changes['description'] = $_POST['description'];
            }
            if (isset($_POST['hint']) && strlen($_POST['hint'])) {
                $changes['hint'] = $_POST['hint'];
            }
            if (isset($_POST['solution']) && strlen($_POST['solution'])) {
                $changes['solution'] = $_POST['solution'];
            }

            foreach($changes AS $change=>$value) {
                $st = $this->app->db->prepare('INSERT INTO challenges_data (`challenge_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
                $res = $st->execute(array(':id'=> $id, ':k'=>$change, ':v'=>$value));
                if (!$res)
                    return false;
            }

            return true;
        }

        function newChallenge() {
            if (!$this->app->user->admin_site_priv)
                return false;

            if (!$this->app->checkCSRFKey("challenge-editor", $_POST['token']))
                return false;

            // Create challenge
            try {
                $st = $this->app->db->prepare('INSERT INTO challenges (`name`, `group`) VALUES (:name, :group)');
                $status = $st->execute(array(':name'=> $_POST['name'], ':group' => $_POST['category']));
            } catch(PDOExecption $e) { 
                return false;
            }

            if (!$status)
                return false;

            $id = $this->app->db->lastInsertId(); 

            // Insert data
            $this->editChallenge($id, true);

            // Return challenge id
            return $id;
        }

        function editChallengeForm($id) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            if (!$this->app->checkCSRFKey("challenge-editor", $_POST['token'])) {
                return false;
            }

            $form = null;

            // Is it JSON?
            if (isset($_POST['form_method'])) {
                $form = array();
                $form['method'] = $_POST['form_method'];
                $form['fields'] = array();

                $f_types = $_POST['form_type'];
                $f_names = $_POST['form_name'];
                $f_labels = $_POST['form_label'];

                foreach($f_types as $key => $value) {
                    echo $value . "<br/>";
                    if ($f_names[$key] && $f_labels[$key]) {
                        $field = new stdClass;
                        $field->type = $value;
                        $field->name = $f_names[$key];
                        $field->label = $f_labels[$key];
                        
                        array_push($form['fields'],$field);
                    }
                }

                if (count($form['fields'])) {
                    $form = json_encode($form);
                }
            } else {
                if (isset($_POST['form'])) {
                    $form = $_POST['form'];
                }
            }

            if ($form) {
                $st = $this->app->db->prepare('INSERT INTO challenges_data (`challenge_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
                $status = $st->execute(array(':id'=> $id, ':k' => 'form', ':v' => $form));
            }

            // Do answers
            $answers = array();

            $a_methods = $_POST['answer_method'];
            $a_names = $_POST['answer_name'];
            $a_values = $_POST['answer_value'];

            foreach($a_methods as $key => $value) {
                if ($a_names[$key] && $a_values[$key]) {
                    $answer = new stdClass;
                    $answer->method = $value;
                    $answer->name = $a_names[$key];
                    $answer->value = $a_values[$key];
                    
                    array_push($answers, $answer);
                }
            }

            if (count($answers)) {
                $answers = json_encode($answers);       
                if ($answers) {
                    $st = $this->app->db->prepare('INSERT INTO challenges_data (`challenge_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
                    $status = $st->execute(array(':id'=> $id, ':k' => 'answer', ':v' => $answers));
                }
            }

            return true;
        }
    }
?>