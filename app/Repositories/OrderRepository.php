<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Order);
    }

    //抓取特定帳號下的近期訂單(5分鐘內)
    public function getRecentOrderCount($accountId): int
    {
        return $this->model
            ->where('account', $accountId)
            ->where('created_at', '>=', now()->copy()->subMinutes(5))
            ->count();
    }
}
