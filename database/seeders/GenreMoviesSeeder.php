<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreMoviesSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            ['genre_name' => 'Kinh dị'],
            ['genre_name' => 'Hài hước'],
            ['genre_name' => 'Hành động'],
            ['genre_name' => 'Tình cảm'],
            ['genre_name' => 'Viễn tưởng'],
            ['genre_name' => 'Khoa học viễn tưởng'],
            ['genre_name' => 'Hoạt hình'],
            ['genre_name' => 'Phim tài liệu'],
            ['genre_name' => 'Thần thoại'],
            ['genre_name' => 'Lịch sử'],
            ['genre_name' => 'Thể thao'],
            ['genre_name' => 'Âm nhạc'],
            ['genre_name' => 'Tâm lý'],
            ['genre_name' => 'Chính kịch'],
            ['genre_name' => 'Hình sự'],
            ['genre_name' => 'Phiêu lưu'],
            ['genre_name' => 'Gia đình'],
            ['genre_name' => 'Kỳ ảo'],
            ['genre_name' => 'Mạo hiểm'],
            ['genre_name' => 'Dã sử'],
            ['genre_name' => 'Xã hội'],
            ['genre_name' => 'Siêu anh hùng'],
            ['genre_name' => 'Kịch'],
            ['genre_name' => 'Cổ trang'],
            ['genre_name' => 'Tội phạm'],
            ['genre_name' => 'Phim truyền hình'],
            ['genre_name' => 'Mê hoặc'],
            ['genre_name' => 'Tâm linh'],
            ['genre_name' => 'Châm biếm'],
            ['genre_name' => 'Tài liệu xã hội'],
            ['genre_name' => 'Giả tưởng'],
            ['genre_name' => 'Chuyên đề'],
            ['genre_name' => 'Sưu tầm'],
            ['genre_name' => 'Lãng mạn'],
            ['genre_name' => 'Trinh thám'],
            ['genre_name' => 'Tương lai'],
            ['genre_name' => 'Trở về quá khứ'],
            ['genre_name' => 'Điện ảnh độc lập'],
            ['genre_name' => 'Nhạc kịch'],
            ['genre_name' => 'Phim ngắn'],
            ['genre_name' => 'Phim truyền hình thực tế'],
            ['genre_name' => 'Phim hành động hài'],
            ['genre_name' => 'Công nghệ cao'],
            ['genre_name' => 'Phim gia đình'],
            ['genre_name' => 'Lịch sử giả tưởng'],
            ['genre_name' => 'Phim nghệ thuật'],
        ];

        foreach ($genres as &$genre) {
            $genre['created_at'] = now(); // Thêm created_at
            $genre['updated_at'] = now(); // Thêm updated_at
        }

        DB::table('genre_movies')->insert($genres);
    }
}
