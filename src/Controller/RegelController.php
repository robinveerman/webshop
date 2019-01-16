<?php

namespace App\Controller;

use App\Entity\Regel;
use App\Form\RegelType;
use App\Repository\RegelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/regel")
 */
class RegelController extends AbstractController
{
    /**
     * @Route("/", name="regel_index", methods="GET")
     */
    public function index(RegelRepository $regelRepository): Response
    {
        return $this->render('regel/index.html.twig', ['regels' => $regelRepository->findAll()]);
    }

    /**
     * @Route("/new", name="regel_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $regel = new Regel();
        $form = $this->createForm(RegelType::class, $regel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($regel);
            $em->flush();

            return $this->redirectToRoute('regel_index');
        }

        return $this->render('regel/new.html.twig', [
            'regel' => $regel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="regel_show", methods="GET")
     */
    public function show(Regel $regel): Response
    {
        return $this->render('regel/show.html.twig', ['regel' => $regel]);
    }

    /**
     * @Route("/{id}/edit", name="regel_edit", methods="GET|POST")
     */
    public function edit(Request $request, Regel $regel): Response
    {
        $form = $this->createForm(RegelType::class, $regel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('regel_index', ['id' => $regel->getId()]);
        }

        return $this->render('regel/edit.html.twig', [
            'regel' => $regel,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="regel_delete", methods="DELETE")
     */
    public function delete(Request $request, Regel $regel): Response
    {
        if ($this->isCsrfTokenValid('delete'.$regel->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($regel);
            $em->flush();
        }

        return $this->redirectToRoute('regel_index');
    }
}
