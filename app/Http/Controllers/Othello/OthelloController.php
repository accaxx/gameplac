<?php
namespace App\Http\Controllers\Othello;

use App\Http\Controllers\Controller;
use App\Http\Requests\Othello\OthelloRequest;
use App\Models\Othello\Othello as OthelloModel;

class OthelloController extends Controller
{
    private $othello_model;
    private $now_count;
    private $now_category;
    private $key;
    private $key_digit_one;
    private $key_digit_two;
    private $message;

    const BLACK_OR_WHITE = [
        0 => 'WHITE',
        1 => 'BLACK'
    ];

    /**
     * OthelloController constructor.
     *
     * @param OthelloModel $othello_model
     */
    public function __construct(OthelloModel $othello_model)
    {
        $this->othello_model = $othello_model;
        $this->now_count = $this->othello_model->all()->count() + 1;
        $this->now_category = $this->now_count % 2;
    }

    /**
     * ゲーム画面表示
     *
     * @return mixed
     */
    public function index()
    {
        return view('othello')->with(['all' => $this->othello_model->all()->pluck('category', 'key')]);
    }

    /**
     * 入力
     *
     * @param OthelloRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function input(OthelloRequest $request)
    {
        $this->setKeyByDigit($request->all());

        if (!$this->updateOtherKeyCategory()) {
            $this->message = "そのマスに置くことはできません。";
        }
        return redirect('/othello')->with('message', $this->message);
    }

    /**
     * 他のマスを更新するか否か
     *
     * @return bool
     */
    private function updateOtherKeyCategory()
    {
        if (!$this->changeTateRow()
            && !$this->changeYokoRow()
            && !$this->changeRightNanameRow()
            && !$this->changeLeftNanameRow()
        ) {
            return false;
        }
        return true;
    }

    /**
     * キーと、キーの1桁と2桁の数字を分割してプロパティにセットする
     *
     * @param array $input
     */
    private function setKeyByDigit(array $input)
    {
        $this->key = $input['key'];
        $this->key_digit_one = substr($this->key, 1, 1);
        $this->key_digit_two = substr($this->key, 0, 1);
    }

    private function changeTateRow()
    {
        $this->changeUp();
        $this->changeDown();
    }

    private function changeUp()
    {
        if ($this->key_digit_two <= 2) {
            return;
        }

        // 1マス目
        if ($this->isSameCategoryWithNowCategory($this->getCategoryByKey($this->key_digit_two - 1 . $this->key_digit_one))) {
            return;
        };

        // 2マス目以降
        $change_start_key_digit_two = null;
        for ($i = 2; $this->key_digit_two - $i > 0; $i++) {
            $check_key = $this->key_digit_two - $i . $this->key_digit_one;
            if ($this->isSameCategoryWithNowCategory($this->getCategoryByKey($check_key))) {
                $change_start_key_digit_two = $check_key;
                break;
            };
            continue;
        }

        // 今のキーからchange_start_keyまでのカテゴリを全て変える // start_key_digit_twoから自分の一つ上までのぼる
        for ($i = 1; $change_start_key_digit_two + $i >= $this->key_digit_two - 1; $i++ ) {
            $this->othello_model
                ->where('key', $change_start_key_digit_two + $i)
                ->update(['category' => $this->now_category]);
        }

    }

    private function changeDown()
    {
    }

    private function changeYokoRow()
    {
    }

    private function changeRightNanameRow()
    {
    }

    private function changeLeftNanameRow()
    {
    }

    private function isNochange(int $key)
    {
        $category = $this->getCategoryByKey($key);
        if ($this->isSet($category)) {
            return true;
        }
        if ($this->isSameCategoryWithNowCategory($category)) {
            return true;
        }
        return false;
    }

    private function getCategoryByKey($key)
    {
        return $this->othello_model->getCategoryByKey($key);
    }

    private function isSet($category)
    {
        return is_null($category);
    }

    private function isSameCategoryWithNowCategory($category)
    {
        return $this->now_category === $category;
    }
}
