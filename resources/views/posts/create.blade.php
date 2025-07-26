<!DOCTYPE html>
<html>
<head>
    <title>Yeni Post</title>
</head>
<body>
    <h1>Yeni Post Oluştur</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="content">İçerik:</label><br>
            <textarea name="content" id="content" required></textarea>
        </div>

        <div>
            <label for="image">Fotoğraf Yükle:</label><br>
            <input type="file" name="image" id="image" accept="image/*">
        </div>

        <button type="submit">Gönder</button>
    </form>
</body>
</html>