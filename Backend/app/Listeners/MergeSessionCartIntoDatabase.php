<?php

class MergeSessionCartIntoDatabase
{
    public function handle(Login $event)
    {
        $user = $event->user;
        $session = session('cart.items', []);
        foreach ($session as $item) {
            CartItem::updateOrCreate(
                ['user_id'=>$user->id,'farm_product_id'=>$item['farm_product_id']],
                ['quantity'=>$item['quantity']]
            );
        }
        session()->forget('cart.items');
    }
}