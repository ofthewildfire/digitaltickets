# Digital Tickets Plugin for OctoberCMS Mall

This plugin extends the OctoberCMS Mall plugin to handle class bookings with date selection functionality.

## Features

- **Date Selection**: Add date picker to product purchase flow for class bookings
- **Email Confirmation**: Automatic email confirmation with virtual ticket
- **Virtual Tickets**: Simple, styled tickets with all booking details
- **Order Integration**: Seamless integration with Mall's existing order system

## Installation

1. Copy the plugin files to your OctoberCMS plugins directory:
   ```
   plugins/niainteractive/digitaltickets/
   ```

2. Register the plugin in your `config/cms.php`:
   ```php
   'plugins' => [
       'Niainteractive\Digitaltickets\Plugin'
   ]
   ```

3. Run the database migration:
   ```bash
   php artisan october:up
   ```

## Configuration

### Setting up Class Booking Products

1. Go to your OctoberCMS backend
2. Navigate to Mall > Products
3. Edit a product you want to make a class booking
4. Enable the "Is Class Booking" switch
5. Enter available dates in the "Available Dates" field (comma-separated)
   Example: `2024-01-15, 2024-01-22, 2024-01-29`

### Using the Component

Add the ClassBooking component to your product page:

```twig
[classBooking]
productId = "{{ product.id }}"
==
<div id="class-booking-container">
    {% component 'classBooking' %}
</div>
```

## Email Template

The plugin includes a pre-configured email template that sends:
- Customer name
- Class name
- Selected class date
- Amount paid
- Unique ticket ID
- Order number

The email template is located at: `plugins/niainteractive/digitaltickets/views/mail/confirmation.htm`

## Database Changes

The plugin adds the following fields to Mall tables:

### mall_products
- `is_class_booking` (boolean) - Whether the product is a class booking
- `available_dates` (text) - Comma-separated list of available dates

### mall_orders
- `booking_date` (string) - The selected booking date

### mall_order_items
- `booking_date` (string) - The selected booking date for this item

## Usage Flow

1. **Customer selects a class product**
2. **Date picker appears** (if product is marked as class booking)
3. **Customer selects available date**
4. **Product is added to cart** with selected date
5. **Checkout process** continues normally
6. **Confirmation email** is sent with virtual ticket

## Customization

### Styling the Date Picker

The component includes basic CSS styling. You can customize it by modifying the styles in `components/classbooking/default.htm`.

### Email Template Customization

Edit the email template at `views/mail/confirmation.htm` to match your branding and requirements.

### Adding More Fields

To add additional fields to the booking process:

1. Extend the database migration
2. Add fields to the component form
3. Update the email template
4. Modify the cart/order processing logic

## Troubleshooting

### Email Not Sending
- Check your OctoberCMS mail configuration
- Verify the order completion event is firing
- Check server logs for mail errors

### Date Selection Not Working
- Ensure the product has `is_class_booking` set to true
- Verify available dates are properly formatted
- Check browser console for JavaScript errors

### Database Issues
- Run `php artisan october:up` to ensure migrations are applied
- Check database permissions
- Verify table structure matches expected schema

## Support

For issues and questions, please check:
1. OctoberCMS documentation
2. Mall plugin documentation
3. Server error logs
4. Browser developer tools

## License

This plugin is provided as-is for educational and commercial use. # digitaltickets
