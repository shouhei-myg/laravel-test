<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionItem;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function show(Question $question)
    {
        $now = now();

        if($question->expired_at < $now) { // すでに有効期限切れの場合

            abort(404);

        }

        $question->load('items');
        $user_id = auth()->id();
        $is_polled = Answer::where('question_id', $question->id)
            ->where('user_id', $user_id)
            ->exists();

        return view('question.index')->with([
            'question' => $question,
            'is_polled' => $is_polled
        ]);
    }

    public function result(Question $question)
    {
        $question_items = QuestionItem::withCount('answers')
            ->where('question_id', $question->id)
            ->orderBy('answers_count', 'desc') // 件数で並び替え
            ->get();
        $all_count = $question_items->sum('answers_count'); // すべての投票数

        return $question_items->map(function($question_item) use($all_count) {

            $percentage = ($all_count === 0)
                ? 0
                : round($question_item->answers_count / $all_count * 100, 1); // 結果のパーセント化

            return [
                'option' => $question_item->option,
                'percentage' => sprintf('%.1f', $percentage) // .0 が消えるのでフォーマット使用
            ];

        });
    }

    public function store(QuestionRequest $request)
    {
        $answer = new Answer();
        $answer->user_id = auth()->id();
        $answer->question_id = $request->question_id;
        $answer->question_item_id = $request->question_item_id;
        $result = $answer->save();

        return ['result' => $result];
    }
}
