<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Promotion;

class UpdatePromotionStatus extends Command
{
    /**
     * Tên và chữ ký của lệnh.
     *
     * @var string
     */
    protected $signature = 'model:update-promotion';

    /**
     * Mô tả của lệnh.
     *
     * @var string
     */
    protected $description = 'Cập nhật status của các khuyến mãi đã hết hạn';

    /**
     * Thực thi lệnh.
     *
     * @return int
     */
    public function handle()
    {
        $expiredPromotions = Promotion::where('end_date', '<', now())
            ->where('status', '!=', 'default')
            ->update(['status' => 'default']);

        $this->info("Đã cập nhật $expiredPromotions khuyến mãi hết hạn.");
        return Command::SUCCESS;
    }
}
