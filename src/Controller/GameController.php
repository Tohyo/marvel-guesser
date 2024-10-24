<?php

namespace App\Controller;

use App\Message\GetMarvelCharacterMessage;
use App\Repository\CharacterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private CharacterRepository $characterRepository
    ) {
    }

    #[Route('/', name: self::class)]
    public function __invoke(): Response
    {
        $this->messageBus->dispatch(new GetMarvelCharacterMessage());

        return $this->render('game/index.html.twig', [
            'character' => $this->characterRepository->findCharacterOfTheDay()
        ]);
    }
}
