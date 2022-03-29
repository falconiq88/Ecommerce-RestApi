<?php

namespace App\Models;

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
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'city',
        'role',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites')->withTimeStamps();
    }

    public function favoritesHas($productId){
        return self::favorites()->where('product_id',$productId)->exists();
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }

    // users that follow this user
    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }


    public function isFollowing(User $user)
    {
        return !!$this->following()->where('following_id', $user->id)->count();
    }

    public function isFollowedBy(User $user)
    {
        return !!$this->followers()->where('follower_id', $user->id)->count();
    }

    public function pushNotification($title, $body, $message)
    {

        $token = $this->fcm_token;


        if ($token == null) return;

        $data['notification']['title'] = $title;
        $data['notification']['body'] = $body;
        $data['notification']['sound'] = true;
        $data['priority'] = 'normal';
        $data['data']['click_action'] = 'FLUTTER_NOTIFICATION_CLICK';
        $data['data']['message'] = $message;
        $data['to'] = $token;


        $http = new \GuzzleHttp\Client(['headers' => [
            'Centent-Type' => 'application/json',
            'Authorization' => 'key=AAAAZhXHAQo:APA91bHJRPm8oJCgP0FAffIEj0kR7o49Y4c3hjHGjdgW6bgkmSiNH7pDLZ6b9GVBKrwYO49zqBcj0wwHVVDpKW0X2S8pP4hH4LdVhVY5KAiLHc8j-LDswTsQ6EKPvDf9yNVnSdyl3luu'

        ]]);
        try {
            $response = $http->post('https://fcm.googleapis.com/fcm/send', [
                'json' =>
                $data
            ]);
            return $response->getBody();
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            // return $e->getCode();
            if ($e->getCode() === 400) {
                return response()->json(['ok' => '0', 'erro' => 'Invalid Request.'], $e->getCode());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getCode());
            }
            return response()->json('Something went wrong on the server.', $e->getCode());
        }


}
}
