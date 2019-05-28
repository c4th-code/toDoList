<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
* @Route("/task")
*/

class TaskController extends AbstractController
{
    /**
    * @Route("/", name="TaskList")
    */
    public function show(Request $request,TaskRepository $taskRepository) : Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $task->setStatus(false);
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('TaskList');
        }

        return $this->render("form.html.twig",[
            'task' => $task,
            'form' => $form->createView(),
            'tasks'=> $taskRepository->findBy(array(),['status'=>'ASC'])
        ]);
    }

    /**
    * @Route("/update/{id}", name="task_update", methods={"GET","POST"})
    */
    public function update(Request $request, Task $task): Response
    {
        $task->getStatus()?$task->setStatus(false):$task->setStatus(true);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('TaskList');
    }
}
