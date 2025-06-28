<?php namespace Niainteractive\Digitaltickets\Classes;

use OFFLINE\Mall\Models\Order;
use OFFLINE\Mall\Models\OrderItem;
use Event;

class OrderExtension
{
    public function __construct()
    {
        // Only extend if Mall plugin is available
        if (class_exists('OFFLINE\Mall\Models\Order')) {
            // Listen for order creation to add booking dates
            Event::listen('mall.order.beforeCreate', function($order, $data) {
                $this->processBookingDates($order, $data);
            });

            // Listen for order item creation
            Event::listen('mall.orderItem.beforeCreate', function($orderItem, $data) {
                $this->processOrderItemBookingDate($orderItem, $data);
            });
        }
    }

    protected function processBookingDates($order, $data)
    {
        // Check if any items in the order have booking dates
        $hasBookingDates = false;
        $bookingDates = [];

        foreach ($order->items as $item) {
            if ($item->product && $item->product->isClassBooking()) {
                $cartItemId = $item->id; // This might need adjustment based on how cart items are mapped
                $bookingDate = \Session::get('cart_booking_date_' . $cartItemId);
                
                if ($bookingDate) {
                    $hasBookingDates = true;
                    $bookingDates[] = $bookingDate;
                    $item->booking_date = $bookingDate;
                }
            }
        }

        if ($hasBookingDates) {
            $order->booking_date = implode(', ', array_unique($bookingDates));
        }
    }

    protected function processOrderItemBookingDate($orderItem, $data)
    {
        // Check if this order item has a booking date stored in session
        $cartItemId = $orderItem->id; // This might need adjustment
        $bookingDate = \Session::get('cart_booking_date_' . $cartItemId);
        
        if ($bookingDate) {
            $orderItem->booking_date = $bookingDate;
            \Session::forget('cart_booking_date_' . $cartItemId);
        }
    }
} 