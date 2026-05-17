<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

#[Fillable(['user_id', 'title', 'description', 'status', 'due_date'])]
class Task extends Model
{
    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Scope: Search tasks by title or description
     */
    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter tasks by status
     */
    public function scopeFilterByStatus(Builder $query, ?string $status = null): Builder
    {
        if (!$status) {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * Scope: Sort tasks by field
     */
    public function scopeSortBy(Builder $query, string $field = 'created_at', string $direction = 'desc'): Builder
    {
        $allowedFields = ['created_at', 'updated_at', 'title', 'status', 'due_date'];

        if (!in_array($field, $allowedFields)) {
            $field = 'created_at';
        }

        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $query->orderBy($field, $direction);
    }
}
