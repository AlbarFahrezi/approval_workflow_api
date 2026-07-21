<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_request_id',
        'user_id',
        'from_status',
        'to_status',
        'comment',
    ];

    /**
     * History milik satu Request
     */
    public function approvalRequest()
    {
        return $this->belongsTo(ApprovalRequest::class);
    }

    /**
     * History dibuat oleh User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}