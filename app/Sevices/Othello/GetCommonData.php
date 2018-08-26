<?php
namespace App\Services\Othello;

use App\Repositories\Othello\Othello as OthelloRepository;

class GetCommonData
{
    private $othello_repository;

    public function __construct(OthelloRepository $othello_repository)
    {
        $this->othello_repository = $othello_repository;
    }

    public static function getAllKeyAndCategory()
    {
        return OthelloRepository::getAllKeyAndCategory();
    }

    public static function getWinnerCategory()
    {
        return OthelloRepository::getMostCommonCategory();
    }

    public static function getNowCount()
    {
        return OthelloRepository::getCountCategoryInputed() - 4 + 1; // デフォルト値と今の順番のため
    }
}

