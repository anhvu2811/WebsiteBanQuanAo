<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tbl_user';
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    const ROLE_CUSTOMER = 0;
    const ROLE_SELLER = 1;
    Const ROLE_ADMIN = 2;

    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isSeller()
    {
        return $this->role === self::ROLE_SELLER;
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
