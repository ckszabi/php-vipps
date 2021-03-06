<?php

include __DIR__ . '/../vendor/autoload.php';
$yaml = new \Symfony\Component\Yaml\Parser();
$settings = \Symfony\Component\Yaml\Yaml::parse(file_get_contents(__DIR__.'/settings.yml'));

try {
    $httpClient = new Http\Adapter\Guzzle6\Client(new \GuzzleHttp\Client([
        // Add certificate.
        'cert' => $settings['cert'],
    ]));
    $vipps = new \Vipps\Vipps($httpClient);
    // Set Vipps client.
    $vipps->setMerchantID($settings['id'])->setMerchantSerialNumber($settings['serialNumber'])->setToken($settings['token']);
    // Get payment.
    $payment = $vipps->payments();
    // Set Order ID.
    $payment->setOrderID($settings['transaction']['orderId']);
    // Get transaction status.
    $payment->capture('captured', 100);
    // Dump status.
    var_dump($payment->getLastResponse());
}
catch (Exception $e) {
    print '<pre>' . var_dump($e) . '</pre>';
}
