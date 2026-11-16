<?php
require __DIR__ . '/../view/viewHeader.php';
require __DIR__ . '/../view/viewBooking.php';
require __DIR__ . '/../view/viewFooter.php';

echo renderHeader('Booking & Presse | Dao Family Records', metadata: [
    'description' => 'Contacts booking, biographies et ressources presse des artistes Dao Family Records à Toulouse.',
], stylesheets: ['booking.css']);
echo renderBooking();
echo renderFooter();
