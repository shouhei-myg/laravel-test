<?php

namespace App\Http\Requests;

use App\Models\Answer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
     {
         return [
             'question_id' => Rule::exists('question_items')->where(function ($query) { // question_id と question_item_id のペアが正しいかチェック

                 return $query->where('id', $this->question_item_id);

             }),
             'question_item_id' => function ($attribute, $value, $fail) { // 投票済みかどうかチェック

                 $user_id = auth()->id();
                 $exists = Answer::where('question_id', $this->question_id)
                     ->where('user_id', $user_id)
                     ->exists();

                 if ($exists === true) {

                     $fail('すでに投票済みです');

                 }

             },
         ];
     }
}
