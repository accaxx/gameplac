<?php
namespace App\Repositories\Othello;

use App\Models\Othello\OthelloGame as OthelloGameModel;

class OthelloGame
{
    private $othello_game_model;

    /**
     * OthelloController constructor.
     *
     * OthelloGame constructor.
     * @param OthelloGameModel $othello_game_model
     */
    public function __construct(OthelloGameModel $othello_game_model)
    {
        $this->othello_game_model = $othello_game_model;
    }

    public static function createNextGame()
    {
        return OthelloGameModel::create();
    }

    public static function getPassCount()
    {
        return OthelloGameModel::latest()->first()->pass_count;
    }

    public static function addPassCount()
    {
        $model = OthelloGameModel::latest()->first();
        $model->pass_count = $model->pass_count + 1;
        $model->save();
    }
}

