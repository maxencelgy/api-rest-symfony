<?php

namespace App\Controller;

use App\Entity\Project;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/api', name: 'api_')]
class ProjectController extends AbstractController
{
    #[Route('/project', name: 'app_project', methods: ['GET'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        $projects = $doctrine
            ->getRepository(Project::class)
            ->findAll();
        $data = [];

        foreach ($projects as $project){
            $data[] = [
              'name' => $project->getName(),
              'description' => $project->getDescription(),
            ];
        }
        return $this->json($data);
    }

//post
//getOnebyid
//delete
    #[Route('/project/post', name: 'app_post', methods: ['GET','POST'])]
    public function post(ManagerRegistry $doctrine): JsonResponse
    {
        //Gere l envoie de données entre bdd et php
        $entityManager = $doctrine->getManager();
        $post =  new Project();

        $post->setName('five');
        $post->setDescription('adzdazdazd');
//        Persist garde en tempon
        $entityManager->persist($post);
//        FLUSH ca envoie
        $entityManager->flush();

        return $this->json('bien jouer hermano c\'est envoyer');
    }


    #[Route('/project/{id}', name: 'get_one', methods: ['GET'])]
    public function get(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $projects = $doctrine
            ->getRepository(Project::class)
            ->findOneById($id);
        $data = [
            'name' => $projects->getName(),
            'description' => $projects->getDescription(),
        ];
        return $this->json($data);
    }

    #[Route('/project/{id}/delete', name: 'delete_one', methods: ['GET','DELETE'])]
    public function delete(ManagerRegistry $doctrine, $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $project = $doctrine->getRepository(Project::class)->findOneById($id);
        $entityManager->remove($project);
        $entityManager->flush($project);


        return $this->json('Ca a bien était delete');
    }
}
