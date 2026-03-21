<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $this->fetch('title') ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Optional Custom CSS -->
    <link href="/public/output.css" rel="stylesheet">
</head>
<body>
    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <!-- <div class="border-end bg-light" id="sidebar-wrapper">
            <div class="sidebar-heading text-center py-4">AI Healthcare Bot</div>
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-light">Dashboard</a>
                <a href="#" class="list-group-item list-group-item-action bg-light">Patients</a>
                <a href="#" class="list-group-item list-group-item-action bg-light">Chat</a>
                <a href="#" class="list-group-item list-group-item-action bg-light">Settings</a>
            </div>
        </div> -->
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <!-- <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle">Toggle Sidebar</button>
                    <h4 class="ms-3 mb-0">Dashboard</h4>
                </div> -->
            </nav>

            <div class="container-fluid mt-4">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Optional JS for sidebar toggle -->
    <!-- <script>
        const toggleButton = document.getElementById('sidebarToggle');
        const wrapper = document.getElementById('wrapper');
        toggleButton.addEventListener('click', () => {
            wrapper.classList.toggle('toggled');
        });
    </script> -->
</body>
</html>
