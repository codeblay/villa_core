<?php

namespace App\Models;

use App\MyConst;
use App\Services\Verification\VerificationService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Buyer extends Authenticatable
{
    use HasFactory, HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    // Relation
    
    function transactions() : HasMany {
        return $this->hasMany(Transaction::class);
    }
    
    function transactionsSuccess() : HasMany {
        return $this->hasMany(Transaction::class)->where('status', Transaction::STATUS_SUCCESS);
    }
    
    function ratings() : HasMany {
        return $this->hasMany(VillaRating::class);
    }

    // End Relation

    function password() : Attribute {
        return Attribute::make(
            set: fn (string $pw) => Hash::make($pw),
        );
    }

    function getAgeAttribute() : int {
        return Carbon::parse($this->birth_date)->age;
    }

    function getGenderLabelAttribute() : string {
        return match ($this->gender) {
            MyConst::GENDER_MALE    => 'Laki-laki',
            MyConst::GENDER_FEMALE  => 'Perempuan',
        };
    }

    function getLinkVerificationAttribute() : string {
        return route('verification', ['token' => VerificationService::generateTokenVerification($this)]);
    }

    function getIsVerifiedAttribute() : bool {
        return !is_null($this->email_verified_at);
    }
}
