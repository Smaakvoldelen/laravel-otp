<?php

namespace Smaakvoldelen\Otp\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property ?Carbon $expires_at
 * @property ?Carbon $validated_at
 */
class Otp extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identifier',
        'token',
        'expires_at',
        'validated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'validated_at' => 'datetime',
    ];

    /**
     * Determine if the OTP is valid.
     */
    public function valid(): Attribute
    {
        return Attribute::get(fn () => $this->expires_at->isFuture() && $this->validated_at === null);
    }
}
