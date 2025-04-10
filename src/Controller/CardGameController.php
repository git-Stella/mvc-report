<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Dice\Dice;
use App\Dice\DiceGraphic;
use App\Dice\DiceHand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameController extends AbstractController
{
    #[Route("/card", name: "card_start")]
    public function home(
        SessionInterface $session
    ): Response
    {
        $session->set("hand", "hand");
        return $this->render('card/home.html.twig');
    }
    #[Route("/session", name: "card_session")]
    public function card_session(
        SessionInterface $session
    ): Response
    {
        
        $data = [
            "hand" => $session->get("hand", "empty")
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    #[Route("/session/delete", name: "card_reset")]
    public function card_reset(
        SessionInterface $session
    ): Response
    {
        $session->clear();
        $data = [
            "hand" => $session->get("hand", "empty")
        ];
        return $this->render('card/session_debug.html.twig', $data);
    }
    #[Route("/card/deck", name: "card_deck")]
    public function deck(
        SessionInterface $session
    ): Response
    {
        $session->set("hand", "hand");
        return $this->render('card/deck.html.twig');
    }
}