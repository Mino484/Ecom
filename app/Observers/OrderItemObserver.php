<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $item)
    {
        $product = $item->product; // Assuming an OrderItem belongs to a Product
        if ($product->stock >= $item->quantity) {
            $product->stock -= $item->quantity;
            $product->save();
        } else {
            throw new \Exception('Insufficient stock for product: ' . $product->name);
        }
    }
        public function deleted(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItem): void
    {
        //
    }
}
