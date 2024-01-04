<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Player;
use App\Form\PlayerType;
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

    #[Route('/player/createSymfony', name: 'app_player_createForms')]
    public function createAction(Request $request, EntityManagerInterface $entityManager): Response
    {
        $player = new Player();


        $form = $this->createForm(PlayerType::class, $player);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Accédez aux données du formulaire avec getData()
            $player = $form->getData();

            $this->addFlash(
                'notice',
                'Le formulaire a bien été saisi !'
            );

            // Utilisez l'EntityManager pour persister l'objet Player
            // Persist permet d'enregisreter le nouveau Player qui veitn d'etre créer
            $entityManager->persist($player);
            // Appliquez les changements dans la base de données
            $entityManager->flush();

            return $this->redirectToRoute('app_player_show', ['id' => $player->getId()]);
        }

        return $this->render('/player/formSym.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/player/show/{id}', name: 'app_player_show')] 
    public function show(Player $player, EntityManagerInterface $entityManager) 
    {
         // On affiche les informations dans notre page twig de Player
        $players = $entityManager->getRepository(Player::class)->findAll();

         return $this->render('player/show.html.twig', [
            'player' => $player,
            'players' => $players,
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

    #[Route('/player/damage/{id}/{targetId}/{type}', name: 'app_player_damage')]
    public function damage(Request $request, EntityManagerInterface $entityManager, Player $player, $id, $targetId, $type): Response
    {
        if ($player->getPv() <= 0) {
            // Gérez le cas où le joueur attaquant a 0 points de vie
            $this->addFlash('error', 'Impossible d\'attaquer avec 0 points de vie.');
        } else {
            // Récupérez le joueur cible à partir de la base de données (par son ID)
            $targetPlayer = $entityManager->getRepository(Player::class)->find($targetId);

            if ($targetPlayer->getPv() <= 0) {
                // Gérez le cas où le joueur cible a déjà 0 points de vie
                $this->addFlash('error', 'Impossible d\'attaquer un joueur ayant 0 points de vie.');
            } else {
                // Effectuez l'attaque en fonction du type (AD ou AP)
                if ($type === 'ad') {
                    // Logique d'attaque AD
                    $damage = $player->getAd(); // Utilisez la valeur AD du joueur attaquant
                } elseif ($type === 'ap') {
                    // Logique d'attaque AP
                    $damage = $player->getAp(); // Utilisez la valeur AP du joueur attaquant
                } else {
                    // Gérez les autres cas si nécessaire
                    throw new \Exception("Type d'attaque non pris en charge");
                }

                // Vérifiez si le joueur attaquant a suffisamment de mana pour lancer l'attaque
                $manaCost = 30;
                if ($player->getMana() >= $manaCost) {
                    // Appliquez les dégâts au joueur cible
                    $targetPlayer->setPv($targetPlayer->getPv() - $damage); // Supprime les points de vie

                    // Déduisez le coût en mana de l'attaquant
                    $player->setMana($player->getMana() - $manaCost);

                    // Enregistrez les modifications dans la base de données
                    $entityManager->flush();
                } else {
                    // Gérez le cas où le joueur n'a pas assez de mana
                    $this->addFlash('error', 'Pas assez de mana pour lancer l\'attaque.');
                }
            }
        }
        

        // Récupérez la page de référence depuis la requête
        $referrer = $request->headers->get('referer');

        // Redirigez l'utilisateur vers la page de référence ou vers la page de détails du joueur attaquant si la référence est vide
        return $this->redirect($referrer ?: $this->generateUrl('app_player_show', ['id' => $player->getId()]));
    }
}
