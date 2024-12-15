<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movie;

class UpdateMovieStatus extends Command
{
    protected $signature = 'movie:update-status';
    protected $description = 'Cập nhật trạng thái phim dựa vào ngày phát hành';

    public function handle()
    {
        $movies = Movie::all();

        foreach ($movies as $movie) {
            $movie->updateStatus();
        }

        $this->info('Trạng thái phim đã được cập nhật.');
    }
}
