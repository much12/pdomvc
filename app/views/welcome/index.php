<?php

/***
 *      __  __            _                     _          _   ______              _   __  ____      _______ 
 *     |  \/  |          | |         /\        (_)        | | |  ____|            (_) |  \/  \ \    / / ____|
 *     | \  / | ___   ___| |__      /  \   _ __ _ ______ _| | | |__ __ _ _   _ _____  | \  / |\ \  / / |     
 *     | |\/| |/ _ \ / __| '_ \    / /\ \ | '__| |_  / _` | | |  __/ _` | | | |_  / | | |\/| | \ \/ /| |     
 *     | |  | | (_) | (__| | | |  / ____ \| |  | |/ / (_| | | | | | (_| | |_| |/ /| | | |  | |  \  / | |____ 
 *     |_|  |_|\___/ \___|_| |_| /_/    \_\_|  |_/___\__,_|_| |_|  \__,_|\__,_/___|_| |_|  |_|   \/   \_____|
 *                                                                                                           
 *                                                                                                           
 */

defined('BASEPATH') or die('No direct script access allowed');
?>
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="<?= base_url('assets/technology/bootstrap 4/css/bootstrap.min.css') ?>">
</head>

<body>
    <div class="container">
        <div class="card mt-3">
            <div class="card-body text-center">
                <span>Welcome to My Project MVC For PHP Data Objects By Moch Arizal Fauzi</span>

                <div style="padding-top: 1rem;">
                    <span>Contoh CRUD :
                        <a href="<?= base_url('siswa') ?>" title="Skuy" style="text-decoration: none;">Skuy</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="<?= base_url('assets/technology/bootstrap 4/js/bootstrap.min.js') ?>"></script>

</html>