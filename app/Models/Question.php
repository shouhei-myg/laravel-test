<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    // Relationship
    public function items()
    {
        return $this->hasMany(QuestionItem::class, 'question_id', 'id');
    }
}
