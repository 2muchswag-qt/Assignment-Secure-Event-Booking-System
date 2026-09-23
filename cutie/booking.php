<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

$events = [
    ['name' => 'Tech Innovators Summit', 'seats' => 50, 'price' => 1500.00],
    ['name' => 'Music Fest Night', 'seats' => 200, 'price' => 800.00],
    ['name' => 'Startup Pitch Day', 'seats' => 30, 'price' => 500.00]
];

$preferredTicket = $_COOKIE['preferred_ticket_type'] ?? 'General Admission';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Your Event</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #fff;
            padding: 36px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            width: 100%;
            max-width: 480px;
        }
        h1 { font-size: 22px; color: #1f2937; }
        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 8px 0 24px;
        }
        .subtitle strong { color: #6366f1; }
        h2 {
            font-size: 16px;
            color: #1f2937;
            margin-bottom: 16px;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
        }
        label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 18px;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
        }
        button {
            width: 100%;
            padding: 12px;
            background: #6366f1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: #4f46e5; }
        .success {
            background: #dcfce7;
            color: #15803d;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p class="subtitle">Preferred ticket type (remembered 24h): <strong><?php echo htmlspecialchars($preferredTicket); ?></strong></p>

        <?php if (isset($_GET['status']) && $_GET['status'] === 'success'): ?>
            <div class="success">Booking successful! Thank you for your purchase.</div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
            <?php
            $errorMessages = [
                'empty_fields' => 'Please fill in all required fields.',
                'invalid_quantity' => 'Ticket quantity must be greater than 0 and not exceed available seats.',
                'invalid_event' => 'Please select a valid event.'
            ];
            $message = $errorMessages[$_GET['error']] ?? 'An unknown error occurred.';
            ?>
            <div class="error"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h2>Book a Ticket</h2>
        <form action="process.php" method="POST">
            <label for="event">Select Event</label>
            <select name="event" id="event" required>
                <?php foreach ($events as $index => $event): ?>
                    <option value="<?php echo $index; ?>">
                        <?php echo htmlspecialchars($event['name']); ?>
                        (₱<?php echo number_format($event['price'], 2); ?>, <?php echo $event['seats']; ?> seats left)
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Ticket Quantity</label>
            <input type="number" id="quantity" name="quantity" min="1" required>

            <label for="promo_code">Promo Code (optional)</label>
            <input type="text" id="promo_code" name="promo_code">

            <button type="submit">Book Now</button>
        </form>
    </div>
</body>
</html>