<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\DeckOfCards;
use App\Poker\Rules;
use App\Poker\Players;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Controller class for standard twig using routes.
 */
class ProjectController extends AbstractController
{
    /**
     * Home page.
     */
    #[Route("/proj", name: "proj")]
    public function projHome(
        SessionInterface $session
    ): Response {
        if (null === $session->get("playerHand")) {
            $session->set("playerHand", []);
        }
        if (null === $session->get("computerHand")) {
            $session->set("computerHand", []);
        }
        $deck = new DeckOfCards();
        $data = [
            "deck" => $deck->returnDeck()
        ];
        return $this->render('proj/home.html.twig', $data);
    }
    /**
     * About page.
     */
    #[Route("/proj/about", name: "projabout")]
    public function projAbout(): Response
    {
        return $this->render('proj/about.html.twig');
    }
    /**
     * Test page.
     */
    #[Route("/proj/test", name: "projtest")]
    public function projTest(
        SessionInterface $session
    ): Response {
        //make the session affect the hand...
        if (null === $session->get("playerHand")) {
            $session->set("playerHand", []);
        }
        if (null === $session->get("computerHand")) {
            $session->set("computerHand", []);
        }
        $deck = new DeckOfCards();
        $pHand = [];
        $cHand = [];
        $temp1 = $deck->draw(5);
        $temp2 = $deck->draw(5);
        //temp as $deck->deck if I wanna take ones from deck
        //I could use remove to precision remove the cards for each hand...
        foreach ($temp1 as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $pHand[] = '[' . $suit . $val . ']';
        }
        foreach ($temp2 as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $cHand[] = '[' . $suit . $val . ']';
        }
        $data = [
            "deck" => $deck->returnDeck(),
            "hand" => $pHand,
            "cHand" => $cHand
        ];
        return $this->render('proj/test.html.twig', $data);
    }
    /**
     * Route to reset session.
     */
    #[Route("/proj/reset", name: "projreset")]
    public function projReset(
        SessionInterface $session
    ): Response {
        $session->clear();
        $this->addFlash(
            'notice',
            'Session has been cleared.'
        );
        return $this->redirectToRoute('proj');
    }
    /**
     * Route for the basic gamestate.
     */
    #[Route("/proj/gamestate", name: "projstate")]
    public function projGamestate(
        SessionInterface $session
    ): Response {
        if (5 == $session->get("currentRound")) {
            return $this->redirectToRoute('projclash');
        }
        $deck = new DeckOfCards();
        $players = new Players();
        $deck->shuffle();
        $pHand = [];
        $cHand = [];
        $pDisplay = [];
        $cDisplay = [];
        $pPot = 50;
        $cPot = 50;
        $funds = 5000;
        if (null !== $session->get("gain")) {
            $funds += $session->get("gain");
        }
        if (null !== $session->get("loss")) {
            $funds -= $session->get("loss");
        }
        if (null !== $session->get("playerHand")) {
            $pHand = $session->get("playerHand");
        }
        if (null !== $session->get("computerHand")) {
            $cHand = $session->get("computerHand");
        }
        //if session not null on deck apply it...
        if (null !== $session->get("pokerDeck")) {
            $deck->swapShuffle($session->get("pokerDeck"));
        }
        $time = 2;
        if (null !== $session->get("roundPart")) {
            $time = $session->get("roundPart");
        }
        if (null !== $session->get("playerPot")) {
            $pPot = $session->get("playerPot");
        }
        if (null !== $session->get("computerPot")) {
            $cPot = $session->get("computerPot");
        }
        $called = $pPot;
        if (null !== $session->get("playerFunds")) {
            $funds = $session->get("playerFunds");
        }
        if ($pPot < $cPot) {
            $pPot = $cPot;
        }
        if (count($pHand) < 5) {
            $difference = 5 - count($pHand);
            $temp1 = $deck->draw($difference);
            foreach ($temp1 as $card) {
                //$suit = $card->getColor();
                //$val = $card->getKingdom();
                //$pDisplay[] = '[' . $suit . $val . ']';
                $pHand[] = $card;
            }
        }
        foreach ($pHand as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $pDisplay[] = '[' . $suit . $val . ']';
            //$pHand[] = $card;
        }
        if (null == $session->get("computerHand")) {
            $temp2 = $deck->draw(5);
            foreach ($temp2 as $card) {
                $suit = $card->getColor();
                $val = $card->getKingdom();
                $cDisplay[] = '[' . $suit . $val . ']';
                $cHand[] = $card;
            }
        }
        if (null !== $session->get("computerHand")) {
            //$players->cHand = $cHand;
            //$deck->deck = $players->handLogic($deck);
            foreach ($cHand as $card) {
                $suit = $card->getColor();
                $val = $card->getKingdom();
                $cDisplay[] = '[' . $suit . $val . ']';
            }
        }
        $username = "No one";
        if (null !== $session->get("username")) {
            $username = $session->get("username");
        }
        $session->set("pokerDeck", $deck->deck);
        $session->set("playerHand", $pHand);
        $session->set("computerHand", $cHand);
        $session->set("roundPart", $time);
        //do round of betting, change time to 1
        $data = [
            "called" => $called,
            "computerpot" => $cPot,
            "playerpot" => $pPot,
            "time" => $time,
            "deck" => count($deck->deck),
            "playerHand" => $pDisplay,
            "computerHand" => $cDisplay,
            "funds" => $funds,
            "username" => $session->get("username")
        ];
        return $this->render('proj/gamestate.html.twig', $data);
    }
    /**
     * Route for raising bets.
     */
    #[Route("/proj/round", name: "projround", methods: ['POST'])]
    public function projRound(
        Request $request,
        SessionInterface $session
    ): Response {
        $pPot = 50;
        $cPot = 50;

        if (null !== $session->get("playerPot")) {
            $pPot = $session->get("playerPot");
        }
        if (null !== $session->get("computerPot")) {
            $cPot = $session->get("computerPot");
        }
        $players = new Players();
        $num = $request->request->get('num');
        $players->cBet = $cPot;
        $players->pBet = $pPot;
        if (null !== $session->get("gain")) {
            $players->pFunds += $session->get("gain");
        }
        if (null !== $session->get("loss")) {
            $players->pFunds -= $session->get("loss");
        }
        $res = $players->bet($num);
        //need to have something happen if fold or all in
        //must also let player fold... put that in html.
        if ($res == "fold") {
            return $this->redirectToRoute('projwin');
        }
        if ($res == "all in") {
            return $this->redirectToRoute('projclash');
        }
        $players->pFunds -= $players->pBet;
        $players->cFunds -= $players->cBet;
        $session->set("playerPot", $players->pBet);
        $session->set("computerPot", $players->cBet);
        $session->set("playerFunds", $players->pFunds);
        $session->set("computerFunds", $players->cFunds);
        $this->addFlash(
            'notice',
            'Round'
        );
        $round = 0;
        if (null !== $session->get("currentRound")) {
            $round = $session->get("currentRound");
        }
        $round += 1;
        $session->set("currentRound", $round);
        $time = 1;
        $session->set("roundPart", $time);
        $session->set("currentRound", $round);
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route to draw cards.
     */
    #[Route("/proj/draw", name: "projdraw", methods: ['POST'])]
    public function projDraw(
        Request $request,
        SessionInterface $session
    ): Response {
        $players = new Players();
        $deck = new DeckOfCards();
        $deck->swapShuffle($session->get("pokerDeck"));
        $hand = $session->get("playerHand");
        $cHand = $session->get("computerHand");
        $players->cHand = $cHand;
        $deck->deck = $players->handLogic($deck);
        if ($request->request->get('card4')) {
            //$card4 = $request->request->get('card4');
            array_splice($hand, 4, 1);
        }
        if ($request->request->get('card3')) {
            //$card3 = $request->request->get('card3');
            array_splice($hand, 3, 1);
        }
        if ($request->request->get('card2')) {
            //$card2 = $request->request->get('card2');
            array_splice($hand, 2, 1);
        }
        if ($request->request->get('card1')) {
            //$card1 = $request->request->get('card1');
            array_splice($hand, 1, 1);
        }
        if ($request->request->get('card0')) {
            //$card0 = $request->request->get('card0');
            array_splice($hand, 0, 1);
        }
        $round = 0;
        if (null !== $session->get("currentRound")) {
            $round = $session->get("currentRound");
        }
        $round += 1;
        $session->set("currentRound", $round);
        //print($draw);
        $this->addFlash(
            'notice',
            'Draw'
        );
        $time = 0;
        $session->set("roundPart", $time);
        $session->set("playerHand", $hand);
        $session->set("computerHand", $cHand);
        $session->set("pokerDeck", $deck->deck);
        //return $draw;
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route to register username.
     */
    #[Route("/proj/startup", name: "projstart", methods: ['POST'])]
    public function projStarter(
        Request $request,
        SessionInterface $session
    ): Response {
        $time = 0;
        $session->set("roundPart", $time);
        $session->set("username", $request->request->get('name'));
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route for tallying up the loss/gains and seeing who won.
     */
    #[Route("/proj/clash", name: "projclash")]
    public function projClash(
        SessionInterface $session
    ): Response {
        $session->set("currentRound", 0);
        $session->set("roundPart", 3);
        $money = 0;
        $players = new Players();
        $players->pHand = $session->get("playerHand");
        $players->cHand = $session->get("computerHand");
        $players->pBet = $session->get("playerPot");
        $players->cBet = $session->get("computerPot");
        $res = $players->clash();
        if ($res == "Player won!") {
            $this->addFlash(
                'notice',
                'Player won!'
            );
            $money = $players->cBet;
            if ($session->get("gain")) {
                $gain = $session->get("gain");
                $money += $gain;
            }
            $session->set("gain", $money);
        }
        if ($res == "Computer won!") {
            $this->addFlash(
                'warning',
                'Player lost!'
            );
            $money = $players->pBet;
            if ($session->get("loss")) {
                $loss = $session->get("loss");
                $money += $loss;
            }
            $session->set("loss", $money);
        }
        /*$this->addFlash(
            'notice',
            'Battle'
        );*/
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route to start new round.
     */
    #[Route("/proj/re", name: "projrestart")]
    public function projRe(
        SessionInterface $session
    ): Response {
        $funds = 5000;
        if (null !== $session->get("gain")) {
            $funds += $session->get("gain");
        }
        if (null !== $session->get("loss")) {
            $funds -= $session->get("loss");
        }
        if ($funds < 200) {
            $this->addFlash(
                'warning',
                'Not enough money!'
            );
            return $this->redirectToRoute('projreset');
        }
        $round = 0;
        $session->set("currentRound", $round);
        $time = 0;
        $session->set("roundPart", $time);
        $session->set("pokerDeck", null);
        $session->set("playerHand", null);
        $session->set("computerHand", null);
        $session->set("playerFunds", null);
        $session->set("playerPot", null);
        $session->set("computerPot", null);
        //will need to reset the pot somehow
        //then I need to ensure the starting funds for player are changed
        //and if not enough money for a round
        //put flash message about that and boot back to restart with new
        //username
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route for if the computer folds.
     */
    #[Route("/proj/win", name: "projwin")]
    public function projEz(
        SessionInterface $session
    ): Response {
        $session->set("currentRound", 0);
        $session->set("roundPart", 3);
        $money = 0;
        $players = new Players();
        $players->pBet = $session->get("playerPot");
        $players->cBet = $session->get("computerPot");
        $this->addFlash(
            'notice',
            'Player won!'
        );
        $money = $players->cBet;
        if ($session->get("gain")) {
            $gain = $session->get("gain");
            $money += $gain;
        }
        $session->set("gain", $money);
        return $this->redirectToRoute('projstate');
    }
    /**
     * Route for if the player folds.
     */
    #[Route("/proj/loss", name: "projloss")]
    public function projGitGud(
        SessionInterface $session
    ): Response {
        $session->set("currentRound", 0);
        $session->set("roundPart", 3);
        $money = 0;
        $players = new Players();
        $players->pBet = $session->get("playerPot");
        $players->cBet = $session->get("computerPot");
        $this->addFlash(
            'warning',
            'Player lost!'
        );
        $money = $players->pBet;
        if ($session->get("loss")) {
            $loss = $session->get("loss");
            $money += $loss;
        }
        $session->set("loss", $money);
        return $this->redirectToRoute('projstate');
    }
}
