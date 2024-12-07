<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:update-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user plans to free if expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = \Carbon\Carbon::now();
        $this->info("Starting to update expired plans at {$now}");

        $users = User::whereHas('products', function ($query) use ($now) {
            $query->where('expired_at', '<', $now);
        })->get();

        foreach ($users as $user) {
            $user->products()->detach(); // Xóa liên kết sản phẩm hiện tại
            $user->plan = 'free';       // Gán lại plan là Free
            $user->save();

            $this->info("Updated user ID {$user->id} to Free plan.");
        }

        $this->info('All expired plans have been updated.');
        return 0;
    }
}
