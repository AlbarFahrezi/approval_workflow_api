<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalRequest extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'submitted_at',
        'approved_at',
        'rejected_at',
    ];


    protected $casts = [
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];


    /**
     * Request dimiliki oleh satu User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * Request memiliki banyak history approval
     */
    public function histories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}