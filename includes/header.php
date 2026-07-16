<?php
// $pageTitle dan $activePage bisa di-set sebelum include file ini
$pageTitle = $pageTitle ?? 'StepUp Shoes';
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= isset($isDashboard) ? '../' : '' ?>assets/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm sticky-top" style="background-color:#1c1c1e;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= isset($isDashboard) ? '../index.php' : 'index.php' ?>">
            <i class="bi bi-shop"></i> StepUp<span class="text-warning">Shoes</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'home' ? 'active fw-semibold text-warning' : '' ?>"
                       href="<?= isset($isDashboard) ? '../index.php' : 'index.php' ?>">
                        <i class="bi bi-house-door"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activePage === 'dashboard' ? 'active fw-semibold text-warning' : '' ?>"
                       href="<?= isset($isDashboard) ? 'index.php' : 'dashboard/index.php' ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
