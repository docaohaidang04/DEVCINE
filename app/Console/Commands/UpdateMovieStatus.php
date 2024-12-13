<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;
use Carbon\Carbon;

class UpdateMovieStatus extends Command
{
    protected $signature = 'update:movie-status'; // Tên lệnh Artisan
    protected $description = 'Update movie status based on release_date'; // Mô tả lệnh

    public function handle()
    {
        // Lấy tất cả các bộ phim
        $movies = Movie::all();

        // Lặp qua tất cả các bộ phim
        foreach ($movies as $movie) {
            $releaseDate = Carbon::parse($movie->release_date);

            // Kiểm tra nếu ngày chiếu trùng với ngày hiện tại
            if ($releaseDate->isToday()) {
                $movie->status = 'active'; // Đặt trạng thái là 'active' nếu ngày chiếu là hôm nay
            } elseif ($releaseDate->isFuture()) {
                $movie->status = 'future'; // Đặt trạng thái là 'future' nếu ngày chiếu là trong tương lai
            } else {
                $movie->status = 'expired'; // Đặt trạng thái là 'expired' nếu ngày chiếu đã qua
            }

            $movie->save(); // Lưu lại trạng thái cập nhật
        }

        $this->info('Movie statuses updated successfully.'); // Thông báo khi hoàn thành
    }
}
