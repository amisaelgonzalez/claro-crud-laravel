<?php

namespace App\Models;

use App\Enum\UserRoleEnum;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the emails for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function emails()
    {
        return $this->hasMany(Email::class);
    }

    /**
     * Scope for general filtering of names, phone, email or identification
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $keywords
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverallSearch($query, $keywords)
    {
        return $query->when($keywords, function ($query, $value) {
                    $query->orWhere('name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('identification', 'like', "%{$value}%");
                });
    }

    /**
     * Scope to sort records.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $column
     * @param  string  $direction
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCustomOrderBy($query, $column, $direction)
    {
        $sortableColumns = [
            'id',
            'name',
            'email',
            'phone',
            'identification',
            'birthday',
        ];

        if (! in_array($column, $sortableColumns)) {
            $column = 'id';
        }

        if ($direction !== 'asc' && $direction !== 'desc') {
            $direction = 'asc';
        }

        return $query->orderBy($column, $direction);
    }

    /**
     * Scope to filter by user role.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRoleUser($query)
    {
        return $query->where('role', UserRoleEnum::USER);
    }

    /**
     * Function to remove user access tokens
     *
     * @return void
     */
    public function tokenDelete()
    {
        $this->tokens->each(function($token, $key) {
            $token->delete();
        });
    }
}
