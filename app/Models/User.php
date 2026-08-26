<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi menggunakan mass assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
    ];

    /**
     * Kolom yang tidak ditampilkan dalam JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute Casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Attribute yang otomatis dikirim ke JSON.
     */
    protected $appends = [
        'avatar_url',
    ];

    /**
     * URL avatar user.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : null;
    }

    /**
     * User memiliki banyak Approval Request.
     */
    public function approvalRequests()
    {
        return $this->hasMany(ApprovalRequest::class);
    }

    /**
     * User memiliki banyak Approval History.
     */
    public function approvalHistories()
    {
        return $this->hasMany(ApprovalHistory::class);
    }
}