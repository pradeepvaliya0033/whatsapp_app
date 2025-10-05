<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'facebook_id',
        'facebook_name',
        'facebook_email',
        'facebook_picture',
        'facebook_access_token',
        'facebook_token_expires_at',
        'facebook_pages',
        'facebook_selected_page_id',
        'facebook_selected_page_name',
        'facebook_selected_page_token',
        'facebook_connected_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'facebook_access_token',
        'facebook_selected_page_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'facebook_token_expires_at' => 'datetime',
        'facebook_connected_at' => 'datetime',
        'facebook_pages' => 'array',
    ];
}
