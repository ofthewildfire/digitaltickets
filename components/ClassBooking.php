<?php namespace Niainteractive\Digitaltickets\Components;

use Cms\Classes\ComponentBase;
use Input;
use Session;
use Redirect;

class ClassBooking extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Class Booking',
            'description' => 'Handles date selection for class bookings'
        ];
    }

    public function onRun()
    {
        // Check if Mall plugin is available
        if (!$this->isMallPluginAvailable()) {
            $this->page['error'] = 'Mall plugin is required but not installed.';
            return;
        }

        $this->page['product'] = $this->getProduct();
        $this->page['selectedDate'] = Session::get('selected_booking_date');
    }

    public function onSelectDate()
    {
        if (!$this->isMallPluginAvailable()) {
            return ['error' => 'Mall plugin is required but not installed.'];
        }

        $date = Input::get('booking_date');
        $productId = Input::get('product_id');
        
        if (!$date || !$productId) {
            return ['error' => 'Please select a valid date'];
        }

        // Validate the date is available for this product
        $product = $this->getMallProduct($productId);
        if (!$product || !$product->isClassBooking()) {
            return ['error' => 'Invalid product'];
        }

        $availableDates = $this->parseAvailableDates($product->available_dates);
        if (!in_array($date, $availableDates)) {
            return ['error' => 'Selected date is not available'];
        }

        // Store selected date in session
        Session::put('selected_booking_date', $date);
        Session::put('booking_product_id', $productId);

        return [
            'success' => true,
            'selectedDate' => $date
        ];
    }

    public function onAddToCart()
    {
        if (!$this->isMallPluginAvailable()) {
            return ['error' => 'Mall plugin is required but not installed.'];
        }

        $productId = Input::get('product_id');
        $quantity = Input::get('quantity', 1);
        $selectedDate = Session::get('selected_booking_date');

        $product = $this->getMallProduct($productId);
        if (!$product || !$product->isClassBooking()) {
            return ['error' => 'Invalid product'];
        }

        if (!$selectedDate) {
            return ['error' => 'Please select a class date first'];
        }

        // Validate date is still available
        $availableDates = $this->parseAvailableDates($product->available_dates);
        if (!in_array($selectedDate, $availableDates)) {
            return ['error' => 'Selected date is no longer available'];
        }

        // Add to cart with booking date
        $cart = \Mall\Classes\Cart::instance();
        $cartItem = $cart->add($product, $quantity, [
            'booking_date' => $selectedDate
        ]);

        // Clear session data
        Session::forget(['selected_booking_date', 'booking_product_id']);

        return [
            'success' => true,
            'message' => 'Class booking added to cart',
            'cartItem' => $cartItem
        ];
    }

    protected function getProduct()
    {
        if (!$this->isMallPluginAvailable()) {
            return null;
        }

        $productId = $this->property('productId') ?: $this->param('id');
        return $this->getMallProduct($productId);
    }

    protected function getMallProduct($productId)
    {
        if (!class_exists('Mall\Models\Product')) {
            return null;
        }

        return \Mall\Models\Product::find($productId);
    }

    protected function isMallPluginAvailable()
    {
        return class_exists('Mall\Models\Product') && 
               class_exists('Mall\Classes\Cart');
    }

    protected function parseAvailableDates($datesString)
    {
        if (!$datesString) {
            return [];
        }

        $dates = explode(',', $datesString);
        return array_map('trim', $dates);
    }

    public function defineProperties()
    {
        return [
            'productId' => [
                'title'             => 'Product ID',
                'description'       => 'The product ID to display',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'Please enter a valid product ID'
            ]
        ];
    }
} 