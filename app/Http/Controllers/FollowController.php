<?php

namespace App\Http\Controllers;

use App\Models\User; // User modelini kullanacağımız için ekledik
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Giriş yapmış kullanıcıyı almak için

class FollowController extends Controller
{
    /**
     * Kullanıcıları sadece giriş yapmışlarsa takip etmelerine izin ver.
     */
    public function __construct()
    {
        $this->middleware('auth'); // Bu controller'daki tüm metotlar için kimlik doğrulaması şartı
    }

    /**
     * Bir kullanıcıyı takip etme işlemi.
     *
     * @param User $user Takip edilecek kullanıcı (username ile Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function follow(User $user)
    {
        $follower = Auth::user(); // Takip eden (giriş yapmış) kullanıcı

        // Kullanıcı kendi kendini takip edemez
        if ($follower->id === $user->id) {
            return back()->with('error', 'Kendinizi takip edemezsiniz.');
        }

        // Eğer zaten takip etmiyorsa, takip et
        if (!$follower->isFollowing($user)) {
            $follower->following()->attach($user->id); // Takip ilişkisini ekle
            return back()->with('success', $user->username . ' takip edildi!');
        }

        return back()->with('info', $user->username . ' zaten takip ediliyor.');
    }

    /**
     * Bir kullanıcının takibini bırakma işlemi.
     *
     * @param User $user Takibi bırakılacak kullanıcı (username ile Route Model Binding)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function unfollow(User $user)
    {
        $follower = Auth::user(); // Takibi bırakan (giriş yapmış) kullanıcı

        // Kullanıcı kendi kendini takibi bırakamaz (mantıken olmaz ama yine de kontrol)
        if ($follower->id === $user->id) {
            return back()->with('error', 'Kendinizin takibini bırakamazsınız.');
        }

        // Eğer takip ediyorsa, takibi bırak
        if ($follower->isFollowing($user)) {
            $follower->following()->detach($user->id); // Takip ilişkisini kaldır
            return back()->with('success', $user->username . ' takibi bırakıldı!');
        }

        return back()->with('info', $user->username . ' zaten takip edilmiyor.');
    }
}