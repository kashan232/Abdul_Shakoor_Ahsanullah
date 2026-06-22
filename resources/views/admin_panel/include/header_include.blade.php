<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('assets/images/logoIcon/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/bootstrap-toggle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/line-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor/datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css?v=1') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <!-- <link rel="stylesheet" href="assets/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/admin/css/vendor/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="assets/global/css/all.min.css">
    <link rel="stylesheet" href="assets/global/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/admin/css/vendor/select2.min.css">
    <link rel="stylesheet" href="assets/admin/css/vendor/datepicker.min.css">
    <link rel="stylesheet" href="assets/admin/css/app.css?v=1">
    <link rel="stylesheet" href="assets/admin/css/custom.css"> -->





    <style>
        /* Global Premium Theme Overrides */
        
        /* Buttons */
        .btn-outline--primary, .btn--primary, .btn-primary {
            background-color: #22c55e !important;
            border-color: #22c55e !important;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3) !important;
            border-radius: 6px !important;
            transition: all 0.3s ease;
        }
        .btn-outline--primary:hover, .btn--primary:hover, .btn-primary:hover {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.4) !important;
            color: #fff !important;
        }
        
        .btn-outline-primary {
            color: #22c55e !important;
            border-color: #22c55e !important;
            background: transparent !important;
            border-radius: 6px !important;
        }
        .btn-outline-primary:hover {
            color: #fff !important;
            background-color: #22c55e !important;
            box-shadow: 0 2px 6px rgba(34, 197, 94, 0.3) !important;
        }

        /* Cards */
        .card {
            border: none !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03) !important;
            border-radius: 12px !important;
        }
        .card-header {
            background-color: #ffffff !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-radius: 12px 12px 0 0 !important;
        }

        /* Modals */
        .modal-content {
            border: none !important;
            border-radius: 12px !important;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
            overflow: hidden;
        }
        .modal-header {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%) !important;
            color: #ffffff !important;
            border-bottom: none !important;
            padding: 20px 25px !important;
        }
        .modal-title {
            font-weight: 600 !important;
            letter-spacing: 0.5px;
            color: #4ade80 !important;
        }
        .modal-header .close, .modal-header .btn-close {
            color: #ffffff !important;
            text-shadow: none !important;
            opacity: 0.8 !important;
        }
        .modal-header .close:hover, .modal-header .btn-close:hover {
            opacity: 1 !important;
        }
        .modal-body {
            padding: 25px !important;
        }
        .modal-footer {
            border-top: 1px solid #f1f5f9 !important;
            padding: 15px 25px !important;
            background-color: #f8fafc !important;
        }

        /* Inputs */
        .form-control {
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #22c55e !important;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.15) !important;
        }

        /* Tables */
        .table-responsive {
            padding: 15px;
        }
        .table, table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 8px !important;
            margin-top: 10px !important;
            border: none !important;
        }
        .table thead th, .table--light thead th, table.dataTable thead th {
            border-bottom: none !important;
            background-color: #f8fafc !important;
            color: #475569 !important;
            font-weight: 600 !important;
            padding: 12px 15px !important;
            text-transform: uppercase;
            font-size: 13px;
        }
        .table tbody tr, .table--light tbody tr, table.dataTable tbody tr {
            background-color: #ffffff !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.02) !important;
            border-radius: 8px !important;
            transition: all 0.2s ease;
        }
        .table tbody tr:hover, .table--light tbody tr:hover, table.dataTable tbody tr:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
        }
        .table tbody td, .table--light tbody td, table.dataTable tbody td {
            border: none !important;
            border-top: 1px solid #f8fafc !important;
            border-bottom: 1px solid #f8fafc !important;
            padding: 15px !important;
            vertical-align: middle !important;
        }
        .table tbody td:first-child, .table--light tbody td:first-child, table.dataTable tbody td:first-child {
            border-left: 1px solid #f8fafc !important;
            border-radius: 8px 0 0 8px !important;
        }
        .table tbody td:last-child, .table--light tbody td:last-child, table.dataTable tbody td:last-child {
            border-right: 1px solid #f8fafc !important;
            border-radius: 0 8px 8px 0 !important;
        }
        
        .widget-two__btn {
            right: 15px !important;
        }
    </style>
</head>