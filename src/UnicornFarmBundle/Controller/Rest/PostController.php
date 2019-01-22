<?php

namespace UnicornFarmBundle\Controller\Rest;

use Swagger\Annotations as SWG;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UnicornFarmBundle\Entity\Post;
use UnicornFarmBundle\Service\PostService;
use UnicornFarmBundle\Service\UnicornService;
use UnicornFarmBundle\Service\UserService;

class PostController extends Controller
{
    /**
     * @var UnicornService
     */
    private $unicornService;

    /**
     * @var PostService
     */
    private $postService;

    /**
     * @var UserService
     */
    private $userService;

    /**
     * @var serializer
     */
    private $serializer;

    /**
     * UnicornController constructor.
     * @param UnicornService $unicornService
     * @param PostService $postService
     * @param UserService $userService
     */
    public function __construct(UnicornService $unicornService, PostService $postService, UserService $userService)
    {
        $this->unicornService = $unicornService;
        $this->postService = $postService;
        $this->userService = $userService;

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($object) { return $object->getId(); });
        $this->serializer = new Serializer([$normalizer], ['json' => new JsonEncoder()]);
    }

    /**
    * @Route(path="/post/create", methods={"POST"})
    * @param Request $request
    * @return Response
    */
        public function createPostAction(Request $request)
    {
        $user  = $this->userService->getUserByName(
            $request->query->get('firstName'),
            $request->query->get('lastName'));
        $text = $request->query->get('text');

        $newPost = $this->postService->addPost($user, $text);

        $unicorns = $this->serializer->serialize(
            $newPost,
            'json'
        );

        return new Response(
            $unicorns,
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }
}
