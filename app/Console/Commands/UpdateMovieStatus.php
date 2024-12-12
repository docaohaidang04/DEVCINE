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
        $movies = Movie::all();

        foreach ($movies as $movie) {
            $releaseDate = Carbon::parse($movie->release_date);
            $movie->status = $releaseDate->isFuture() ? 'future' : 'active';
            $movie->save();
        }

        $this->info('Movie statuses updated successfully.'); // Hiển thị thông báo thành công
    }
}
