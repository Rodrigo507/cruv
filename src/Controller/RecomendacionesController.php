<?php

namespace App\Controller;

use App\Entity\Recomendaciones;
use App\Form\RecomendacionesType;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecomendacionesController extends AbstractController
{
    /**
     * @Route("/recomendaciones", name="recomendaciones")
     */
    public function index(Request $request): Response
    {
        $recomendacions = new Recomendaciones();
        $formulario = $this->createForm(RecomendacionesType::class,$recomendacions);
        $formulario->handleRequest($request);
        if ($formulario->isSubmitted() && $formulario->isValid()){
            $em = $this->getDoctrine()->getManager();
            $recomendacions->setDate(new \DateTime('now'));
            $em->persist($recomendacions);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('recomendaciones/index.html.twig', [
            'formulario' => $formulario->createView(),
        ]);
    }
}
