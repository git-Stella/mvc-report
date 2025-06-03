<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\DeckOfJokers;
use App\Game\Player;
use App\Game\Bank;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller for the game in kmom03
 */
class GameController extends AbstractController
{
    /**
     * Route to start the game and explain the rules of it.
     */
    #[Route("/game", name: "game_start")]
    public function home(): Response {
        return $this->render('game/home.html.twig');
    }
    /**
     * Route to documentation of the game.
     */
    #[Route("/game/doc", name: "game_doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }
    /**
     * Route for new game starting up for real.
     */
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

        $session->set("playerPoints", 0);

        return $this->render('game/new.html.twig');
    }
    /**
     * Route to clear the session.
     */
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
    /**
     * Route for the player to draw a card.
     */
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

        return $this->redirect('gamestate');
    }
    /**
     * Route showing the current gamestate.
     */
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
    /**
     * Route setting player points before redirecting to the bank's turn.
     */
    #[Route("/game/satisfaction", name: "game_satisfaction", methods: ['POST'])]
    public function teamSatisfaction(
        SessionInterface $session
    ): Response {
        $session->set("playerPoints", $_POST["selector"]);
        return $this->redirect('vs');
    }
    /**
     * Route for the bank's turn and calculating who won.
     */
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

        $bankPoints = $bank->pickPoints();

        $pScore = $session->get("playerScore", 0);
        $bScore = $session->get("bankScore", 0);

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
                'Player WIN!'
            );
            $pScore += 1;
            $session->set("playerScore", $pScore);
            return $this->render('game/clash.html.twig', $data);
        }

        $this->addFlash(
            'warning',
            'Player LOSS!'
        );

        $bScore += 1;
        $session->set("bankScore", $bScore);

        return $this->render('game/clash.html.twig', $data);
    }
    /**
     * Route showing the current gamestate as JSON api.
     */
    #[Route("/api/game", name: "game_api")]
    public function apiGame(
        SessionInterface $session
    ): Response {
        $player = new Player();
        $player->hand = $session->get("playerHand", []);

        $bank = new Bank();
        $bank->hand = $session->get("bankHand", []);

        $deck = new DeckOfJokers();
        $deck->swapShuffle($session->get('deckArray', $deck->deck));

        $data = [
            'player-hand' => $player->showHand(),
            'bank-hand' => $bank->showHand(),
            'deck-number' => $session->get('deck', count($deck->deck)),
            'player-score' => $session->get('playerScore', 0),
            'bank-score' => $session->get('bankScore', 0),
            'deck' => $deck->returnDeck()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    /**
     * Route to test various functions. Comment out if needed.
     */
    /*#[Route("/game/test", name: "game_test")]
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
    }*/
}
