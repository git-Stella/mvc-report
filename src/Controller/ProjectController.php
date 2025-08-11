<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Card\Card;
use App\Card\DeckOfCards;
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
    ): Response
    {
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
    /*#[Route("/proj/draw", name: "projdraw", methods: ['POST'])]
    public function projDraw(): Response
    {
        return $this->redirect('test');
    }*/
    //make some sort of gamestate route
    //on it have a form for raising bet
    //when form submitted turn ends
    //by default bet is same as before, cannot be lowered (use min)
    //can also fold??
    //can select card to drop from hand via another form for drawing new
    /**
     * Test page.
     */
    #[Route("/proj/test", name: "projtest")]
    public function projTest(
        SessionInterface $session
    ): Response
    {
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
    //have the computer discard cards if not meeting certain points threshold
    //hey hey
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
    #[Route("/proj/gamestate", name: "projstate")]
    public function projGamestate(
        SessionInterface $session
    ): Response {
        $deck = new DeckOfCards();
        $deck->shuffle();
        $pHand = [];
        $cHand = [];
        if (null !== $session->get("playerHand")) {
            $pHand = $session->get("playerHand");
        }
        if (null !== $session->get("computerHand")) {
            $cHand = $session->get("computerHand");
        }
        //if session not null on deck apply it...
        if (null !== $session->get("deck")) {
            $deck->swapShuffle($session->get("deck"));
        }
        $temp1 = $deck->draw(5);
        foreach ($temp1 as $card) {
            $suit = $card->getColor();
            $val = $card->getKingdom();
            $pHand[] = '[' . $suit . $val . ']';
            //$pHand[] = $card;
        }
        $data = [
            "score" => 5,
            "time" => 0,
            "deck" => count($deck->deck),
            "playerHand" => $pHand,
            "computerHand" => $cHand
        ];
        return $this->render('proj/gamestate.html.twig', $data);
    }
    #[Route("/proj/round", name: "projround", methods: ['POST'])]
    public function projRound(
        SessionInterface $session
    ): Response {
        $this->addFlash(
            'notice',
            'Round'
        );
        return $this->redirectToRoute('projstate');
    }
    #[Route("/proj/draw", name: "projdraw", methods: ['POST'])]
    public function projDraw(
        SessionInterface $session
    ): Response {
        $this->addFlash(
            'notice',
            'Draw'
        );
        return $this->redirectToRoute('projstate');
    }
}
