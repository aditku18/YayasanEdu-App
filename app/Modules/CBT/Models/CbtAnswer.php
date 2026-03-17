<?php

namespace App\Modules\CBT\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CbtAnswer extends Model
{
    use SoftDeletes;

    protected $table = 'cbt_answers';
    
    protected $fillable = [
        'question_id',
        'answer_text',
        'is_correct',
        'order_index',
        'match_item_id'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'order_index' => 'integer'
    ];

    /**
     * Get the question that owns the answer.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(CbtQuestion::class, 'question_id');
    }

    /**
     * Get the matched answer (for matching questions).
     */
    public function matchItem(): BelongsTo
    {
        return $this->belongsTo(CbtAnswer::class, 'match_item_id');
    }

    /**
     * Scope a query to only include correct answers.
     */
    public function scopeCorrect($query)
    {
        return $query->where('is_correct', true);
    }

    /**
     * Scope a query to filter by question.
     */
    public function scopeForQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }
}
