<?php
namespace App\Usecases\Othello;

use App\Repositories\Othello\Othello as OthelloRepository;

class ChangeLeftLine extends BaseChangeLine
{
    private $next_compare_category;
    private $change_start_key_digit_one = null;

    public function __construct(int $key, int $now_category, array $all_othello)
    {
        parent::__construct($key, $now_category, $all_othello);
        $this->next_compare_category = $this->key_digit_two . $this->key_digit_one - 1;
    }

    /**
     * 自分より上のマスを変更するか否か
     *
     * @return bool
     */
    public function changeLeft()
    {
        if (!$this->isChangeLeft()) {
            return false;
        }

        // 2マス目以降
        for ($i = 2; $this->key_digit_one - $i > 0; $i++) {
            $check_key = $this->key_digit_two . $this->key_digit_one - $i;
            if (is_null($this->all_othello[$check_key])) {
                return false;
            }
            if ($this->isSameCategoryWithNowCategory($this->all_othello[$check_key])) {
                $this->change_start_key_digit_one = substr($check_key, 1, 1);
                break;
            };
            continue;
        }

        // 今のキーからchange_start_keyまでのカテゴリを全て変える // start_key_digit_oneから自分の一つ左まで
        for ($i = 1; $this->change_start_key_digit_one + $i <= $this->key_digit_one; $i++) {
            OthelloRepository::updateCategoryByKeyAndCategory($this->key_digit_two . $this->change_start_key_digit_one + $i, $this->now_category);
        }
        return true;
    }

    /**
     * 変更が必要ないか否か
     *
     * @return bool
     */
    private function isChangeLeft()
    {
        if ($this->key_digit_one <= 2) {
            return false;
        }

        if (!$this->isChangeByNextCategory($this->all_othello[$this->next_compare_category])) {
            return false;
        };

        return true;
    }
}
