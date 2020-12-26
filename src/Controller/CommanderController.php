<?php

namespace App\Controller;

use App\Entity\Commander;
use App\Form\CommanderType;
use App\Repository\CommanderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commander")
 */
class CommanderController extends AbstractController
{
    /**
     * @Route("/", name="commander_index", methods={"GET"})
     */
    public function index(CommanderRepository $commanderRepository): Response
    {
        return $this->render('commander/index.html.twig', [
            'commanders' => $commanderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commander_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commander = new Commander();
        $form = $this->createForm(CommanderType::class, $commander);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commander);
            $entityManager->flush();

            return $this->redirectToRoute('commander_index');
        }

        return $this->render('commander/new.html.twig', [
            'commander' => $commander,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commander_show", methods={"GET"})
     */
    public function show(Commander $commander): Response
    {
        return $this->render('commander/show.html.twig', [
            'commander' => $commander,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commander_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commander $commander): Response
    {
        $form = $this->createForm(CommanderType::class, $commander);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commander_index');
        }

        return $this->render('commander/edit.html.twig', [
            'commander' => $commander,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commander_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Commander $commander): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commander->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commander);
            $entityManager->flush();
        }

        return $this->redirectToRoute('commander_index');
    }
}
