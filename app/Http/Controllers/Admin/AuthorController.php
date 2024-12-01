<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Author;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Laravel\Prompts\alert;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        // Lấy trang hiện tại và số lượng tác giả cần tải
        $page = $request->input('page', 1); // Mặc định là trang 1
        $perPage = 10; // Số lượng tác giả mỗi lần tải

        $query = Author::query();

        // Lọc theo khu vực nếu có
        if ($request->has('area') && $request->area) {
            $query->where('area_id', $request->area);
        }

        // Lọc theo từ khóa tìm kiếm nếu có
        if ($request->has('search') && $request->search) {
            $query->where('author_name', 'like', '%' . $request->search . '%');
        }
        // Truy vấn lấy dữ liệu
        $authors = $query->withCount('songs')->with('area')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();
        if ($authors->isEmpty()) {
            return response()->json([], 200); // Trả về một mảng rỗng nếu không còn tác giả
        }
        return response()->json($authors);
    }
    public function createAuthor(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'area' => 'required|exists:areas,id',
                'bio' => 'string|nullable',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $imageData = file_get_contents($request->file('image'));
            $imageName = Str::uuid() . '.webp';
            // Lưu ảnh
            $image = Storage::disk('public')->put('images/' . $imageName, $imageData);
            if (!$image) {
                return response()->json(['error' => 'Failed to save the image.'], 500);
            }
            $imagePath = Image::create([
                'img_name' => $imageName,
                'img_path' => 'images/' . $imageName,
                'category' => 'artist_img',
            ]);

            // Tạo tác giả
            Author::create([
                'author_name' => $request->name,
                'area_id' => $request->area,
                'bio' => $request->bio,
                'img_id' => $imagePath->id,
            ]);
            return response()->json(['success' => true], 200);
        } catch (\Exception $e) {
            // Xóa ảnh nếu có lỗi
            Storage::disk('public')->delete('images/' . $imageName);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}