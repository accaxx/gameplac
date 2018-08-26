<?php
namespace App\Usecases\Othello;

use App\Repositories\Othello\Othello as OthelloRepository;

class ResetGame
{
    private $othello_repository;

    public function __construct(OthelloRepository $othello_repository)
    {
        $this->othello_repository = $othello_repository;
    }

    public static function reset()
    {
        return OthelloRepository::updateCategoryToDefault();
    }
}

