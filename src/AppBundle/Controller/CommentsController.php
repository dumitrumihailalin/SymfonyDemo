<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Comment;

class CommentsController extends Controller
{
    /**
     * @Route("/comments", name="comments")
     */
    public function indexAction()
    {
        $comment = 'salut';
        return $this->render("comments/index.html.twig", ['comment' => $comment]);
    }
}
