<?php

namespace Allen;

class Twitter
{
    private $connection;

    public function __construct(TwitterConnection $request)
    {
        $this->connection = $request;
    }

    public function getCommonFollowers($user1, $user2)
    {
        $user1 = new TwitterUser($user1, $this->connection);
        $user2 = new TwitterUser($user2, $this->connection);
        $followers1 = $user1->getFollowers();
        $followers2 = $user2->getFollowers();

        return json_encode(["common_followers" => array_intersect($followers1, $followers2)]);
    }

    public function getUserKeywords($user, $keyword)
    {
        $user = new TwitterUser($user, $this->connection);
        $tweets = $user->getTweetsContaining($keyword);

        return json_encode(["tweets" => $tweets]);
    }
} 
