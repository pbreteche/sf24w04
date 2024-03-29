<?php

namespace App\Controller;

use App\Entity\Conge;
use App\EventSubscriber\Event\AppEvent;
use App\EventSubscriber\Event\CongeShowEvent;
use App\Form\CongeType;
use App\Repository\CongeRepository;
use App\Security\Voter\CongeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\Cache;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\EventDispatcher\Event;

#[Route('/conge')]
class CongeController extends AbstractController
{
    #[Route('/', name: 'app_conge_index', methods: ['GET'])]
    public function index(CongeRepository $congeRepository): Response
    {
        return $this->render('conge/index.html.twig', [
            'conges' => $congeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_conge_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conge = new Conge();
        $conge->setBenefciaire($this->getUser());
        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conge);
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/new.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_show', methods: ['GET'])]
    #[Cache(expires: 'tomorrow', public: true)]
    public function show(
        Conge $conge,
        Request $request,
        EventDispatcherInterface $dispatcher,
        Stopwatch $stopwatch,
    ): Response
    {
        $stopwatch->start('conge show controller');
        $response = new Response();
        // Il nous faudrait une colonne "updatedAt"
        $response->setLastModified(new \DateTimeImmutable('2024-01-15'));
        $response->setPublic();

        $stopwatch->stop('conge show controller');
        if ($response->isNotModified($request)) {
            return $response;
        }

        $dispatcher->dispatch(new Event(), AppEvent::CONGE_SHOW_EVENT);
        $dispatcher->dispatch(new CongeShowEvent($conge));

        $response->setContent($this->renderView('conge/show.html.twig', [
            'conge' => $conge,
        ]));

        $response
            ->setExpires(new \DateTimeImmutable('tomorrow'))
            ->setPublic()
        ;

        $response->headers->set('Cache-control', 'public');

        return $response;
    }

    #[Route('/{id}/edit', name: 'app_conge_edit', methods: ['GET', 'POST'])]
    #[IsGranted(new Expression(
        'is_granted("ROLE_ADMIN") or user === subject.getBenefciaire()'
    ), subject: 'conge')]
    #[IsGranted(CongeVoter::EDIT, subject: 'conge')]
    public function edit(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        $everyOne = $this->isGranted('ROLE_ADMIN');

        if (rand(0, 5) > 3) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(CongeType::class, $conge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('conge/edit.html.twig', [
            'conge' => $conge,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conge_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Conge $conge, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conge->getId(), $request->request->get('_token'))) {
            $entityManager->remove($conge);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_conge_index', [], Response::HTTP_SEE_OTHER);
    }
}
