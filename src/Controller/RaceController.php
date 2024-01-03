<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Race;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class RaceController extends AbstractController
{
    #[Route('/race', name: 'app_race')]
    public function index(): Response
    {
        return $this->render('race/index.html.twig', [
            'controller_name' => 'RaceController',
        ]);
    }

    // Permet de créer la route pour aller sur cette page
    #[Route('/race/create', name: 'app_race_create')] 
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nom = $request->request->get('inputRace');

        // Creer un nouveau joueur
        $race = new Race();
        // Les parametres du nouveau joueur qui vient d'etre créer
        $race
            ->setNom("$nom");
        $entityManager->persist($race);
        $entityManager->flush();
        
        // Fait une redirection pointé vers le name de ma route player show 
        // Et on rajoute un parametre qui est name pour que la route de player show puisse l'afficher
        return $this->redirectToRoute('app_race_show', ['id' => $race->getId()]);
    }

    #[Route('/race/forms', name: 'app_race_forms')]
    public function formulaireRace ()
    {
        return $this->render('race/create.html.twig');
    }

    #[Route('/race/show/{id}', name: 'app_race_show')] 
    public function show(Race $race, EntityManagerInterface $entityManager) 
    {
         // On affiche les informations dans notre page twig de Player
        $races = $entityManager->getRepository(Race::class)->findAll();

         return $this->render('race/show.html.twig', [
            'race' => $race,
            'races' => $races,
        ]);
    }
}
