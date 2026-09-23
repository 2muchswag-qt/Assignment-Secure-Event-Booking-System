<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

// Same mock database — in a real app this would live in a shared include/config file
$events = [
    [
        'name' => 'Tech Innovators Summit',
        'seats' => 50,
        'price' => 1500.00
    ],
    [
        'name' => 'Music Fest Night',
        'seats' => 200,
        'price' => 800.00
    ],
    [
        'name' => 'Startup Pitch Day',
        'seats' => 30,
        'price' => 500.00
    ]
];


function calculateTotal($price, $quantity, $discountCode) {
    $total = $price * $quantity;

    if ($discountCode === 'HCDC2026') {
        $total = $total - ($total * 0.10); // 10% discount
    }

    return $total;
}

// --- Server-side validation ---
$eventIndex = $_POST['event'] ?? '';
$quantity   = $_POST['quantity'] ?? '';
$promoCode  = trim($_POST['promo_code'] ?? '');

if (empty($eventIndex) && $eventIndex !== '0') {
    header("Location: booking.php?error=empty_fields");
    exit;
}

if (empty($quantity)) {
    header("Location: booking.php?error=empty_fields");
    exit;
}

if (!isset($events[$eventIndex])) {
    header("Location: booking.php?error=invalid_event");
    exit;
}

$quantity = (int) $quantity;
$selectedEvent = $events[$eventIndex];

if ($quantity <= 0 || $quantity > $selectedEvent['seats']) {
    header("Location: booking.php?error=invalid_quantity");
    exit;
}


$finalTotal = calculateTotal($selectedEvent['price'], $quantity, $promoCode);


$_SESSION['last_booking'] = [
    'event' => $selectedEvent['name'],
    'quantity' => $quantity,
    'total' => $finalTotal
];

header("Location: booking.php?status=success");
exit;