<?php

namespace AppBundle\Service\Entity;

use AppBundle\Entity\Tag;
use AppBundle\Entity\Tweet;
use AppBundle\Traits\SetEntityManagerTrait;

/**
 * Class TweetService
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class TweetService
{
    use SetEntityManagerTrait;

    /**
     * Creates a tweets
     *
     * @param array $tweetsData
     * @return array
     */
    public function create(array $tweetsData): array
    {
        $result = [];
        $tweetRepository = $this->entityManager->getRepository(Tweet::class);
        $tagRepository = $this->entityManager->getRepository(Tag::class);

        foreach ($tweetsData as $tweetData) {
            if ($tweetRepository->findOneBy(['remoteId' => $tweetData['remote_id']])) {
                continue;
            }
            $tweet = (new Tweet())
                ->setRemoteId($tweetData['remote_id'])
                ->setPublishedAt(new \DateTime($tweetData['published_at']))
                ->setContent($tweetData['content'])
                ->setImgUrl($tweetData['img_url']);

            foreach ($tweetData['tags'] as $title) {
                if (!$tag = $tagRepository->findOneBy(['title' => $title])){
                    $tag = (new Tag())->setTitle($title);
                    $this->entityManager->persist($tag);
                    $this->entityManager->flush();
                }
                $tweet->addTag($tag);
            }
            $this->entityManager->persist($tweet);
            $result[] = $tweet;
        }
        $this->entityManager->flush();

        return $result;
    }
}