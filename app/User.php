<?php

namespace App;

use Illuminate\Database\Query\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens, Notifiable;
    use LaratrustUserTrait;
    use HasMediaTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'last_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_srcset',
    ];

    public function comments()
    {
        return $this->hasMany(\App\Core\Post\Comment::class, 'author_user_id');
    }

    public function likes()
    {
        return $this->hasMany(\App\Core\Like\Like::class, 'author_user_id');
    }

    public function posts()
    {
        return $this->hasMany(\App\Core\Post\Post::class, 'author_user_id');
    }

    /**
     * Scope a query to only include users registered last week.
     */
    public function scopeLastWeek(Builder $query): Builder
    {
        return $query->whereBetween('registered_at', [carbon('1 week ago'), now()])
            ->latest();
    }

    /**
     * Scope a query to order users by latest registered.
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderBy('registered_at', 'desc');
    }

    public function getAllPermissionsAttribute() {
        $roles = $this->roles()->with('permissions')->get();

        $roles = $roles->flatMap(function ($role) {
            return $role->permissions;
        });

        return $this->permissions->merge($roles)->unique('name');
    }

    public function registerMediaConversions(Media $media = null) {
        $this->addMediaConversion('profile')
            ->withResponsiveImages();
    }


    public function getProfileSrcsetAttribute() {
        return optional($this->getMedia('profile')
            ->last())->getSrcset('profile');
    }

}
