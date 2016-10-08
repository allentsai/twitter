<?php
require_once(__DIR__ . '/vendor/autoload.php');
$myKey = 'UGApHjbUChHBsJB0LBuThOlNm';
$mySecret = 'X9xg5g1dMRDJe4r5eFyEtp0HGf6CybW2tRJSLc4U3KxKjNBaxT';

$string = $myKey . ":" . $mySecret;
$encoded  = base64_encode($string);

$method = $argv[1];
$arg1 = $argv[2];
$arg2 = $argv[3];

$client = new \GuzzleHttp\Client();
$auth = new \Allen\TwitterAuth($myKey, $mySecret, $client);
$connection = new \Allen\TwitterConnection($auth, $client);
$twitter = new \Allen\Twitter($connection);
$result = null;
if ($method == 'common-followers') {
    $result = $twitter->getCommonFollowers($argv[2], $argv[3]);
} elseif ($method == 'tweet-keyword') {
    $result = $twitter->getUserKeywords($argv[2], $argv[3]);
} else {
    var_dump("Unrecognized command $method, expected common-followers or tweet-keyword");
}
var_dump(count($result));
var_dump($result);
