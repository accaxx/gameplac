<?php
namespace App\Http\Controllers\Othello;

use App\Http\Controllers\Controller;
use App\Http\Requests\Othello\OthelloRequest;
use App\Repositories\Othello\GetCommonData;
use App\Usecases\Othello\ChangeLeftLine;
use App\Usecases\Othello\ChangeRightLine;
use App\Usecases\Othello\ChangeUpLine;
use App\Usecases\Othello\ChangeDownLine;
use App\Usecases\Othello\ResetGame;
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
        switch (true) {
            case (new ChangeUpLine($this->key, $this->now_category, $this->all_othello))->changeUp():
            case (new ChangeDownLine($this->key, $this->now_category, $this->all_othello))->changeDown():
            case (new ChangeRightLine($this->key, $this->now_category, $this->all_othello))->changeRight():
            case (new ChangeLeftLine($this->key, $this->now_category, $this->all_othello))->changeLeft():
                return true;
            default:
                return false;
        }
    }

    private function getWinnerCategory()
    {
        return array_first(GetCommonData::getWinnerCategory());
    }
}
