<?php

namespace UnicornFarmBundle\Controller\Rest;

use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use UnicornFarmBundle\Service\PostService;
use UnicornFarmBundle\Service\UnicornService;
use UnicornFarmBundle\Service\UserService;

class StoreController extends Controller
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
     * StoreController constructor.
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
     * @SWG\Post(
     *      path="/store/buy",
     *      tags={"store"},
     *      summary="Buy a unicorn.",
     *      description="Buy a unicorn",
     *      operationId="buyUnicornAction",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *         name="unicornId",
     *         required=true,
     *         in="query",
     *         description="The unicorn id",
     *         type="integer",
     *     ),
     *      @SWG\Parameter(
     *         name="userId",
     *         required=true,
     *         in="query",
     *         description="The user id",
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *          response=202,
     *          description="Accepted",
     *          examples={
     *              "application/json":{
     *                  "id":1,
     *                  "name":"pinky",
     *                  "description":"pink fury",
     *                  "available":false,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "posts": {},
     *              }
     *          }
     *     ),
     * )
     *
     * @Route(path="/store/buy", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function buyUnicornAction(Request $request)
    {
        $unicorn = $this->serializer->serialize(
            $this->unicornService->buyUnicorn($request->query->get('unicornId'), $request->query->get('userId')),
            'json'
        );

        return new Response(
            $unicorn,
            Response::HTTP_ACCEPTED,
            ['content-type' => 'application/json']);
    }
}
