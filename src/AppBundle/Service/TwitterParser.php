<?php

namespace AppBundle\Service;

/**
 * Class TwitterParser
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class TwitterParser
{
    /**
     * @var \TwitterAPIExchange
     */
    private $twitterApi;

    /**
     * @var array
     */
    private $apiUrls;

    /**
     * @var string
     */
    private $targetAccount;

    /**
     * @var int
     */
    private $tweetsMaxCount;

    /**
     * TwitterParser constructor.
     *
     * @param array $credentials
     * @param $apiUrls
     * @param string $targetAccount
     * @param int $tweetsMaxCount
     */
    public function __construct(
        array $credentials,
        array $apiUrls,
        string $targetAccount,
        int $tweetsMaxCount)
    {
        $this->twitterApi = new \TwitterAPIExchange([
            'oauth_access_token' => $credentials['access_token'],
            'oauth_access_token_secret' => $credentials['access_token_secret'],
            'consumer_key' => $credentials['consumer_key'],
            'consumer_secret' => $credentials['consumer_secret'],
        ]);
        $this->apiUrls = $apiUrls;
        $this->targetAccount = $targetAccount;
        $this->tweetsMaxCount = $tweetsMaxCount;
    }

    /**
     * Get tweets from target account
     *
     * @param string|null $sinceId
     * @return array
     */
    public function getTweets(string $sinceId = null): array
    {
        $url = $this->apiUrls['get_user_tweets'];
        $getField  = '?screen_name=' . $this->targetAccount;
        $getField .= '&exclude_replies=true';
        if ($sinceId) {
            $getField .= '&since_id=' . $sinceId;
        } else {
            $getField .= '&count=' . $this->tweetsMaxCount;
        }

        $requestMethod = 'GET';

        $tweets = $this->twitterApi
            ->setGetfield($getField)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return json_decode($tweets, true);
    }
}