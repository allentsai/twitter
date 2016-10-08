<?php

namespace Allen;

class TwitterUser
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @var TwitterConnection
     */
    private $connection;

    public function __construct($userName, TwitterConnection $twitterRequest)
    {
        $this->userName = $userName;
        $this->connection = $twitterRequest;
    }

    public function getFollowers()
    {
        $users = [];
        $cursor = -1;
        do {
            $response = $this->connection->get("/followers/list.json?cursor=$cursor&screen_name=" . $this->userName);
            $contents = json_decode($response->getBody()->getContents(), true);
            var_dump("Fetched back a page, next page:" . $contents['next_cursor']);
            $cursor = $contents['next_cursor'];
            $newUsers = array_map(
                function($userArray) {
                    return $userArray["screen_name"];
                },
                $contents["users"]
            );
            $users = array_merge($users, $newUsers);
        } while ($cursor > 0);

        return $users;
    }

    public function getTweetsContaining($keyword)
    {
        $tweets = [];
        $count = 0;
        $tries = 0;
        $maxId = false;
//        $queryString = "$keyword from:" . $this->userName;
        do {
            // I can't get seearch working: https://twittercommunity.com/t/search-tweets-api-returned-empty-statuses-result-for-some-queries/12257/6
            // Seems like a problem a year ago...
//            $resource = "/search/tweets.json?q=" . urlencode($queryString);
            $resource = "/statuses/user_timeline.json?include_rts=false&count=200&screen_name=".$this->userName;
            var_dump($resource);
            if (!empty($maxId)) {
                $resource .= "&max_id=$maxId";
            }

            $hasNew = false;
            $response = $this->connection->get($resource);
//            var_dump($response->getBody()->getContents());
            $contents = json_decode($response->getBody()->getContents(), true);
            var_dump($contents);

            foreach ($contents as $status) {
                $maxId = max($maxId, $status['id']);
                if (strpos($status['text'], $keyword) !== false) {
                    $count += 1;
                    if (!isset($tweets[$status['id']])) {
                        $hasNew = true;
                    }
                    $tweets[$status['id']] = $status;
                }
            }
//            $maxId = $contents['search_metadata']['max_id'];
//            var_dump("Fetched back a page, next page:" . $contents['next_cursor']);
//            $cursor = $contents['next_cursor'];
//            $newUsers = array_map(
//                function($userArray) {
//                    return $userArray["screen_name"];
//                },
//                $contents["users"]
//            );
//            $users = array_merge($users, $newUsers);
//            $count++;
            $tries++;
        } while ($maxId > 0 && $hasNew);

        return $this->formatTweets($tweets);
    }

    private function formatTweets($tweets) {
        $results = [];
        $count = 0;
        foreach($tweets as $tweet) {
            var_dump($tweet);
            $count += 1;
            $results["Tweet$count"] = $tweet;
        }

        return $results;
    }
} 
