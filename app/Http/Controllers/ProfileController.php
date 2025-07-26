<?php

namespace App\Http\Controllers;

use App\Models\User; // User modelini dahil ediyoruz
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Giriş yapmış kullanıcıyı almak için
use Illuminate\Support\Facades\Storage; // Dosya yükleme/silme için
use Illuminate\Validation\Rule; // 'unique' doğrulama kuralı için

class ProfileController extends Controller
{
    // Kullanıcının kendi profilini görüntüleme (örn: /profile)
    public function showOwnProfile()
    {
        $user = Auth::user(); // Giriş yapmış kullanıcıyı al
        // Kullanıcının gönderilerini de çekelim
        // (User modelinde 'posts()' ilişkisi tanımlı olmalı: return $this->hasMany(Post::class);)
        $posts = $user->posts()->latest()->get();
        return view('profile.show', compact('user', 'posts'));
    }

    // Belirli bir kullanıcının profilini görüntüleme (örn: /users/{username})
    // Laravel'in Route Model Binding özelliği sayesinde User nesnesi otomatik gelir.
    // 'username' ile eşleşme yaparız.
    public function show(User $user)
    {
        // Kullanıcının gönderilerini çekelim
        $posts = $user->posts()->latest()->get();
        return view('profile.show', compact('user', 'posts'));
    }

    // Profil düzenleme formunu gösterme (örn: /profile/edit)
    public function edit()
    {
        $user = Auth::user(); // Sadece giriş yapmış kullanıcının kendi profilini düzenleyebilir
        return view('profile.edit', compact('user'));
    }

    // Profil bilgilerini güncelleme işlemi (Formdan gelen verilerle)
    public function update(Request $request)
    {
        $user = Auth::user();

        // Veri doğrulama kuralları
        $request->validate([
            'name' => 'required|string|max:255',
            // 'username' benzersiz olmalı, ancak kullanıcının kendi mevcut kullanıcı adını hariç tutarız.
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            // 'email' benzersiz olmalı, ancak kullanıcının kendi mevcut e-postasını hariç tutarız.
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:500', // Biyografi alanı boş bırakılabilir
            // 'profile_picture' bir resim dosyası olmalı, belirli tiplerde ve max 2MB boyutunda.
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Profil fotoğrafı yükleme işlemi (eğer yeni bir fotoğraf seçildiyse)
        if ($request->hasFile('profile_picture')) {
            // Eğer kullanıcının daha önce bir profil fotoğrafı varsa, eskisini depolamadan sil
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            // Yeni fotoğrafı 'public/profile_pictures' klasörüne kaydet
            // store() metodu dosya yolunu döndürür (örn: profile_pictures/asdfasdf.jpg)
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path; // Veritabanına dosya yolunu kaydet
        }

        // Diğer profil alanlarını güncelle
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->save(); // Tüm değişiklikleri veritabanına kaydet

        // Başarılı güncelleme sonrası kendi profil sayfasına yönlendir ve mesaj göster
        return redirect()->route('profile.show.own')->with('success', 'Profiliniz başarıyla güncellendi!');
    }
}