<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Controllers\UserController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
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
        "uniqueUrl",
        'name',
        'email',
        'password',
        "avatar",
        "cover",
        "city",
        "state",
        "country",
        "birth_date",
        "following_count",
        "followers_count",
        "last_online_at"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        "email"
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

    /**
     * Retorna quem o Usuário está seguindo
     */
    public function following() {
        return $this->hasMany(FriendRelation::class, "user_from", "id");
    }

    /**
     * Retorna quem está seguindo o Usuário
     */
    public function followers() {
        return $this->hasMany(FriendRelation::class, "user_to", "id");
    }

    public static function getUsersModelsFromFriendRelations(Collection $targetUsers) : Collection {
        $users = collect();

        foreach($targetUsers as $userRelation) {
            $user = User::find($userRelation->user_from);
            $user = UserController::checkUser($user);
            $users->push($user);
        }

        return $users;
    }

    public static function getUsersImgs(Collection $targetUsers) : Collection {
        $users = collect();

        foreach($targetUsers as $targetUser) {
            $targetUser = UserController::checkUser($targetUser);

            $users->push($targetUser);
        }

        return $users;
    }
}
