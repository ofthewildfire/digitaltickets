<?php namespace Niainteractive\Digitaltickets\Models;

use Mall\Models\Product as MallProduct;

class Product extends MallProduct
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Add fillable fields for class booking
        $this->fillable[] = 'is_class_booking';
        $this->fillable[] = 'available_dates';
    }

    public function isClassBooking()
    {
        return (bool) $this->is_class_booking;
    }

    public function getAvailableDatesArrayAttribute()
    {
        if (!$this->available_dates) {
            return [];
        }

        $dates = explode(',', $this->available_dates);
        return array_map('trim', $dates);
    }

    public function setAvailableDatesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['available_dates'] = implode(',', $value);
        } else {
            $this->attributes['available_dates'] = $value;
        }
    }
} 