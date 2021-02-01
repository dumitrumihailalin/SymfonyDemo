<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use GuzzleHttp\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Blog\Post;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
//use Symfony\Contracts\HttpClient\HttpClientInterface;


class ArticlesController extends Controller
{
    protected $batchSize = 20;
    /**
     * @Route("/getArticles", name="getArticles")
     */
    public function getArticles()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://jsonplaceholder.typicode.com/posts');

        $posts = [];
        if( $response->getStatusCode() == 200 && $response->getReasonPhrase() == 'OK') {
            $contentType = $response->getBody();
            $body = $contentType->getContents();
            $posts = \GuzzleHttp\json_decode($body);
            $entityManager = $this->getDoctrine()->getManager();
            for ($i = 0; $i < count($posts); ++$i) {
                $post = new Post();

                $post->setTitle($posts[$i]->title);
                $post->setDescription($posts[$i]->body);
                $post->setAuthor($posts[$i]->userId);
                $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $posts[$i]->title)));
                $post->setSlug($slug);
                $entityManager->persist($post);
                if (($i % $this->batchSize) === 0) {
                    $entityManager->flush();
                    $entityManager->clear(); // Detaches all objects from Doctrine!
                }
            }
            $entityManager->flush(); //Persist objects that did not make up an entire batch
            $entityManager->clear();

            return new Response('Saved new post');
        } else {
            $posts = [];
            return new Response('Posts found '. sizeof($posts));
        }

    }

    /**
     * @Route("/getComments", name="getComments")
     */
    public function getComments()
    {
        // connectiin with guzzle
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://jsonplaceholder.typicode.com/comments');

        $comments = [];
        // try to check the connection has a good status of 200 of successfll
        if( $response->getStatusCode() == 200 && $response->getReasonPhrase() == 'OK') {

            $contentType = $response->getBody();
            $body = $contentType->getContents();
            $comments = \GuzzleHttp\json_decode($body);
            $entityManager = $this->getDoctrine()->getManager();

            for ($i = 0; $i < count($comments); ++$i) {

                $comment = new Comment();
                $comment->setPostId($comments[$i]->postId);
                $comment->setBody($comments[$i]->body);
                $comment->setEmail($comments[$i]->email);
                $comment->setName($comments[$i]->name);

                $entityManager->persist($comment);
                if (($i % $this->batchSize) === 0) {
                    $entityManager->flush();
                    $entityManager->clear(); // Detaches all objects from Doctrine!
                }
            }
            $entityManager->flush(); //Persist objects that did not make up an entire batch
            $entityManager->clear();

            return new Response('Saved new comment');
        } else {
            $posts = [];
            return new Response('Comments found '. sizeof($posts));
        }

    }


    /**
     * @Route("/getUsers", name="getUsers")
     */
    public function getUsers()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', 'http://jsonplaceholder.typicode.com/users');

        $users = [];
        if( $response->getStatusCode() == 200 && $response->getReasonPhrase() == 'OK') {
            $contentType = $response->getBody();
            $body = $contentType->getContents();
            $users = \GuzzleHttp\json_decode($body);
            $entityManager = $this->getDoctrine()->getManager();
            for ($i = 0; $i < count($users); ++$i) {
                $user = new User();

                $user->setName($users[$i]->name);
                $user->setUsername($users[$i]->username);
                $user->setEmail($users[$i]->email);

                $entityManager->persist($user);
                if (($i % $this->batchSize) === 0) {
                    $entityManager->flush();
                    $entityManager->clear(); // Detaches all objects from Doctrine!
                }
            }
            $entityManager->flush(); //Persist objects that did not make up an entire batch
            $entityManager->clear();

            return new Response('Saved new user');
        } else {
            $posts = [];
            return new Response('User found '. sizeof($posts));
        }

    }
    /**
     * @Route("/show", name="show")
     */
    public function showPosts()
    {
        $data = [];
        $posts =  $this->getDoctrine()->getRepository('AppBundle:Blog\Post')->findAll();
        $data['posts'] = $posts;
        return $this->render("posts/index.html.twig", $data);
    }
}