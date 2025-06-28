<?php namespace Niainteractive\Digitaltickets\Classes;

use OFFLINE\Mall\Models\Cart as CartModel;
use OFFLINE\Mall\Classes\User\Auth;

class CartExtension
{
    public function __construct()
    {
        // Only extend cart if Mall plugin is available
        if (class_exists('OFFLINE\Mall\Models\Cart')) {
            // Extend the cart to handle booking dates
            CartModel::extend(function($cart) {
                $cart->addDynamicMethod('addWithBookingDate', function($product, $quantity = 1, $options = []) use ($cart) {
                    // Store booking date in cart item options
                    if (isset($options['booking_date'])) {
                        $cartItem = $cart->addProduct($product, $quantity, $options);
                        
                        // Store booking date in session for later use
                        \Session::put('cart_booking_date_' . $cartItem->id, $options['booking_date']);
                        
                        return $cartItem;
                    }
                    
                    return $cart->addProduct($product, $quantity, $options);
                });
            });
        }
    }
} 