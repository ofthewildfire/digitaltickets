<?php namespace Niainteractive\Digitaltickets\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class AddClassBookingFields extends Migration
{
    public function up()
    {
        // Add fields to mall_products table
        Schema::table('mall_products', function($table) {
            $table->boolean('is_class_booking')->default(false);
            $table->text('available_dates')->nullable();
        });

        // Add fields to mall_orders table
        Schema::table('mall_orders', function($table) {
            $table->string('booking_date')->nullable();
        });

        // Add fields to mall_order_items table
        Schema::table('mall_order_items', function($table) {
            $table->string('booking_date')->nullable();
        });
    }

    public function down()
    {
        // Remove fields from mall_products table
        Schema::table('mall_products', function($table) {
            $table->dropColumn(['is_class_booking', 'available_dates']);
        });

        // Remove fields from mall_orders table
        Schema::table('mall_orders', function($table) {
            $table->dropColumn('booking_date');
        });

        // Remove fields from mall_order_items table
        Schema::table('mall_order_items', function($table) {
            $table->dropColumn('booking_date');
        });
    }
} 