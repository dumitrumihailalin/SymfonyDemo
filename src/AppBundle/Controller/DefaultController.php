<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\DBAL\Driver\Connection;
class DefaultController extends Controller
{

    protected $connection;
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $posts = [];
        $post = $this->connection->query('SELECT blog_post.title, blog_post.slug, blog_post.description, user.name FROM blog_post LEFT JOIN user ON blog_post.author = user.id limit 10');
        $posts = $post->fetchAll();

        return $this->render("default/index.html.twig", ['posts' => $posts]);

    }
}
