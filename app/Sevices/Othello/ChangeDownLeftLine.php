<?php
namespace App\Services\Othello;

use App\Repositories\Othello\Othello as OthelloRepository;

class ChangeDownLeftLine extends BaseChangeLine
{
    private $next_compare_category;

    public function __construct(int $key, int $now_category, array $all_othello)
    {
        parent::__construct($key, $now_category, $all_othello);
        $this->next_compare_category = $this->key_digit_two - 1 . $this->key_digit_one + 1;
    }

    /**
     * 自分より上のマスを変更するか否か
     *
     * @return bool
     */
    public function changeDownLeft()
    {
        if (!$this->isChangeDownLeft()) {
            return false;
        }

        // 2マス目以降
        $change_start_key_digit_one = null;
        $change_start_key_digit_two = null;
        for ($i = 2; $this->key_digit_two - $i > 0, $this->key_digit_one + $i <= 8; $i++) {
            $check_key = $this->key_digit_two - $i . $this->key_digit_one + $i;
            if (is_null($this->all_othello[$check_key])) {
                return false;
            }
            if ($this->isSameCategoryWithNowCategory($this->all_othello[$check_key])) {
                $change_start_key_digit_one = substr($check_key, 1, 1);
                $change_start_key_digit_two = substr($check_key, 0, 1);
                break;
            };
            if ($this->key_digit_two - $i === 1 || $this->key_digit_one + $i === 8) {
                return false; // 端まで別の色の場合、ひっくり返さない
            }
            continue;
        }

        // 今のキーからchange_start_keyまでのカテゴリを全て変える // start_key_digit_twoから自分の一つ上までのぼる
        for ($i = 1; $change_start_key_digit_two + $i <= $this->key_digit_two, $change_start_key_digit_one - $i >= $this->key_digit_one; $i++) {
            OthelloRepository::updateCategoryByKeyAndCategory($change_start_key_digit_two + $i . $change_start_key_digit_one - $i, $this->now_category);
        }
        return true;
    }

    /**
     * 変更が必要ないか否か
     *
     * @return bool
     */
    private function isChangeDownLeft()
    {
        if ($this->key_digit_two <= 2 || $this->key_digit_one >= 7) {
            return false;
        }

        if (!$this->isChangeByNextCategory($this->all_othello[$this->next_compare_category])) {
            return false;
        };

        return true;
    }
}
