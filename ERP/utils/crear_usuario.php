<?php
require_once 'config/database.php';

$pdo = Database::connect();

$name = 'Prueba1';
$email = 'prueba1@gmail.com';
$password = password_hash('prueba', PASSWORD_DEFAULT);
$role = 'admin';
$active = 1;

$stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, active) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$name, $email, $password, $role, $active]);

echo "✅ Usuario creado correctamente.<br>";
echo "🔐 Hash generado: $password";
