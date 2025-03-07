<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    // Route pour afficher toutes les tâches avec un filtre
    #[Route('/task', name: 'app_task_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository, Request $request): Response
    {
        // Récupération du filtre
        $filter = $request->query->get('filter', 'all');

        // Construction de la requête en fonction du filtre
        $queryBuilder = $taskRepository->createQueryBuilder('t');

        if ($filter === 'completed') {
            $queryBuilder->where('t.status = true');
        } elseif ($filter === 'pending') {
            $queryBuilder->where('t.status = false');
        }

        $tasks = $queryBuilder->getQuery()->getResult();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
            'filter' => $filter,
        ]);
    }

    // Route pour créer une nouvelle tâche
    #[Route('/task/new', name: 'app_task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Tâche créée avec succès.');
            return $this->redirectToRoute('app_task_index');
        }

        return $this->render('task/new.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }

    // Route pour afficher une tâche spécifique
    #[Route('/task/{id}', name: 'app_task_show', methods: ['GET'])]
    public function show(Task $task): Response
    {
        return $this->render('task/show.html.twig', [
            'task' => $task,
        ]);
    }

    // Route pour éditer une tâche existante
    #[Route('/task/{id}/edit', name: 'app_task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Tâche modifiée avec succès.');
            return $this->redirectToRoute('app_task_index'); // Correction de la redirection
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(), // Utiliser createView() pour afficher le formulaire
        ]);
    }

    // Route pour supprimer une tâche
    #[Route('/task/{id}', name: 'app_task_delete', methods: ['POST'])]
    public function delete(Request $request, Task $task, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF pour la suppression
        if ($this->isCsrfTokenValid('delete' . $task->getId(), $request->request->get('_token'))) {
            $entityManager->remove($task);
            $entityManager->flush();

            $this->addFlash('success', 'Tâche supprimée avec succès.');
        }

        return $this->redirectToRoute('app_task_index'); // Correction de la redirection
    }
}
