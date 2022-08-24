<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sorting_order',
        'title',
        'status',
    ];

    /**
     * Get sorting order.
     *
     * @return int
     */
    public static function getSortingOrder(): int
    {
        $datum = self::select('sorting_order')
            ->where('status', 'to_do')
            ->orderBy('sorting_order', 'DESC')
            ->first();

        if ($datum && $datum->sorting_order) {
            return $datum->sorting_order + 1;
        }

        return 1;
    }
}
