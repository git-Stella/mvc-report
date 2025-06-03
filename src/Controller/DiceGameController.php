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

/**
 * Controller class for dice game.
 */
class DiceGameController extends AbstractController
{
    /**
     * Landing page for pig game.
     */
    #[Route("/game/pig", name: "pig_start")]
    public function home(): Response
    {
        return $this->render('pig/home.html.twig');
    }
    /**
     * Test rolling dice.
     */
    #[Route("/game/pig/test/roll", name: "test_roll_dice")]
    public function testRollDice(): Response
    {
        $die = new Dice();

        $data = [
            "dice" => $die->roll(),
            "diceString" => $die->getAsString(),
        ];

        return $this->render('pig/test/roll.html.twig', $data);
    }
    /**
     * Page to initialize the pig game.
     */
    #[Route("/game/pig/init", name: "pig_init_get", methods: ['GET'])]
    public function init(): Response
    {
        return $this->render('pig/init.html.twig');
    }
    /**
     * Route that initializes the pig game if that option was selected.
     */
    #[Route("/game/pig/init", name: "pig_init_post", methods: ['POST'])]
    public function initCallback(
        Request $request,
        SessionInterface $session
    ): Response {
        $numDice = $request->request->get('num_dices');

        $hand = new DiceHand();
        for ($i = 1; $i <= $numDice; $i++) {
            $hand->add(new DiceGraphic());
        }
        $hand->roll();

        $session->set("pig_dicehand", $hand);

        $session->set("pig_dices", $numDice);
        $session->set("pig_round", 0);
        $session->set("pig_total", 0);

        return $this->redirectToRoute('pig_play');
    }
    /**
     * Route to play pig game.
     */
    #[Route("/game/pig/play", name: "pig_play", methods: ['GET'])]
    public function play(
        SessionInterface $session
    ): Response {
        $dicehand = $session->get("pig_dicehand");

        $data = [
            "pigDices" => $session->get("pig_dices"),
            "pigRound" => $session->get("pig_round"),
            "pigTotal" => $session->get("pig_total"),
            "diceValues" => $dicehand->getString()
        ];

        return $this->render('pig/play.html.twig', $data);
    }
    /**
     * Route to roll dice for pig game.
     */
    #[Route("/game/pig/roll", name: "pig_roll", methods: ['POST'])]
    public function roll(
        SessionInterface $session
    ): Response {
        $hand = $session->get("pig_dicehand");
        $hand->roll();

        $roundTotal = $session->get("pig_round");
        $round = 0;
        $values = $hand->getValues();
        foreach ($values as $value) {
            if ($value === 1) {
                $this->addFlash(
                    'warning',
                    'You got a 1 and you lost the round points!'
                );
                $round = 0;
                $roundTotal = 0;
                break;
            }
            $round += $value;
        }

        $session->set("pig_round", $roundTotal + $round);

        return $this->redirectToRoute('pig_play');
    }
    /**
     * Route to save pig game stats.
     */
    #[Route("/game/pig/save", name: "pig_save", methods: ['POST'])]
    public function save(
        SessionInterface $session
    ): Response {
        $roundTotal = $session->get("pig_round");
        $gameTotal = $session->get("pig_total");

        $session->set("pig_round", 0);
        $session->set("pig_total", $roundTotal + $gameTotal);

        $this->addFlash(
            'notice',
            'Your round was saved to the total!'
        );

        return $this->redirectToRoute('pig_play');
    }
}
