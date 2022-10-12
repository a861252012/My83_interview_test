<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use BillerInterface;
use DB;
use Exception;

class OrderProcessorService
{
    private $orderRepository;

    public function __construct(
        BillerInterface $biller,
        OrderRepository $orderRepository
    ) {
        $this->biller = $biller;
        $this->orderRepository = $orderRepository;
    }

    //把題目的業務邏輯移service層,DB相關操作統整到repository
    public function process($orderId)
    {
        try {
            DB::beginTransaction();

            $order = $this->orderRepository->find($orderId);
            $accountID = $order->account->id;
            $orderAmount = $order->amount;

            $recent = $this->orderRepository->getRecentOrderCount($accountID);

            if ($recent > 0) {
                throw new Exception('Duplicate order likely.');
            }

            $this->biller->bill(
                $accountID,
                $orderAmount
            );

            //改用create created_at預設會自動帶入當下寫入時間
            $this->orderRepository->create([
                'account' => $accountID,
                'amount' => $orderAmount
            ]);

            DB::commit();

            return [
                'status' => 200,
                'msg' => 'success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error($e);

            return [
                'status' => 500,
                'msg' => $e->getMessage()
            ];
        }
    }
}
