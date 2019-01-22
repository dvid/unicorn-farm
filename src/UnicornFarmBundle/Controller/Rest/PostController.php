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
     * PostController constructor.
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
     *      path="/post/create",
     *      tags={"post"},
     *      summary="Create a post.",
     *      description="Create a post",
     *      operationId="createPostAction",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *         name="firstName",
     *         required=true,
     *         in="query",
     *         description="The user first name",
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="lastName",
     *         required=true,
     *         in="query",
     *         description="The user last name",
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="text",
     *         required=true,
     *         in="query",
     *         description="The content of the post ...",
     *         type="string",
     *     ),
     *      @SWG\Parameter(
     *         name="unicornId",
     *         required=false,
     *         in="query",
     *         description="The unicorn id to link the post to",
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="Created",
     *          examples={
     *              "application/json":{
     *                  "id":1,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "unicorn":null,
     *                  "text":"content of the post ...",
     *                  "createdAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *                  "modifiedAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *              }
     *          }
     *     ),
     * )
     *
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
            Response::HTTP_CREATED,
            ['content-type' => 'application/json']);
    }

    /**
     * @SWG\Post(
     *      path="/post/link",
     *      tags={"post"},
     *      summary="Link an existing post to a unicorn.",
     *      description="Link an existing post to a unicorn",
     *      operationId="linkPostToUnicornAction",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *         name="postId",
     *         required=true,
     *         in="query",
     *         description="The id of the post",
     *         type="integer",
     *     ),
     *      @SWG\Parameter(
     *         name="unicornId",
     *         required=true,
     *         in="query",
     *         description="The id of the unicorn to link to",
     *         type="integer",
     *     ),
     *     @SWG\Response(
     *          response=202,
     *          description="Accepted",
     *          examples={
     *              "application/json":{
     *                  "id":1,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "unicorn":null,
     *                  "text":"content of the post ...",
     *                  "createdAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *                  "modifiedAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *              }
     *          }
     *     ),
     * )
     *
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
            Response::HTTP_ACCEPTED,
            ['content-type' => 'application/json']);
    }

    /**
     * @SWG\Post(
     *      path="/post/update",
     *      tags={"post"},
     *      summary="Update the content of an existing post.",
     *      description="Update the content of an existing post",
     *      operationId="updatePostAction",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *         name="postId",
     *         required=true,
     *         in="query",
     *         description="The id of the post",
     *         type="integer",
     *     ),
     *      @SWG\Parameter(
     *         name="content",
     *         required=true,
     *         in="query",
     *         description="The content of the post",
     *         type="string",
     *     ),
     *     @SWG\Response(
     *          response=202,
     *          description="Accepted",
     *          examples={
     *              "application/json":{
     *                  "id":1,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "unicorn":null,
     *                  "text":"content of the post ...",
     *                  "createdAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *                  "modifiedAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *              }
     *          }
     *     ),
     * )
     *
     * @Route(path="/post/update", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function updatePostAction(Request $request)
    {
        $post = $this->postService->getPost($request->query->get('postId'));
        $post = $post->setText($request->query->get('content'));
        $post = $this->postService->updatePost($post);

        return new Response(
            $this->serializer->serialize(
                $post,
                'json'
            ),
            Response::HTTP_ACCEPTED,
            ['content-type' => 'application/json']);
    }

    /**
     * @SWG\Get(
     *      path="/post/list",
     *      tags={"post"},
     *      summary="Get a list of all posts.",
     *      description="Get a list of all posts",
     *      operationId="listPostsAction",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="Success",
     *          examples={
     *              "application/json":{
     *              {
     *                  "id":1,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "unicorn":null,
     *                  "text":"content of the post ...",
     *                  "createdAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *                  "modifiedAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *              }
     *          }}
     *     ),
     * )
     *
     * @Route(path="/post/list", methods={"GET"})
     * @return Response
     */
    public function listPostsAction()
    {
        $posts = $this->serializer->serialize(
            $this->postService->getAllPosts(),
            'json'
        );

        return new Response(
            $posts,
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @SWG\Get(
     *      path="/post/user/list",
     *      tags={"post"},
     *      summary="Get a list of all posts for a user.",
     *      description="Get a list of all posts for a user",
     *      operationId="listUserPostsAction",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="Success",
     *          examples={
     *              "application/json":{
     *              {
     *                  "id":1,
     *                  "user": {
     *                      "id":1,
     *                      "firstName":"David",
     *                      "lastName":"",
     *                      "email":"",
     *                  },
     *                  "unicorn":null,
     *                  "text":"content of the post ...",
     *                  "createdAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *                  "modifiedAt": {
     *                      "timezone": {
     *                          "name":"Europe/Brussels",
     *                          "location": {
     *                              "country_code": "BE",
     *                              "latitude": 50.83333,
     *                              "longitude": 4.33333,
     *                              "comments": ""
     *                              },
     *                          },
     *                      "offset": 3600,
     *                      "timestamp": 1548204515
     *                  },
     *              }
     *          }}
     *     ),
     * )
     *
     * @Route(path="/post/user/list", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function listUserPostsAction(Request $request)
    {
        $posts = $this->serializer->serialize(
            $this->postService->getAllPostsFromUserId($request->query->get('userId')),
            'json'
        );

        return new Response(
            $posts,
            Response::HTTP_OK,
            ['content-type' => 'application/json']);
    }

    /**
     * @SWG\Delete(
     *      path="/post/delete",
     *      tags={"post"},
     *      summary="Delete a post.",
     *      description="Delete a post",
     *      operationId="deletePostAction",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=202,
     *          description="Accepted",
     *      )
     * )
     *
     * @Route(path="/post/delete", methods={"DELETE"})
     * @param Request $request
     * @return Response
     */
    public function deletePostAction(Request $request)
    {
        $this->postService->deletePost($request->query->get('id'));

        return new Response(
            'accepted',
            Response::HTTP_ACCEPTED);
    }
}
