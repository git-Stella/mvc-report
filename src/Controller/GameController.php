<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
//use App\Card\CardGraphic;
use App\Card\DeckOfCards;
use App\Card\DeckOfJokers;
use App\Game\Player;
use App\Game\Bank;
//use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

//put version of deck with jokers in json api...
class GameController extends AbstractController
{
    #[Route("/game", name: "game_start")]
    public function home(
        SessionInterface $session
    ): Response {
        //put in a continue button somewhere if session not empty...
        //in home
        return $this->render('game/home.html.twig');
    }
    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
    #[Route("/game/new", name: "game_new")]
    public function playerTurn(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfJokers();
        $deck->shuffle();
        $player = new Player();
        $bank = new Bank();

        $session->set("deck", count($deck->deck));

        $session->set("deckArray", $deck->deck);

        $session->set("playerHand", $player->hand);

        $session->set("bankHand", $bank->hand);

        //$session->set("playerScore", 0);

        //$session->set("bankScore", 0);

        $session->set("playerPoints", 0);

        //$session->set("bankPoints", 0);

        return $this->render('game/new.html.twig');
    }
    #[Route("/game/clear", name: "game_clear")]
    public function restartGame(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Session has been cleared.'
        );
        return $this->redirectToRoute('game_start');
    }
    #[Route("/game/draw", name: "game_draw")]
    public function playerDraw(
        SessionInterface $session
    ): Response {
        $player = new Player();
        $deck = new DeckOfJokers();
        $deck->swapShuffle($session->get('deckArray', $deck->deck));
        $player->hand = $session->get("playerHand", []);
        $player->drawCard($deck);

        $session->set('deckArray', $deck->deck);
        $session->set("deck", count($deck->deck));
        $session->set("playerHand", $player->hand);

        /*$data = [
            "hand" => $player->showHand()
        ];*/

        return $this->redirect('gamestate');
    }
    #[Route("/game/gamestate", name: "game_state")]
    public function gameState(
        SessionInterface $session
    ): Response {
        $player = new Player();
        $player->hand = $session->get("playerHand", []);
        $data = [
            "hand" => $player->showHand(),
            "scores" => $player->calcPoints()
        ];
        return $this->render('game/gamestate.html.twig', $data);
    }
    #[Route("/game/satisfaction", name: "game_satisfaction", methods: ['POST'])]
    public function teamSatisfaction(
        SessionInterface $session
    ): Response {
        //need this to add player score then redirect to game/vs
        //redirect it differently if the number is above 21 already...
        //turn this into an int below:
        $session->set("playerPoints", $_POST["selector"]);
        return $this->redirect('vs');
    }
    #[Route("/game/vs", name: "game_vs")]
    public function fight(
        SessionInterface $session
    ): Response {
        $bank = new Bank();
        $bank->hand = $session->get("bankHand", []);
        $player = new Player();
        $player->hand = $session->get("playerHand", []);
        $deck = new DeckOfJokers();
        $deck->swapShuffle($session->get('deckArray', $deck->deck));
        $bank->drawCards($deck);
        $session->set('deckArray', $deck->deck);
        $session->set("deck", count($deck->deck));
        $session->set("bankHand", $bank->hand);
        $pPoints = $session->get("playerPoints", 0);
        //$bankPoints = $bank->calcPoints();

        //get which bank points used ideally
        //compare to player points
        //add flash based on who wins
        //add a <h1> with extra class for color based on winner...
        //button to restart below

        $bankPoints = $bank->pickPoints();

        $data = [
            "hand" => $bank->showHand(),
            "phand" => $player->showHand(),
            "scores" => $bank->calcPoints(),
            "pscore" => $pPoints
        ];

        if ($pPoints > 21) {
            $pPoints = -10;
        }

        if ($bankPoints < $pPoints) {
            $this->addFlash(
                'notice',
                'Player win!'
            );
            return $this->render('game/clash.html.twig', $data);
        }

        $this->addFlash(
            'warning',
            'Player LOSS!'
        );

        return $this->render('game/clash.html.twig', $data);
    }
    #[Route("/game/final", name: "game_final")]
    public function finale(
        SessionInterface $session
    ): Response {
        //just gotta finish up the final one for the reset...
        return $this->render('game/clash.html.twig', $data);
    }
    #[Route("/game/test", name: "game_test")]
    public function testinger(): Response
    {
        $bank = new Bank();
        $player = new Player();
        $deck = new DeckOfJokers();

        $deck->shuffle();
        $bank->drawCards($deck, 3);
        $player->drawCard($deck);
        $player->drawCard($deck);
        $player->drawCard($deck);

        $data = [
            "hand" => $bank->playHand(),
            "phand" => $player->playHand(),
            "deck" => $deck->returnDeck(),
            "deckNum" => $deck->getNumberCards()
        ];
        return $this->render('game/test.html.twig', $data);
    }
}
