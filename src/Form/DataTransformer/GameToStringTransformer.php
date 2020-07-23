<?php


namespace App\Form\DataTransformer;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class GameToStringTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (game) to a string (name).
     * @param Game|null $game
     * @return string
     * @inheritDoc
     */
    public function transform($game)
    {
        if (null === $game) {
            return '';
        }

        return $game->getName();
    }

    /**
     * Transforms a string (name) to a object (Game).
     * @inheritDoc
     */
    public function reverseTransform($gameName)
    {

        $game = $this->entityManager
            ->getRepository(Game::class)
            ->findOneBy(['name' => $gameName])
        ;

        if (null === $game) {
            $game = new Game();
            $game->setName($gameName);
        }

        return $game;
    }
}