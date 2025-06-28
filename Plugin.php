<?php namespace Niainteractive\Digitaltickets;

use System\Classes\PluginBase;
use Backend;
use Event;
use Mall\Models\Product;
use Mall\Models\Order;
use Mall\Models\OrderItem;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'Digital Tickets',
            'description' => 'Extends Mall plugin for class bookings with date selection',
            'author'      => 'Niainteractive',
            'icon'        => 'icon-ticket'
        ];
    }

    public function registerComponents()
    {
        return [
            'Niainteractive\Digitaltickets\Components\ClassBooking' => 'classBooking'
        ];
    }

    public function boot()
    {
        // Register extensions
        $this->registerExtensions();
        
        // Extend Mall Product model to include date selection
        Product::extend(function($model) {
            $model->addFillable(['is_class_booking', 'available_dates']);
            
            $model->addDynamicMethod('isClassBooking', function() use ($model) {
                return (bool) $model->is_class_booking;
            });
        });

        // Extend Mall Order model to include booking date
        Order::extend(function($model) {
            $model->addFillable(['booking_date']);
            
            $model->addDynamicMethod('getBookingDateAttribute', function() use ($model) {
                return $model->booking_date;
            });
        });

        // Extend Mall OrderItem model to include booking date
        OrderItem::extend(function($model) {
            $model->addFillable(['booking_date']);
        });

        // Listen for order completion to send confirmation email
        Event::listen('mall.order.completed', function($order) {
            $this->sendConfirmationEmail($order);
        });
    }

    protected function registerExtensions()
    {
        // Register cart extension
        new \Niainteractive\Digitaltickets\Classes\CartExtension();
        
        // Register order extension
        new \Niainteractive\Digitaltickets\Classes\OrderExtension();
    }

    public function registerMailTemplates()
    {
        return [
            'niainteractive.digitaltickets::mail.confirmation' => 'Class Booking Confirmation Email'
        ];
    }

    protected function sendConfirmationEmail($order)
    {
        // Check if any items in the order are class bookings
        $hasClassBookings = false;
        foreach ($order->items as $item) {
            if ($item->product && $item->product->isClassBooking()) {
                $hasClassBookings = true;
                break;
            }
        }

        if ($hasClassBookings) {
            \Mail::send('niainteractive.digitaltickets::mail.confirmation', [
                'order' => $order,
                'customer' => $order->customer
            ], function($message) use ($order) {
                $message->to($order->customer->email, $order->customer->name);
                $message->subject('Class Booking Confirmation - Order #' . $order->order_number);
            });
        }
    }
} 