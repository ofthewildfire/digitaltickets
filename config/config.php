<?php

return [
    'name' => 'Digital Tickets',
    'description' => 'Class booking extension for Mall plugin',
    
    'tabs' => [
        'general' => 'General Settings',
        'email' => 'Email Settings'
    ],
    
    'fields' => [
        'enable_email_confirmation' => [
            'label' => 'Enable Email Confirmation',
            'comment' => 'Send confirmation emails for class bookings',
            'type' => 'switch',
            'default' => true,
            'tab' => 'email'
        ],
        
        'email_template' => [
            'label' => 'Email Template',
            'comment' => 'Template to use for confirmation emails',
            'type' => 'dropdown',
            'options' => [
                'niainteractive.digitaltickets::mail.confirmation' => 'Default Confirmation Template'
            ],
            'default' => 'niainteractive.digitaltickets::mail.confirmation',
            'tab' => 'email'
        ],
        
        'ticket_id_prefix' => [
            'label' => 'Ticket ID Prefix',
            'comment' => 'Prefix for generated ticket IDs',
            'type' => 'text',
            'default' => 'TKT',
            'tab' => 'general'
        ],
        
        'date_format' => [
            'label' => 'Date Format',
            'comment' => 'Format for displaying dates',
            'type' => 'dropdown',
            'options' => [
                'Y-m-d' => 'YYYY-MM-DD',
                'd/m/Y' => 'DD/MM/YYYY',
                'm/d/Y' => 'MM/DD/YYYY',
                'F j, Y' => 'January 1, 2024'
            ],
            'default' => 'F j, Y',
            'tab' => 'general'
        ]
    ]
]; 