<?php
session_start();
// Proteksi halaman: kalau belum login, tendang balik ke login.php
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
include 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PS Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1a1e25; color: #fff; }
        .sidebar .nav-link { color: #a0aec0; }
        .sidebar .nav-link.active { color: #fff; background-color: #2d3748; border-radius: 5px; }
        .sidebar .nav-link:hover { color: #fff; }
        .canvas-container { border: 2px dashed #ccc; background: #fff; position: relative; }
        canvas { width: 100%; height: 200px; cursor: crosshair; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">