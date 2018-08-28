<?php
namespace App\Http\Controllers\Othello;

use App\Http\Controllers\Controller;
use App\Http\Requests\Othello\OthelloRequest;
use App\Services\Othello\ChangeUpLeftLine;
use App\Services\Othello\ChangeUpRightLine;
use App\Services\Othello\ChangeDownRightLine;
use App\Services\Othello\ChangeDownLeftLine;
use App\Services\Othello\GetCommonData;
use App\Services\Othello\ChangeLeftLine;
use App\Services\Othello\ChangeRightLine;
use App\Services\Othello\ChangeUpLine;
use App\Services\Othello\ChangeDownLine;
use App\Services\Othello\ResetGame;
use Illuminate\Http\Request;

class OthelloController extends Controller
{
    private $all_othello;
    private $now_count;
    private $now_category;
    private $key;

    const BLACK_OR_WHITE = [
        0 => '白',
        1 => '黒',
    ];

    /**
     * OthelloController constructor.
     */
    public function __construct()
    {
        $this->now_count = GetCommonData::getNowCount();
        $this->now_category = $this->now_count % 2;
    }

    /**
     * ゲーム画面表示
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($this->now_count > 60) {
            $request->session()->flash('message', self::BLACK_OR_WHITE[$this->getWinnerCategory()].'の勝利です！');
        }

        if (!$request->session()->has('all_othello')) {
            $request->session()->flash('all_othello', GetCommonData::getAllKeyAndCategory());
        } else {
            $request->session()->keep('all_othello');
        }

        return view('othello/index')->with('now_category', $this->now_category);
    }

    /**
     * 入力したときの挙動
     *
     * @param OthelloRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function input(OthelloRequest $request)
    {
        $this->all_othello = $request->session()->get('all_othello') ? $request->session()->get('all_othello')->all() : GetCommonData::getAllKeyAndCategory();
        $this->key = $request->key;

        if ($this->updateOtherKeyCategory()) {
            $request->session()->flash('all_othello', GetCommonData::getAllKeyAndCategory());
        } else {
            $request->session()->keep('all_othello'); // 前回入力されていた全てのマス情報をもう一度sessionにいれる
            $request->session()->flash('message', 'そのマスに置くことはできません');
        };

        return redirect('/othello');
    }

    /**
     * ゲームをリセットする
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function resetGame(Request $request)
    {
        ResetGame::reset();
        $this->all_othello = GetCommonData::getAllKeyAndCategory();
        $request->session()->flash('all_othello', $this->all_othello);

        return redirect('/othello');
    }

    /**
     * 他のマスを更新するか否か
     *
     * @return bool
     */
    private function updateOtherKeyCategory()
    {
        $is_changed = false;

        if ((new ChangeUpLine($this->key, $this->now_category, $this->all_othello))->changeUp()) {
            $is_changed = true;
        }
        if ((new ChangeDownLine($this->key, $this->now_category, $this->all_othello))->changeDown()) {
            $is_changed = true;
        }
        if ((new ChangeRightLine($this->key, $this->now_category, $this->all_othello))->changeRight()) {
            $is_changed = true;
        }
        if ((new ChangeLeftLine($this->key, $this->now_category, $this->all_othello))->changeLeft()) {
            $is_changed = true;
        }
        if ((new ChangeUpLeftLine($this->key, $this->now_category, $this->all_othello))->changeUpLeft()) {
            $is_changed = true;
        }
        if ((new ChangeUpRightLine($this->key, $this->now_category, $this->all_othello))->changeUpRight()) {
            $is_changed = true;
        }
        if ((new ChangeDownRightLine($this->key, $this->now_category, $this->all_othello))->changeDownRight()) {
            $is_changed = true;
        }
        if ((new ChangeDownLeftLine($this->key, $this->now_category, $this->all_othello))->changeDownLeft()) {
            $is_changed = true;
        }

        return $is_changed;
    }

    /**
     * コマが多いカテゴリを返す
     *
     * @return mixed
     */
    private function getWinnerCategory()
    {
        return array_first(GetCommonData::getWinnerCategory());
    }
}
