<?php

namespace App\Http\Controllers; // Bu kontrolörün hangi isimlendirme alanı (namespace) içinde olduğunu belirtir.

use App\Models\User; // User modelini içeri aktarır, böylece User veritabanı tablosuyla etkileşim kurulabilir.
use Illuminate\Http\Request; // HTTP isteklerini yönetmek için Request sınıfını içeri aktarır (bu örnekte doğrudan kullanılmıyor ama genellikle kontrolörlerde bulunur).

class UserController extends Controller // UserController sınıfını tanımlar ve Laravel'in temel Controller sınıfından miras alır.
{
    /**
     * Belirli bir kullanıcının profilini gösterir.
     *
     * @param  \App\Models\User  $user // Rota model bağlaması (Route Model Binding) sayesinde, gelen URL'deki {user} parametresine karşılık gelen User modelini otomatik olarak alır.
     * @return \Illuminate\View\View // Bir view döndüreceğini belirtir.
     */
    public function show(User $user) // show metodu tanımlanır ve bağımlılık enjeksiyonu ile bir User modeli alır.
    {
        // Belirtilen kullanıcının (User $user) tüm gönderilerini (posts) en sonuncudan başlayarak alır.
        $posts = $user->posts()->latest()->get();

        // 'users.show' adındaki Blade şablonunu döndürür.
        // Bu şablona, $user değişkeni 'user' adıyla ve $posts değişkeni 'posts' adıyla geçirilir.
        return view('users.show', compact('user', 'posts'));
    }
}