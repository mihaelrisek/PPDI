<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

const DATA_DIR = __DIR__ . '/../data';
const USERS_XML_PATH = DATA_DIR . '/users.xml';
const USERS_XSD_PATH = DATA_DIR . '/users.xsd';
const CARS_XML_PATH = DATA_DIR . '/cars.xml';
const CARS_XSD_PATH = DATA_DIR . '/cars.xsd';

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function validateXmlDocument(string $xmlPath, string $xsdPath): DOMDocument
{
    $document = new DOMDocument();
    $document->preserveWhiteSpace = false;
    $document->formatOutput = true;

    libxml_use_internal_errors(true);

    if (!$document->load($xmlPath)) {
        throw new RuntimeException('Nije moguće učitati XML datoteku: ' . basename($xmlPath));
    }

    if (!$document->schemaValidate($xsdPath)) {
        $errors = libxml_get_errors();
        libxml_clear_errors();
        throw new RuntimeException('XML datoteka ' . basename($xmlPath) . ' nije valjana prema pripadajućoj XSD shemi.');
    }

    libxml_clear_errors();

    return $document;
}

function loadUsers(): array
{
    $document = validateXmlDocument(USERS_XML_PATH, USERS_XSD_PATH);
    $xml = simplexml_import_dom($document);

    if ($xml === false) {
        throw new RuntimeException('Greška pri pretvaranju users.xml u SimpleXML.');
    }

    $users = [];

    foreach ($xml->user as $user) {
        $users[] = [
            'username' => (string) $user->username,
            'password' => (string) $user->password,
            'role' => (string) $user->role,
        ];
    }

    return $users;
}

function loadCars(): array
{
    $document = validateXmlDocument(CARS_XML_PATH, CARS_XSD_PATH);
    $xml = simplexml_import_dom($document);

    if ($xml === false) {
        throw new RuntimeException('Greška pri pretvaranju cars.xml u SimpleXML.');
    }

    $cars = [];

    foreach ($xml->car as $car) {
        $cars[] = [
            'id' => (int) $car->id,
            'brand' => (string) $car->brand,
            'model' => (string) $car->model,
            'year' => (string) $car->year,
            'engine' => (string) $car->engine,
            'horsepower' => (string) $car->horsepower,
            'acceleration' => (string) $car->acceleration,
            'topSpeed' => (string) $car->topSpeed,
            'drivetrain' => (string) $car->drivetrain,
            'transmission' => (string) $car->transmission,
            'price' => (string) $car->price,
            'shortDescription' => (string) $car->shortDescription,
            'fullDescription' => (string) $car->fullDescription,
            'image' => (string) $car->image,
        ];
    }

    return $cars;
}

function findUser(string $username, string $password): ?array
{
    foreach (loadUsers() as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            return $user;
        }
    }

    return null;
}

function requireLogin(): void
{
    if (empty($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function getCurrentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

function findCarById(int $id): ?array
{
    foreach (loadCars() as $car) {
        if ($car['id'] === $id) {
            return $car;
        }
    }

    return null;
}

function filterCars(array $cars, string $query): array
{
    if ($query === '') {
        return $cars;
    }

    $normalized = mb_strtolower($query);

    return array_values(array_filter($cars, static function (array $car) use ($normalized): bool {
        $haystack = implode(' ', [
            $car['model'],
            $car['brand'],
            $car['year'],
            $car['horsepower'],
            $car['acceleration'],
            $car['topSpeed'],
            $car['engine'],
            $car['shortDescription'],
        ]);

        return str_contains(mb_strtolower($haystack), $normalized);
    }));
}
