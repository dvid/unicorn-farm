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
        $user = $this->userService->getUserByName(
            $request->query->get('firstName'),
            $request->query->get('lastName')
        );

        $unicorn = null;
        if ($request->query->get('unicornId')){
            $unicorn = $this->unicornService->getUnicorn($request->query->get('unicornId'));
        }

        $newPost = $this->postService->addPost($user, $request->query->get('text'), $unicorn);

        return new Response(
            $this->serializer->serialize(
                $newPost,
                'json'
            ),
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @Route(path="/post/link", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function linkPostToUnicornAction(Request $request)
    {
        $post = $this->postService->getPost($request->query->get('postId'));
        $unicorn = $this->unicornService->getUnicorn($request->query->get('unicornId'));
        $post = $this->postService->linkPostToUnicorn($post, $unicorn);
        $post = $this->postService->updatePost($post);

        return new Response(
            $this->serializer->serialize(
                $post,
                'json'
            ),
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }
}
