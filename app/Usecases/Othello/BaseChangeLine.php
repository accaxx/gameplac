<?php
namespace App\Usecases\Othello;

class BaseChangeLine
{
    protected $all_othello;
    protected $now_category;
    protected $key;
    protected $key_digit_one;
    protected $key_digit_two;

    /**
     * BaseChangeLine constructor.
     * @param int $key
     * @param int $now_category
     * @param array $all_othello
     */
    public function __construct(int $key, int $now_category, array $all_othello)
    {
        $this->setKeyByDigit($key);
        $this->now_category = $now_category;
        $this->all_othello = $all_othello;
    }

    /**
     * 比較したいカテゴリが今のカテゴリと一致してるか否か
     *
     * @param $category
     * @return bool
     */
    protected function isSameCategoryWithNowCategory($category)
    {
        return $this->now_category === $category;
    }

    /**
     * 隣のカテゴリにより、その行を変更するか否か
     *
     * @param $next_category
     * @return bool
     */
    protected function isChangeByNextCategory($next_category)
    {
        if (is_null($next_category)) {
            return false;
        }

        if ($this->isSameCategoryWithNowCategory($next_category)) {
            return false;
        };

        return true;
    }

    /**
     * キーと、キーの1桁と2桁の数字を分割してプロパティにセットする
     *
     * @param int $key
     */
    private function setKeyByDigit(int $key)
    {
        $this->key = $key;
        $this->key_digit_one = substr($this->key, 1, 1);
        $this->key_digit_two = substr($this->key, 0, 1);
    }
}