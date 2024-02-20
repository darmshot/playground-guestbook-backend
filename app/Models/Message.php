<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'text',
        'answer',
    ];

    protected $casts = [
        'user_id' => 'int',
    ];

    protected $perPage = 6;

    /*-------------------------------------------------
     *  Relations
     * ------------------------------------------------
     */

    public function author(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
