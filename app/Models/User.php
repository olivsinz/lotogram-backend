<?php

namespace App\Models;

use App\Enum\UserType;
use App\Models\Notification;
use App\Traits\Model\HasUuid;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserInterfaceSetting;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, HasUuid;

    //public $cacheFor = 3600; // cache time, in seconds
    //protected static $flushCacheOnUpdate = true;

    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'password_change_required',
        'password_changed_at',
        'is_active',
        'has_tfa',
        'tfa_method',
        'tfa_secret',
        'title_id',
        'group_id',
        'language',
        'birth_date',
        'phone',
        'national_id',
        'type'
    ];

    protected $hidden = [
        'password',
        'tfa_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'is_active' => 'boolean',
        'has_tfa' => 'boolean',
        'tfa_secret' => 'encrypted',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function userInterfaceSetting()
    {
        return $this->hasOne(UserInterfaceSetting::class);
    }

    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'group_id');
    }

    public function methods()
    {
        return $this->belongsToMany(Method::class, 'user_methods')->using(UserMethod::class);
    }

    public function ipAddresses()
    {
        return $this->hasMany(UserIpWhitelist::class);
    }

    public function sites()
    {
        return $this->belongsToMany(Site::class, 'user_sites')->using(UserSite::class);
    }

    public function bonuses()
    {
        return $this->belongsToMany(Bonus::class, 'bonus_users');
    }

    public function scopeNotAwardedBonus($query, $bonusId)
    {
        return $query->whereDoesntHave('bonuses', function ($query) use ($bonusId) {
            $query->where('bonuses.id', $bonusId);
        });
    }

    public function scopeFilterByEmail(Builder $query, ?string $email): void
    {
        $email !== null
            && $query->where('email', 'ILIKE', '%' . $email . '%');
    }

    public function scopeFilterByFirstName(Builder $query, ?string $firstName): void
    {
        $firstName !== null
            && $query->where('first_name', 'ILIKE', '%' . $firstName . '%');
    }

    public function scopeFilterByLastName(Builder $query, ?string $lastName): void
    {
        $lastName !== null
            && $query->where('last_name', 'ILIKE', '%' . $lastName . '%');
    }

    public function scopeFilterByStatus(Builder $query, ?bool $status): void
    {
        $status !== null
            && $query->where('is_active', $status);
    }

    public function scopeFilterByTitle(Builder $query, ?string $titleId): void
    {
        $titleId !== null
            && $query->where('title_id', $titleId);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeEmail(Builder $query, string $email): void
    {
        $query->where('email', $email);
    }

    public function scopeBot(Builder $query): void
    {
        $query->where('type', UserType::Bot->value);
    }

    public function scopeUser(Builder $query): void
    {
        $query->where('type', UserType::User->value);
    }

    public function scopeEmailVerified(Builder $query): void
    {
        $query->whereNotNull('email_verified_at');
    }

    public function scopeFilterByCreatedAt(Builder $query, array $date): void
    {
        count($date) == 2
            && $query->whereBetween('created_at', $date);
    }


}
