<?php

namespace App\Models;

use App\Enum\UserRoleEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

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
        'email_verified_at',
        'phone',
        'identification',
        'birthday',
        'role',
        'password',
        'terms_conditions',
        'privacy_policies',
        'city_id',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthday'          => 'date:Y-m-d',
        'email_verified_at' => 'datetime',
        'terms_conditions'  => 'boolean',
        'privacy_policies'  => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'age',
    ];

    /**
     * Set the user's password.
     *
     * @param  string  $pass
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::needsRehash($password) ? Hash::make($password) : $password;
    }

    /**
     * Get the user's age.
     *
     * @param  string  $value
     * @return int
     */
    public function getAgeAttribute($value)
    {
        return Carbon::parse($this->attributes['birthday'])->age;
    }

    /**
     * Get if the user is admin.
     *
     * @param  string  $value
     * @return bool
     */
    public function getIsAdminAttribute($value)
    {
        return $this->attributes['role'] === UserRoleEnum::ADMIN;
    }

    /**
     * Get the city that owns the user.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the emails for the user.
     */
    public function emails(): HasMany
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Function to remove user access tokens
     */
    public function tokenDelete(): void
    {
        $this->tokens->each(function($token, $key) {
            $token->delete();
        });
    }
}
