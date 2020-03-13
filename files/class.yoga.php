<?php
    class yoga {
        private $app;
        private $list;
        private $yoga;

        public function __construct($app) {
            $this->app = $app;
        }

        public function getList($uid=null, $category=null) {
            // Check cache
            $yoga = json_decode($this->app->cache->get('yoga_list', 10));

            if (!$yoga) {
                $sql = 'SELECT yoga.yoga_id AS `id`, CONCAT(yoga_groups.title, " Level ", yoga.name) as `title`, yoga.name, yoga_groups.title as `group`,
                LOWER(CONCAT("/yoga/", CONCAT_WS("/", yoga_groups.title, yoga.name))) as `uri`, yoga_completed.completed as `total_completed`,
                yoga_data.value AS `reward`
                FROM yoga
                INNER JOIN yoga_groups
                ON yoga_groups.title = yoga.group
                LEFT JOIN yoga_data
                ON yoga_data.yoga_id = yoga.yoga_id AND yoga_data.key = "reward"
                LEFT JOIN (SELECT COUNT(user_id) AS `completed`, yoga_id FROM users_yoga WHERE completed > 0 AND user_id != 69 GROUP BY yoga_id) `yoga_completed`
                ON yoga_completed.yoga_id = yoga.yoga_id 
                ORDER BY yoga_groups.order ASC, yoga.yoga_id ASC';

                $st = $this->app->db->prepare($sql);
                $st->execute();
                $yoga = $st->fetchAll();

                $this->app->cache->set('yoga_list', json_encode($yoga));
            }

            // Get list of completed yoga
            $sql = 'SELECT yoga_id, IF(users_yoga.completed > 0, 2, 1) as `completed` FROM users_yoga WHERE user_id = :uid';
            $st = $this->app->db->prepare($sql);
            $st->bindValue(':uid', $uid?$uid:$this->app->user->uid);
            $st->execute();
            $user_yoga = $st->fetchAll();
            
            // Create list
            $list = array();
            foreach ($yoga AS &$yoga) {
                //echo "category: $category <br/>";
                //echo "group: $yoga->group <br/>";
                // Check filter
                if ($category && trim(strtolower(str_replace('+', '', $yoga->group))) != trim($category)) {
                    continue;
                }

                // Assign progress based on $users_yoga
                $yoga->progress = 0;
                foreach ($user_yoga AS $l) {
                    if ($l->yoga_id == $yoga->id) {
                        $yoga->progress = $l->completed;
                        break;
                    }
                }

                if (!array_key_exists($yoga->group, $list)) {
                    $list[$yoga->group] = new stdClass();
                    $list[$yoga->group]->yoga = array();
                }
                array_push($list[$yoga->group]->yoga, $yoga);
            }

            return $list;
        }

        public function getGroups() {
            $st = $this->app->db->prepare('SELECT title FROM yoga_groups ORDER BY `order` ASC, `title` ASC');
            $st->execute();
            return $st->fetchAll();       
        }

        public function getLevelFromID($id) {
            $st = $this->app->db->prepare('SELECT `group`, `name` FROM yoga WHERE yoga_id = :id LIMIT 1');
            $st->bindValue(':id', $id);
            $st->execute();
            $res = $st->fetch();

            if ($res) {
                return $this->getLevel($res->group, $res->name, true);
            } else{
                return false;
            }
        }

        public function getLevel($group, $name, $noSkip=false) {
            // Check cache for yoga data
            $cacheKey = 'yoga_data_' . $group . '_' . $name;
            $cache = $this->app->cache->get($cacheKey, 5);

            if ($cache):
                $yoga = unserialize($cache);

                $sql = "SELECT
                            IF(users_yoga.completed > 0, 1, 0) as `completed`,
                            users_yoga.completed as `completed_time`,
                            users_yoga.started,
                            IFNULL(users_yoga.attempts, 0) as `attempts`
                        FROM users_yoga
                        WHERE yoga_id = :yogaid AND user_id = :uid";

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':yogaid' => $yoga->yoga_id, ':uid' => $this->app->user->uid));
                $tmpLevel = $st->fetch();

                // Merge users stats into cache response
                $yoga = (object) array_merge((array) $yoga, (array) $tmpLevel);

                //Check if user has access
                if (isset($yoga->yoga_before_uri) && strtolower($yoga->group) == 'main') {
                    $sql = 'SELECT IF(users_yoga.completed > 0, 1, 0) as `completed` FROM yoga
                            LEFT JOIN users_yoga
                            ON users_yoga.user_id = :uid AND users_yoga.yoga_id = yoga.yoga_id
                            WHERE `group` = :group AND yoga.yoga_id < :yoga_id
                            ORDER BY yoga.yoga_id DESC
                            LIMIT 1';
                    
                    $st = $this->app->db->prepare($sql);
                    $st->execute(array(':yoga_id'=>$yoga->yoga_id, ':group'=>$group, ':uid'=>$this->app->user->uid));
                    $previous = $st->fetch();

                    if (!$noSkip && (!$previous || !$previous->completed)) {
                        header("Location: $yoga->yoga_before_uri?skipped");
                        die();
                    }
                }

                $this->yogaView($yoga->yoga_id);

                return $yoga;

            else:
                $before_after_sql = 'SELECT `yoga_id`, `name`, LOWER(CONCAT("/yoga/", CONCAT_WS("/", yoga_groups.title, yoga.name))) as `uri`
                                     FROM yoga
                                     INNER JOIN yoga_groups
                                     ON yoga_groups.title = yoga.group
                                     WHERE `group` = :group
                                     ORDER BY yoga_id';

                $sql = "SELECT yoga.yoga_id, yoga.name, yoga_groups.title AS `group`, CONCAT(`group`, ' Level ', yoga.name) as `title`,
                            IF(users_yoga.completed > 0, 1, 0) as `completed`, users_yoga.completed as `completed_time`, `started`,
                            IFNULL(users_yoga.attempts, 0) as `attempts`,
                            yoga_before.uri AS `yoga_before_uri`, yoga_after.uri AS `yoga_after_uri`
                        FROM yoga
                        INNER JOIN yoga_groups
                        ON yoga_groups.title = yoga.group
                        LEFT JOIN ({$before_after_sql} DESC) yoga_before
                        ON yoga_before.yoga_id < yoga.yoga_id
                        LEFT JOIN ({$before_after_sql} ASC) yoga_after
                        ON yoga_after.yoga_id > yoga.yoga_id
                        LEFT JOIN users_yoga
                        ON users_yoga.user_id = :uid AND users_yoga.yoga_id = yoga.yoga_id
                        WHERE yoga.name = :yoga AND yoga.group = :group";

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':yoga'=>$name, ':group'=>$group, ':uid'=>$this->app->user->uid));
                $yoga = $st->fetch();        

                if ($yoga) {
                    //Check if user has access
                    if (isset($yoga->yoga_before_uri) && strtolower($yoga->group) == 'main') {
                        $sql = 'SELECT IF(users_yoga.completed > 0, 1, 0) as `completed` FROM yoga
                                LEFT JOIN users_yoga
                                ON users_yoga.user_id = :uid AND users_yoga.yoga_id = yoga.yoga_id
                                WHERE `group` = :group AND yoga.yoga_id < :yoga_id
                                ORDER BY yoga.yoga_id DESC
                                LIMIT 1';
                        
                        $st = $this->app->db->prepare($sql);
                        $st->execute(array(':yoga_id'=>$yoga->yoga_id, ':group'=>$group, ':uid'=>$this->app->user->uid));
                        $previous = $st->fetch();

                        if (!$noSkip && (!$previous || !$previous->completed)) {
                            header("Location: $yoga->yoga_before_uri?skipped");
                            die();
                        }
                    }

                    $this->yogaView($yoga->yoga_id);
                } else {
                    return false;
                }

                // Build yoga data
                $sql = 'SELECT `key`, `value`, users.username
                        FROM yoga_data
                        LEFT JOIN users
                        ON yoga_data.value = users.user_id AND yoga_data.key = "author"
                        WHERE yoga_id = :lid';

                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$yoga->yoga_id));
                $data = $st->fetchAll();

                $yoga->data = array();

                foreach($data as $d) {
                    //Find all non-value entries
                    foreach($d as $k=>$v) {
                        if ($v && $k !== 'key' && $k !== 'value')
                            $d->value = $v;
                    }

                    $yoga->data[$d->key] = $d->value;
                }

                if (isset($yoga->data['code']) && $yoga->data['code']) {
                    $yoga->data['code'] = json_decode($yoga->data['code']);
                }

                // Set page details
                $this->app->page->title = ucwords($yoga->title);

                // Get stats
                $sql = "SELECT COUNT(user_id) AS `completed` FROM users_yoga WHERE completed > 0 AND yoga_id = :lid AND user_id != 69";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$yoga->yoga_id));
                $result = $st->fetch();
                $yoga->count = $result->completed;

                // If yoga has uptime code, check yoga status
                if (isset($yoga->data['uptime']) && $yoga->data['uptime']) {
                    // Second cache used for high completion yoga, limits the online check from happening every time someone completes the yoga
                    $yoga->online = $this->app->cache->get('yoga_uptime_' . $yoga->data['uptime'], 5);

                    if (!$yoga->online) {
                        $status = file_get_contents("https://api.uptimerobot.com/getMonitors?apiKey=".$yoga->data['uptime']."&format=json&noJsonCallback=1");
                        $status = json_decode($status);
                        $yoga->online = $status->monitors->monitor[0]->status == 2 ? 'online' : 'offline';

                        $this->app->cache->set('yoga_uptime_' . $yoga->data['uptime'], $yoga->online);
                    }
                }

                // Get last user to complete yoga
                $sql = "SELECT username, completed FROM users INNER JOIN (SELECT `user_id`, `completed` FROM users_yoga WHERE completed > 0 AND yoga_id = :lid AND user_id != 69 ORDER BY `completed` DESC LIMIT 1) `a` ON `a`.user_id = users.user_id;";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$yoga->yoga_id));
                $result = $st->fetch();
                $yoga->last_completed = $result->completed;
                $yoga->last_user = $result->username;

                // Get first user to complete yoga
                $sql = "SELECT username, completed FROM users INNER JOIN (SELECT `user_id`, `completed` FROM users_yoga WHERE completed > 0 AND yoga_id = :lid AND user_id != 69 ORDER BY `completed` ASC LIMIT 1) `a` ON `a`.user_id = users.user_id;";
                $st = $this->app->db->prepare($sql);
                $st->execute(array(':lid'=>$yoga->yoga_id));
                $result = $st->fetch();
                $yoga->first_completed = $result->completed;
                $yoga->first_user = $result->username;

                // Cache yoga data 
                $this->app->cache->set($cacheKey, serialize($yoga));

                return $yoga;

            endif; // End cache check
        }

        // Mark that the user has viewed a yoga
        function yogaView($yoga_id) {
            $st = $this->app->db->prepare('INSERT IGNORE INTO users_yoga (`user_id`, `yoga_id`) VALUES (:uid, :lid)');
            $st->execute(array(':lid'=> $yoga_id, ':uid' => $this->app->user->uid));
        }

        // Check if supplied answer is correct
        function check($yoga) {
            if (!isset($yoga->data['answer']))
                return false;

            $answers = json_decode($yoga->data['answer']);

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
                $yoga->attempt = $correct;
                $this->attempt($yoga, $correct);

                if ($yoga->yoga_id == 53) {
                    $yoga->errorMsg = (3 - $incorrect) . ' out of 3 answers correct';
                }
            }

            return $correct;
        }

        // Record attempt to complete yoga
        function attempt($yoga, $correct=false) {
            if (!$yoga->completed) {
                $yoga->attempts = $yoga->attempts + 1;
                $yoga->completed_time = 'now';
                if ($correct) {
                    $yoga->completed = true;
                    $yoga->count++;
                    $yoga->last_user = $this->app->user->username;
                    $yoga->last_completed = "now";
                   
                    //Update user score (temporary)
                    $this->app->user->score = $this->app->user->score + $yoga->data['reward'];
                    $st = $this->app->db->prepare('UPDATE users_yoga SET completed = NOW(), attempts=attempts+1 WHERE yoga_id = :lid AND user_id = :uid');
                    $st->execute(array(':lid'=> $yoga->yoga_id, ':uid' => $this->app->user->uid));

                    // Setup GA event
                    $this->app->ssga->set_event('yoga', 'completed', $yoga->yoga_id, $this->app->user->uid);
                    $this->app->ssga->send();

                    // Send feed thingy
                    $this->app->feed->call($this->app->user->username, 'yoga', ucwords($yoga->group.' '.$yoga->name), '/yoga/'.strtolower($yoga->group).'/'.strtolower($yoga->name));
                    
                    // Update WeChall
                    file_get_contents("http://wechall.net/remoteupdate.php?sitename=ht&username=".$this->app->user->username);
                } else {
                    // Record attempt
                    $st = $this->app->db->prepare('UPDATE users_yoga SET attempts=attempts+1 WHERE yoga_id = :lid AND user_id = :uid');
                    $st->execute(array(':lid'=> $yoga->yoga_id, ':uid' => $this->app->user->uid));
                }
            }
        }

        function user_data($yoga_id, $data=null) {
            if ($data !== null) {
                $st = $this->app->db->prepare('INSERT INTO users_yoga_data (`user_id`, `yoga_id`, `data`) VALUES (:uid, :lid, :data) ON DUPLICATE KEY UPDATE `data` = :data, `time` = now()');
                return $st->execute(array(':lid' => $yoga_id, ':uid' => $this->app->user->uid, ':data' => $data));
            } else {
                $st = $this->app->db->prepare('SELECT * FROM users_yoga_data WHERE `user_id` = :uid AND `yoga_id` = :lid');
                $st->execute(array(':lid' => $yoga_id, ':uid' => $this->app->user->uid));
                return $st->fetch();
            }
        }


        // ADMIN FUNCTIONS
        function addCategory($title) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            $st = $this->app->db->prepare('INSERT INTO yoga_groups (`title`) VALUES (:title)');
            return $st->execute(array(':title'=> $title));
        }

        function editLevel($id, $new = false) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            $changes = array();

            if (!$new) {
                if (!$this->app->checkCSRFKey("yoga-editor", $_POST['token']))
                    return false;

                if (isset($_POST['category']) && strlen($_POST['category'])) {
                    $group = $_POST['category'];

                    $st = $this->app->db->prepare('UPDATE IGNORE yoga SET `group` = :g WHERE yoga_id = :id LIMIT 1');
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
                $st = $this->app->db->prepare('INSERT INTO yoga_data (`yoga_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
                $res = $st->execute(array(':id'=> $id, ':k'=>$change, ':v'=>$value));
                if (!$res)
                    return false;
            }

            return true;
        }

        function newLevel() {
            if (!$this->app->user->admin_site_priv)
                return false;

            if (!$this->app->checkCSRFKey("yoga-editor", $_POST['token']))
                return false;

            // Create yoga
            try {
                $st = $this->app->db->prepare('INSERT INTO yoga (`name`, `group`) VALUES (:name, :group)');
                $status = $st->execute(array(':name'=> $_POST['name'], ':group' => $_POST['category']));
            } catch(PDOExecption $e) { 
                return false;
            }

            if (!$status)
                return false;

            $id = $this->app->db->lastInsertId(); 

            // Insert data
            $this->editLevel($id, true);

            // Return yoga id
            return $id;
        }

        function editLevelForm($id) {
            if (!$this->app->user->admin_site_priv) {
                return false;
            }

            if (!$this->app->checkCSRFKey("yoga-editor", $_POST['token'])) {
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
                $st = $this->app->db->prepare('INSERT INTO yoga_data (`yoga_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
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
                    $st = $this->app->db->prepare('INSERT INTO yoga_data (`yoga_id`, `key`, `value`) VALUES (:id, :k, :v) ON DUPLICATE KEY UPDATE `value` = :v');
                    $status = $st->execute(array(':id'=> $id, ':k' => 'answer', ':v' => $answers));
                }
            }

            return true;
        }
    }
?>
