<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Upload</title>
</head>

<body>
    <h1>Upload an Image</h1>
    <form action="{{ route('test.image.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Choose an image to upload:</label><br><br>
        <input type="file" id="image" name="image" accept="image/*"><br><br>
        <button type="submit">Upload Image</button>
    </form>
</body>

</html>