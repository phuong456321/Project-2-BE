<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class ImageController extends Controller
{
    public function showImage($id)
    {
        // Truy vấn hình ảnh từ cơ sở dữ liệu
        $image = Image::where('img_id', $id)->first();
        // Kiểm tra nếu hình ảnh tồn tại
        if ($image) {
            $imagePath = public_path('storage/' . $image->img_path);
            // Trả về hình ảnh với header Content-Type đúng
            return response()->file($imagePath);
        }

        // Nếu không tìm thấy hình ảnh
        return abort(404, 'Image not found');
    }
}
