<?php

namespace UnicornFarmBundle\Controller\Rest;

use Doctrine\Common\Collections\ArrayCollection;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UnicornFarmBundle\Entity\Post;
use UnicornFarmBundle\Entity\Unicorn;
use UnicornFarmBundle\Entity\User;
use UnicornFarmBundle\Service\PostService;
use UnicornFarmBundle\Service\UnicornService;
use UnicornFarmBundle\Service\UserService;

class UnicornController extends Controller
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
     * @SWG\Info(
     *      title="The Unicorns farm API",
     *      version="3.0.0",
     *      description="This is a unicorn farm.
     *                  No authorization is required to use this API.
     *                  This documentation was generated by swagger. You can find out more about Swagger at [http://swagger.io](http://swagger.io) or on [irc.freenode.net, #swagger](http://swagger.io/irc/).",
     *      @SWG\Contact(
     *          email="support@example.com"
     *      )
     * )
     */

    /**
     * @SWG\Tag(
     *      name="unicorn",
     *      description="Everything about unicorns"
     * )
     * @SWG\Tag(
     *      name="post",
     *      description="Operations about posts"
     * )
     * @SWG\Tag(
     *      name="store",
     *      description="Operations in the store"
     * )
     */

    /**
     * @SWG\Get(
     *      path="/list",
     *      tags={"unicorn"},
     *      summary="Get a list of all unicorns",
     *      description="Get a list of all the unicorns in the farm",
     *      operationId="listAction",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="Success",
     *          examples={
     *              "application/json":{
     *                  {
     *                      "id":1,
     *                      "name":"pinky",
     *                      "description":"pink fury",
     *                      "available":false,
     *                      "user": {
     *                          "id":1,
     *                          "firstName":"David",
     *                          "lastName":"",
     *                          "email":"",
     *                      },
     *                      "posts": {},
     *                  },{
     *                      "id":2,
     *                      "name":"blacky",
     *                      "description":"black fury",
     *                      "available":true,
     *                      "user":null,
     *                      "posts": {},
     *                  },
     *              }
     *          }
     *      )
     * )
     *
     * @Route(path="/list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $unicorns = $this->serializer->serialize(
            $this->unicornService->getAllUnicorns(),
            'json'
        );

        return new Response(
            $unicorns,
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @Route(path="/test", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function testAction(Request $request)
    {
        $newUser = $this->userService->addUser("John", "Doe", "jd@mail.com");
        $newUnicorn = $this->unicornService->addUnicorn("Blanko33");
        $newPost = $this->postService->addPost($newUser, "test text");

        $unicorns = $this->serializer->serialize(
            $this->unicornService->getAllUnicorns(),
            'json'
        );
        return new Response($unicorns);
    }
}
