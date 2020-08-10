<?php
defined('BASEPATH') or die('No direct script access allowed!');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="<?= base_url('assets/technology/bootstrap 4/css/bootstrap.min.css') ?>">

    <style>
        body {
            background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
        }

        .error-template {
            text-align: center;
            margin-top: 10%;
        }

        .error-actions {
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .error-actions .btn {
            margin-right: 10px;
            margin-top: 1rem;
        }
    </style>

    <title>404 Not Found</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <h1>Oops!</h1>

                    <h2>404 Not Found</h2>

                    <div class="error-details">
                        Sorry, an error has occured, Requested page not found!
                    </div>

                    <div class="error-actions">
                        <a href="<?= base_url() ?>" class="btn btn-primary">
                            Take Me Home
                        </a>

                        <a href="<?= base_url('siswa') ?>" class="btn btn-success">
                            CRUD Example
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="<?= base_url('assets/technology/jquery/js/jquery-3.2.1.slim.min.js') ?>"></script>
<script src="<?= base_url('assets/technology/popper/js/popper.min.js') ?>"></script>
<script src="<?= base_url('assets/technology/bootstrap 4/js/bootstrap.min.js') ?>"></script>

</html>