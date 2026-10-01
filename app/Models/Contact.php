<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'notes',
        'ip_address',
    ];

    /**
     * Scope for unread messages.
     */
    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }
}
