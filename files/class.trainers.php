<?php
    class trainers {
        private $app;

        public function __construct($app) {
            $this->app = $app;
        }

        public function go($q) {
            //echo "q: ".$q."<br/>";
            $q = trim($q);
            $this->lastSearch = $q;

            //$articles = $this->searchArticles($q);
            //$forum = $this->searchForum($q);
            $users = $this->searchTrainers($q);
            return array('articles'=>$articles, 'forum'=>$forum, 'users'=>$users);
        }


        private function searchTrainers($latitude,$longitude) {
            if ($latitude==null || $longitude==null)
                return false;

            $like = $this->app->utils->escape_like($term, '|');
            $like .= '%';

            $sql = 'SELECT username, users.score, profile.gravatar, IF (profile.gravatar = 1, users.email , profile.img) as `image`, users_friends.status
                    FROM users
                    LEFT JOIN users_profile as profile
                    ON users.user_id = profile.user_id
                    LEFT JOIN users_friends
                    ON (users_friends.user_id = users.user_id AND users_friends.friend_id = :uid) OR (users_friends.user_id = :uid AND users_friends.friend_id = users.user_id)
                    WHERE username LIKE :like ESCAPE \'|\' OR (email = :term AND profile.show_email = 1)
                    ORDER BY username ASC
                    LIMIT 8';

            //echo "sql: ".$sql."<br/>";
            $st = $this->app->db->prepare($sql);
            $st->execute(array(':like' => $like, ':term' => $term, ':uid' => $this->app->user->uid));
            $result = $st->fetchAll();

            if (!count($result))
                return false;

            foreach($result as $res) {
                if (isset($res->image)) {
                    $gravatar = isset($res->gravatar) && $res->gravatar == 1;
                    $res->image = profile::getImg($res->image, 48, $gravatar);
                } else
                    $res->image = profile::getImg(null, 48);
            }

            return $result;
        }

        public function getLastSearchTerm() {
            $result = preg_replace_callback('/(?<!")\b\w+\b|(?<=")\b[^"]+/', array($this, 'lastSearchTermReplace'), $this->lastSearch);

            return $result;
        }

        private function lastSearchTermReplace($matches) {
            $term = $matches[0];

            if (strlen($term) <= 2 || in_array($term, $this->disallowedWords))
                return "<s>$term</s>";
            else
                return $term;
        }
    }
    
    //$search = new search();
    //print_r($search->go("abhi1302"));
    
?>