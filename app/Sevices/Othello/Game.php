<?php
namespace App\Services\Othello;

use App\Repositories\Othello\Othello as OthelloRepository;
use App\Repositories\Othello\OthelloGame as OthelloGameRepository;

class Game
{
    private $othello_repository;

    public function __construct(OthelloRepository $othello_repository)
    {
        $this->othello_repository = $othello_repository;
    }

    public static function pass()
    {
        OthelloGameRepository::addPassCount();
    }

    public static function reset()
    {
        OthelloGameRepository::createNextGame();
        OthelloRepository::updateCategoryToDefault();
    }
}

