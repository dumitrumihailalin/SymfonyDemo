<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog\Post;
use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\DBAL\Driver\Connection;

class BlogController extends AbstractController
{

    protected $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @Route("/post/{slug}", name="show")
     */
    public function show($slug)
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['slug' => $slug ]);

        if (!$post) {

            throw $this->createNotFoundException();
        }

        $commments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findBy(['postId' => $post[0]->getId() ]);
        return $this->render("posts/show.html.twig", ['post' => $post, 'comments' => $commments]);
    }
}
