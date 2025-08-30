<?php
    if (session_status() === PHP_SESSION_NONE) session_start();

    function setFlash(string $key, string $message, string $type = 'info'): void {
        $_SESSION['flash'][$key] = ['message' => $message, 'type' => $type];
    }

    function getFlash(string $key): ?array {
        if (!isset($_SESSION['flash'][$key])) {
            return null;
        }
        $flash = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $flash;
    }

    function getAllFlashes(): array {
        if (empty($_SESSION['flash'])) {
            return [];
        }
        $flashes = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flashes;
    }
?>