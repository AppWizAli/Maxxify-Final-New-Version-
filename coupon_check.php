<?php
require 'config.php';

$code = trim($_POST['code'] ?? '');
$price = floatval($_POST['price'] ?? 0);

$response = ['valid' => false];

if ($code !== '') {
    $stmt = $pdo->prepare("SELECT * FROM coupons WHERE code = ?");
    $stmt->execute([$code]);
    $coupon = $stmt->fetch();
    if (!$coupon) {
        $response['error'] = 'Invalid coupon';
    } elseif (strtotime($coupon['expiry_date']) < strtotime(date('Y-m-d'))) {
        $response['error'] = 'Coupon expired';
    } else {
        $discount = round($price * ($coupon['percentage'] / 100), 2);
        $final = round($price - $discount, 2);
        $response = [
            'valid' => true,
            'percentage' => $coupon['percentage'],
            'discount' => $discount,
            'final' => $final,
            'coupon_id' => $coupon['id']
        ];
    }
} else {
    $response['error'] = 'Enter a coupon code';
}

header('Content-Type: application/json');
echo json_encode($response); 