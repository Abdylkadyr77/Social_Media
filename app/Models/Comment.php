<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Bu satırı buraya eklemelisiniz (Zaten görselde var)
    protected $fillable = ['user_id', 'post_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Eklenmesi gereken yeni fonksiyonlar (Daha önce CommentLike'ta olanlar)

    // Bir yorumun birden fazla beğenisi olabilir.
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Bir yorumun belirli bir kullanıcı tarafından beğenilip beğenilmediğini kontrol eder.
    public function isLikedBy(User $user) // User modelini use etmeyi unutma: use App\Models\User;
    {
        // Yorumun beğenileri arasında, user_id'si gelen kullanıcının id'si ile eşleşen bir kayıt var mı?
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}