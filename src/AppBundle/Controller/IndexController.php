<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tag;
use AppBundle\Entity\Tweet;
use AppBundle\Form\SearchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 *
 * @author Andrey Antonov <apologboy@gmail.com>
 */
class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $page = (int)$request->query->get('p');

        $tags = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findPopular(
                Tag::POPULAR_PERIOD,
                Tag::POPULAR_LIMIT
            );
        $tweetsRepository = $this->getDoctrine()->getRepository(Tweet::class);

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchText = $form->getData()['text'];
            $tweetsPaginator = $tweetsRepository->findBySearch($searchText);
            $pagesCount = 1;
            $tweets = $tweetsPaginator->getQuery()->getResult();
        } else {
            $tweetsPaginator = $tweetsRepository->findAll($page);
            $pagesCount = ceil(count($tweetsPaginator) / Tweet::PAGE_LIMIT);
            $tweets = $tweetsPaginator->getQuery()->getResult();
        }

        $utils = $this->get('app.service.common_utils');

        return $this->render(
            'AppBundle:Index:index.html.twig',
            [
                'form' => $form->createView(),
                'groupedTweets' => $utils->groupTweets($tweets),
                'pagesCount' => $pagesCount,
                'tags' => $tags,
                'page' => $page,
                'searchText' => $searchText ?? null,
            ]
        );
    }

    /**
     * @Route("/{tagTitle}", name="tag")
     *
     * @param string $tagTitle
     * @return Response
     */
    public function tagAction(string $tagTitle): Response
    {
        $tweets = [];

        $tag = $this->getDoctrine()
            ->getRepository(Tag::class)
            ->findOneBy(['title' => $tagTitle]);
        if ($tag) {
            $tweets = $tag->getTweets()->toArray();
        }

        $utils = $this->get('app.service.common_utils');

        return $this->render(
            'AppBundle:Index:tag.html.twig',
            [
                'groupedTweets' => $utils->groupTweets($tweets),
                'tagTitle' => $tagTitle,
            ]
        );
    }
}
