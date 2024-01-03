<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player;
use App\Entity\Pet;
use App\Entity\Race;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PetsController extends AbstractController
{
    #[Route('/pets', name: 'app_pets')]
    public function index(): Response
    {
        return $this->render('pets/index.html.twig', [
            'controller_name' => 'PetsController',
        ]);
    }

    // Permet de créer la route pour aller sur cette page
    #[Route('/pets/create/{playerId}', name: 'app_pets_create')] 
    public function save(Request $request, EntityManagerInterface $entityManager, $playerId): Response
    {
        
        // Récupérer le joueur spécifique
        $player = $entityManager->getRepository(Player::class)->find($playerId);

        $name = $request->request->get('inputName');
        $xp = $request->request->get('inputXp');
        $niveau = $request->request->get('inputNiveau');
        $ad = $request->request->get('inputAd');
        $ap = $request->request->get('inputAp');
        $pv = $request->request->get('inputPv');
        $mana = $request->request->get('inputMana');
        $races = $request->request->get('inputRace');

        // Récupérer l'instance de la Race à partir de l'ID
        $raceId = $entityManager->getRepository(Race::class)->find($races);

        // Creer un nouveau race
        $pet = new Pet();
        // Les parametres du nouveau joueur qui vient d'etre créer
        $pet
            ->setName("$name")
            ->setXp((int)$xp)
            ->setNiveau((int)$niveau)
            ->setAd((int)$ad)
            ->setAp((int)$ap)
            ->setPv((int)$pv)
            ->setMana((int)$mana)
            ->setRace($raceId)
            ->setOwner($player);
        $entityManager->persist($pet);
        $entityManager->flush();
        
        // Fait une redirection pointé vers le name de ma route player show 
        // Et on rajoute un parametre qui est name pour que la route de player show puisse l'afficher
        return $this->redirectToRoute('app_pets_show', [
            'id' => $pet->getId()
        ]);
    }

    #[Route('/pets/forms/{playerId}', name: 'app_pets_forms')]
    public function formulairePlayer (EntityManagerInterface $entityManager, $playerId): Response
    {
        $races = $entityManager->getRepository(Race::class)->findAll();
        $player = $entityManager->getRepository(Player::class)->find($playerId);
        return $this->render('pets/create.html.twig', [
            'races' => $races,
            'player' =>$player,
        ]);
    }

    #[Route('/pets/show/{id}', name: 'app_pets_show')] 
    public function show(Pet $pet, EntityManagerInterface $entityManager) 
    {
         // On affiche les informations dans notre page twig de Player
        $pets = $entityManager->getRepository(Pet::class)->findAll();

         return $this->render('pets/show.html.twig', [
            'pet' => $pet,
            'pets' => $pets,
        ]);
    }
}
