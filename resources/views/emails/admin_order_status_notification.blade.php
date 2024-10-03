<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Update Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            color: #555;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            text-align: center;
            color: #777;
        }
        .highlight {
            background-color: #e7f3fe;
            padding: 10px;
            border-left: 4px solid #2196F3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Status Updated</h1>
        <div class="highlight">
            <p>The status of order ID: <strong>{{ $order->id }}</strong> for customer <strong>{{ $order->first_name }} {{ $order->last_name }}</strong> has been updated to:</p>
            <p><strong>{{ $order->order_status }}</strong></p>
        </div>
        
        <p>Thank you for your attention to this matter. If you have any questions, feel free to reach out.</p>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Ukay Supplier Consign. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
