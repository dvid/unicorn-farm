<?php

namespace UnicornFarmBundle\Service;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use UnicornFarmBundle\Entity\Post;
use UnicornFarmBundle\Entity\Unicorn;
use UnicornFarmBundle\Entity\User;
use UnicornFarmBundle\Repository\PostRepository;

final class PostService
{
    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $this->entityManager->getRepository(Post::class);
    }

    public function getPost($postId)
    {
        $post = $this->postRepository->findById($postId);
        if (!$post) {
            throw new EntityNotFoundException('Post with id does not exist!');
        }
        return $post;
    }

    public function getAllPosts()
    {
        return $this->postRepository->findAll();
    }

    public function getAllPostsFromUserId($userId)
    {
        return $this->postRepository->findAllByUserId($userId);
    }

    public function addPost(User $user, $text, Unicorn $unicorn = null)
    {
        $post = new Post();
        $post->setUser($user);
        $post->setText($text);
        $post->setUnicorn($unicorn);
        $this->postRepository->save($post);
        return $post;
    }

    public function linkPostToUnicorn(Post $post, Unicorn $unicorn)
    {
        $post = $this->postRepository->findById($post->getId());
        if (!$post) {
            throw new EntityNotFoundException('Post with id does not exist!');
        }

        $post->setUnicorn($unicorn);
        $this->postRepository->save($post);
        return $post;
    }

    public function updatePost($post)
    {
        $post = $this->postRepository->findById($post->getId());
        if (!$post) {
            throw new EntityNotFoundException('Post with id does not exist!');
        }

        $post->setUser($post->getUser());
        $post->setText($post->getText());
        $this->postRepository->save($post);
        return $post;
    }

    public function deletePost($postId)
    {
        $post = $this->postRepository->findById($postId);
        if ($post) {
            $this->postRepository->delete($post);
        }
    }

    public function deleteAllPostFromUserName($firstName, $lastName)
    {
        $user = $this->entityManager->getRepository(User::class)->findByUserName($firstName, $lastName);
        if (!$user) {
            throw new EntityNotFoundException('No user with this first and last name!');
        }

        $posts = $this->postRepository->findBy($user);
        if (!$posts) {
            throw new EntityNotFoundException('No Posts found for this user first and last name!');
        }

        foreach ($posts as $post){
            $this->postRepository->delete($post);
        }
    }
}