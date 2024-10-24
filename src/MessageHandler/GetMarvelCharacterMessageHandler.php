<?php

namespace App\MessageHandler;

use App\Entity\Character;
use App\ImageGenerator\Pixelate;
use App\Message\GetMarvelCharacterMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class GetMarvelCharacterMessageHandler
{
    public function __construct(
        private HttpClientInterface $client,
        #[Autowire(env: 'MARVEL_API_PUBLIC_KEY')] private string $publicKey,
        #[Autowire(env: 'MARVEL_API_PRIVATE_KEY')] private string $privateKey,
        private EntityManagerInterface $entityManager,
        private Pixelate $pixelate
    ) {
    }

    public function __invoke(GetMarvelCharacterMessage $message): void
    {
        do {
            $response = $this->getMarvelCharacter();
        } while (str_contains($response['data']['results'][0]['thumbnail']['path'], 'image_not_available'));
        
        $character = new Character(
            name: $response['data']['results'][0]['name'],
            description: $response['data']['results'][0]['description'],
            date: new \DateTimeImmutable(),
            comics: $response['data']['results'][0]['comics']['items'],
        );

        $this->pixelate->generate($response['data']['results'][0]['thumbnail']['path'] . '.' . $response['data']['results'][0]['thumbnail']['extension']);

        $this->entityManager->persist($character);
        $this->entityManager->flush();
    }

    private function getMarvelCharacter(): array
    {
        return $this->client->request('GET', 'https://gateway.marvel.com/v1/public/characters', [
            'query' => [
                'apikey' => $this->publicKey,
                'limit' => 1,
                'offset' => random_int(0, 1564),
                'ts' => time(),
                'hash' => md5(time() . $this->privateKey . $this->publicKey),
            ],
        ])->toArray();
    }
}
