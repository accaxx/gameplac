<?php

namespace App\Http\Controllers;

use App\Models\Play as PlayModel;
use App\Http\Requests\Create as CreateRequest;

class PlayController extends Controller
{
    private $play_model;
    private $now_count; // 今何番目の入力か
    private $now_category; // 今○か☓か 0は☓、1は○
    private $key; // キー
    private $key_digit_one; // キーの1桁目
    private $key_digit_two; // キーの2桁目
    private $response_message;

    const MARUBATSU = [
        0 => '×',
        1 => '◯'
    ];
    const KEY = [
        11, 12, 13,
        21, 22, 23,
        31, 32, 33,
    ];

    public function __construct(PlayModel $play_model)
    {
        $this->play_model = $play_model;
        $this->now_count = $this->play_model->all()->count() + 1;
        $this->now_category = $this->now_count%2;
    }

    /**
     * Viewを表示
     *
     * @return mixed
     */
    public function index()
    {
        $all = $this->setAllKey($this->play_model->all()->pluck('category', 'key'));

        return view('index')->with([
            'now_count' => $this->now_count,
            'now_category' => $this->now_category,
            'all' => $all,
        ]);
    }

    /**
     * 入力
     *
     * @param CreateRequest $request
     * @return mixed
     */
    public function create(CreateRequest $request)
    {
        $input = $request->all();
        $this->play_model->create($input);
        if ($this->isMinResultNumberOfTimes()) {
            return redirect('/')->with('message', $this->response_message);
        };
        if ($this->isGameSet($input)) {
            return redirect('/')->with('message', $this->response_message);
        };
        return redirect('/')->with('message', $this->response_message);
    }

    /**
     * 既にセットしたキーとセットされてないキーをViewに返す
     * ※ twigでisset判断しないため(session使用へ変更すれば必要なし)
     *
     * @param $all_set_array
     * @return mixed
     */
    private function setAllKey($all_set_array)
    {
        $all = [];
        foreach (self::KEY as $key) {
            $all[$key] = $all_set_array[$key] ?? null;
        }
        return $all;
    }

    /**
     * 結果が出るmin回数を下回る場合は保存のみ
     *
     * @return bool
     */
    private function isMinResultNumberOfTimes()
    {
        if ($this->now_count > 4) {
            return false;
        }
        return true;
    }

    /**
     * ゲームセットの場合レスポンスを返す
     *
     * @param array $input
     * @return string
     */
    private function isGameSet(array $input)
    {
        // 入力キーを取得
        $this->key = $input['key'];
        $this->key_digit_one = substr($this->key, 1, 1);
        $this->key_digit_two = substr($this->key, 0, 1);

        if ($this->isAlineAllRow()) {
            $this->response_message = self::MARUBATSU[$this->now_category] . 'の勝利です!';
            return true;
        }

        if ($this->now_count == 9) {
            $this->response_message = '引き分けです！';
            return true;
        }
        return false;
    }

    /**
     * 今回は3列3行が前提
     *
     * @return bool
     */
    private function isAlineAllRow()
    {
        switch (true) {
            case $this->isAlineTate():
            case $this->isAlineYoko():
            case $this->isAlineLeftNaname():
            case $this->isAlineRightNaname():
                return true;
            default:
                return false;
        }
    }

    /**
     * 入力されたkeyに応じて行が揃っているかチェック
     */
    private function isAlineTate()
    {
        // 一番上ではない場合のみ 上のマスチェック
        if ($this->key_digit_two != 1) {
            for ($i = 1; $this->key_digit_two - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two - $i . $this->key_digit_one))) {
                    return false;
                };
            }
        }

        // 一番下ではない場合のみ 下のマスチェック
        if ($this->key_digit_two != 3) {
            for ($i = 1; $this->key_digit_two + $i < 4; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two + $i . $this->key_digit_one))) {
                    return false;
                };
            }
        }
        return true;
    }

    /**
     * 入力されたkeyに応じて列が揃っているかチェック
     */
    private function isAlineYoko()
    {
        // 一番左ではない場合のみ 左のマスチェック
        if ($this->key_digit_one != 1) {
            for ($i = 1; $this->key_digit_one - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two . $this->key_digit_one - $i))) {
                    return false;
                };
            }
        }

        // 一番右ではない場合のみ 右のマスチェック
        if ($this->key_digit_one != 3) {
            for ($i = 1; $this->key_digit_one + $i < 4; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two . $this->key_digit_one + $i))) {
                    return false;
                };
            }
        }
        return true;
    }

    /**
     * 入力されたkeyに応じて左斜めが揃っているかチェック
     */
    private function isAlineLeftNaname()
    {
        // 左斜めなので キーが1桁と2桁で同じじゃない場合は確認不要
        if ($this->key_digit_two != $this->key_digit_one) {
            return false;
        }

        if ($this->key_digit_two != 1 && $this->key_digit_one != 1) {
            for ($i = 1; $this->key_digit_two - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two - $i . $this->key_digit_one - $i))) {
                    return false;
                };
            }
        }

        if ($this->key_digit_two != 3 && $this->key_digit_one != 3) {
            for ($i = 1; $this->key_digit_two - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two + $i . $this->key_digit_one + $i))) {
                    return false;
                };
            }
        }
        return true;
    }

    /**
     * 入力されたkeyに応じて右斜めが揃っているかチェック
     */
    private function isAlineRightNaname()
    {
        //　右斜めの定義　(合計1+3=4)  // 後でやる
        if (!in_array($this->key, [13, 22, 31])) {
            return false;
        }

        // 一番上ではない場合のみ 右上のマスチェック
        if ($this->key_digit_two != 13) {
            for ($i = 1; $this->key_digit_two - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two - $i . $this->key_digit_one + $i))) {
                    return false;
                };
            }
        }
        if ($this->key_digit_two != 31) {
            for ($i = 1; $this->key_digit_one - $i > 0; $i++) {
                if ($this->isCategoryNullOrDifferent($this->getCategoryByKey($this->key_digit_two + $i . $this->key_digit_one - $i))) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 引数のカテゴリがNULL もしくは 入力したカテゴリと違うカテゴリか否か
     *
     * @param $category
     * @return bool
     */
    private function isCategoryNullOrDifferent($category)
    {
        if ($category === null) {
            return true;
        }

        if (!$this->isSameCategoryByNowCategory($category)) {
            return true;
        }
        return false;
    }

    /**
     * 入力したカテゴリと、引数のカテゴリが一致しているか否か
     *
     * @param $category
     * @return bool
     */
    private function isSameCategoryByNowCategory($category)
    {
        return $category == $this->now_category;
    }

    /**
     * キーから入力済みカテゴリ取得する
     *
     * @param $key
     * @return null
     */
    private function getCategoryByKey($key)
    {
        return $this->play_model->getCategoryByKey($key);
    }
}