<?php

namespace AppBundle\Command;

use AppBundle\Entity\Tweet;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TweetsParseCommand
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class TweetsParseCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:tweets-parse')
            ->setDescription('Parse tweets from target account');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $parser = $container->get('app.service.twitter_parser');
        $tweetsService = $container->get('app.service.entity.tweet');

        $lastTweet = $container
            ->get('doctrine.orm.entity_manager')
            ->getRepository(Tweet::class)
            ->findLastByRemoteId();

        $sinceId = $lastTweet['remoteId'] ?? null;

        $tweetsData = $parser->getTweets($sinceId);

        $tweets = [];

        foreach ($tweetsData as $tweetData) {
            $tags = $tweetData['entities']['hashtags'] ?? [];
            $tagsTitles = [];

            foreach ($tags as $tag) {
                if (isset($tag['text'])) {
                    $tagsTitles[] = $tag['text'];
                }
            }

            $imgUrl = $tweetData['entities']['media'][0]['media_url'] ?? null;

            // Add links to content
            $content = preg_replace(
                '/(https?:\/\/[a-z\.]+\/[a-zA-Z0-9]+)/',
                '<a href="$1" target="_blank">$1</a>',
                $tweetData['text']
            );

            $tweets[] = [
                'remote_id' => (string)$tweetData['id'],
                'published_at' => $tweetData['created_at'],
                'content' => $content,
                'img_url' => $imgUrl,
                'tags' => $tagsTitles,
            ];
        }

        $tweetsService->create($tweets);
        $output->writeln('<fg=blue;bg=white>Tweets was successfully created!</>');
    }
}