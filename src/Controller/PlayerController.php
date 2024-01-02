<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PlayerController extends AbstractController
{
    // Permet de créer la route pour aller sur cette page
    #[Route('/player/create', name: 'app_player_create')] 
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        $name = $request->request->get('inputName');
        $ad = $request->request->get('inputAd');
        $ap = $request->request->get('inputAp');
        $pv = $request->request->get('inputPv');
        $mana = $request->request->get('inputMana');

        // Creer un nouveau joueur
        $player = new Player();
        // Les parametres du nouveau joueur qui vient d'etre créer
        $player
            ->setName("$name")
            ->setAd((int)$ad)
            ->setAp((int)$ap)
            ->setPv((int)$pv)
            ->setMana((int)$mana);
        $entityManager->persist($player);
        $entityManager->flush();
        
        // Fait une redirection pointé vers le name de ma route player show 
        // Et on rajoute un parametre qui est name pour que la route de player show puisse l'afficher
        return $this->redirectToRoute('app_player_show', ['id' => $player->getId()]);
    }

    #[Route('/player/formulairePlayer', name: 'app_player_formulaire')]
    public function formulairePlayer () 
    {
        return $this->render('player/formulaire.html.twig');
    }

    #[Route('/player/show/{id}', name: 'app_player_show')] 
    public function show(Player $player) 
    {
         // On affiche les informations dans notre page twig de Player
         return $this->render('player/show.html.twig', [
            'player' => $player,
        ]);
    }

    #[Route('/player/delete/{id}', name: 'app_player_delete')] 
    public function delete (EntityManagerInterface $entityManager, Player $player) 
    {
        $entityManager->remove($player);
        $entityManager->flush();
        return $this->redirectToRoute('app_player_show_all');

    }
    

    #[Route('/player/all', name: 'app_player_show_all')]
    public function showAll (EntityManagerInterface $entityManager) 
    {
        $players = $entityManager->getRepository(Player::class)->findAll();
        return $this->render('player/index.html.twig', ['players' => $players]);
    }

    #[Route('/player/update/{id}', name: 'app_player_update')]
    // Le request sert a detecter si il y a soumissions de formulaire
    public function update(Request $request, EntityManagerInterface $entityManager, Player $player): Response
    {
        if ($request->isMethod('POST')) {
            // Récupérer les nouvelles valeurs du formulaire
            $name = $request->request->get('inputName');
            $ad = $request->request->get('inputAd');
            $ap = $request->request->get('inputAp');
            $pv = $request->request->get('inputPv');
            $mana = $request->request->get('inputMana');
    
            // Mettre à jour les propriétés du joueur
            $player
                ->setName($name)
                ->setAd((int)$ad)
                ->setAp((int)$ap)
                ->setPv((int)$pv)
                ->setMana((int)$mana);

                // Charge l'entity
                $entityManager->flush();
                
            return $this->redirectToRoute('app_player_show_all');
        }
        return $this->render('player/update.html.twig', [
            'player' => $player,
        ]);
    }
}
