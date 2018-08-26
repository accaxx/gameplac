<?php
namespace App\Repositories\Othello;

use App\Models\Othello\Othello as OthelloModel;

class Othello
{
    private $othello_model;

    /**
     * OthelloController constructor.
     *
     * @param OthelloModel $othello_model
     */
    public function __construct(OthelloModel $othello_model)
    {
        $this->othello_model = $othello_model;
    }

    public static function getAllKeyAndCategory()
    {
        return OthelloModel::all()->pluck('category', 'key');
    }

    public static function getCountCategoryInputed()
    {
        return OthelloModel::whereNotNull('category')->count();
    }

    public static function updateCategoryByKeyAndCategory(int $key, int $category)
    {
        return OthelloModel::where('key', $key)
            ->first()
            ->update(['category' => $category]);
    }

    public static function getMostCommonCategory()
    {
        return OthelloModel::all()->mode('category');
    }

    public static function updateCategoryToDefault()
    {
        foreach (OthelloModel::all() as $model) {
            $model->update(['category' => $model->default_category]);
        }
        return;
    }
}

