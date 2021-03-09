<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Post;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
//        $post = $this->getDoctrine()
//            ->getRepository(Post::class)
//            ->findAll();
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Post::class)->BuscarTodosLosPost();
        $ultimos = $em->getRepository(Post::class)->BuscarUltimosPost();

        $post = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            3 /*limit per page*/
        );

        return $this->render('home/index.html.twig', compact("post","ultimos"));
    }

    /**
     * @Route("/detail-post/{id}", name="detail_post", defaults={"id":0})
     */
    public function detailPost($id): Response
    {
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);
//            ->findOneBy(array("id"=>$id));
        return $this->render('home/detail.html.twig', compact('post','id'));
    }


    /**
     * @Route("/add-comment", name="addcomment")
     */
    public function addComment(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->getMethod() == 'POST'){
            $user = $this->getUser();
            $userName =$user->getNombre();
            $data = $request->request->all();
            $coment = new Comments();
            $coment->setCommentDate(new \DateTime("now"));
            $coment->setUser($userName);
            $coment->setDetail($data['detail']);
            $coment->setPostId($this->getDoctrine()->getRepository('App:Post')->find($data['post_id']));

            $em->persist($coment);
            $em->flush();

            $detail = $coment->getDetail();
            $idcomment = $coment->getId();

            $commentdate = $coment->getCommentDate()->format('d/m/Y');

            $response = new Response(json_encode(compact('detail','idcomment','commentdate','userName')));
            $response-> headers->set('Content-Type','applicaction/json');
            return $response;

        }
    }
}
