// test_mercure.php
<?php

use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

require_once __DIR__.'/vendor/autoload.php';

$kernel = new \App\Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();
$container = $kernel->getContainer();

$hub = $container->get(HubInterface::class);

$update = new Update(
    'test/topic',
    json_encode(['message' => 'Test from Symfony'])
);

try {
    $hub->publish($update);
    echo "✅ Message publié avec succès!\n";
} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
