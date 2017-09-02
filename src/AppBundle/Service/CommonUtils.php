<?php

namespace AppBundle\Service;

/**
 * Class CommonUtils
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class CommonUtils
{
    /**
     * Groups tweets by date
     *
     * @param array $tweets
     * @return array
     */
    public function groupTweets(array $tweets): array
    {
        $dates = [];

        for ($i = 0; $i < count($tweets); $i++) {
            $dayName = $this->getDayName($tweets[$i]->getPublishedAt());
            $dates[$dayName][] = $tweets[$i];
        }

        return $dates;
    }

    /**
     * Gets day name (for example, Today, Yesterday, 13 march etc)
     *
     * @param \DateTime $dateTime
     * @return string
     */
    private function getDayName(\DateTime $dateTime): string
    {
        $timestamp = $dateTime->getTimestamp();
        $dayNum = $dateTime->format('j');
        $monthName = strtolower($dateTime->format('F'));

        if ($timestamp >= strtotime('today')) {
            return 'Today';
        } elseif ($timestamp >= strtotime('yesterday')) {
            return "Yesterday, {$dayNum} {$monthName}";
        } else {
            return "{$dayNum} {$monthName}";
        }
    }
}