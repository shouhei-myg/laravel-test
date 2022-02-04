<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\QuestionItem;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $test_questions = [
          [
              'title' => '好きな動物は？',
              'options' => [
                  '犬',
                  '猫',
                  'パンダ',
                  'アライグマ',
                  'カモノハシ'
              ]
          ],
          [
              'title' => '行ってみたい国は？',
              'options' => [
                  'アメリカ',
                  'イギリス',
                  'フランス',
                  'ドイツ',
                  'カナダ'
              ]
          ]
      ];

      foreach ($test_questions as $test_question) {

          $question = new Question();
          $question->title = $test_question['title'];
          $question->expired_at = today()->addDays(rand(3, 7));
          $question->save();

          $test_options = $test_question['options'];

          foreach ($test_options as $test_option) {

              $question_item = new QuestionItem();
              $question_item->question_id = $question->id;
              $question_item->option = $test_option;
              $question_item->save();

          }

      }
    }
}
