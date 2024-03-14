<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'message',
    ];


    public function scopeFiltered(Builder $query, ?string $status, ?string $from, ?string $to): void
    {
        $query->when($status, function ($q) use ($status) {
            return $q->where('status', $status);
        });

        $query->when($from, function ($q) use ($from) {
            return $q->where('created_at', '>', $from);
        });

        $query->when($to, function ($q) use ($to) {
            return $q->where('created_at', '<', $to);
        });

    }
}
