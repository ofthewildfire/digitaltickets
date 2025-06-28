<?php namespace Niainteractive\Digitaltickets\Classes;

use Mall\Classes\Cart;

class CartExtension
{
    public function __construct()
    {
        // Extend the cart to handle booking dates
        Cart::extend(function($cart) {
            $cart->addDynamicMethod('addWithBookingDate', function($product, $quantity = 1, $options = []) use ($cart) {
                // Store booking date in cart item options
                if (isset($options['booking_date'])) {
                    $cartItem = $cart->add($product, $quantity, $options);
                    
                    // Store booking date in session for later use
                    \Session::put('cart_booking_date_' . $cartItem->id, $options['booking_date']);
                    
                    return $cartItem;
                }
                
                return $cart->add($product, $quantity, $options);
            });
        });
    }
} 