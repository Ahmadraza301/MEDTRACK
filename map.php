<!-- connect file -->
<?php
$page_title = 'MedTrack - Map';
include('./components/connect.php');
include('./components/customer_header.php');

if (isset($_GET['shop'])) {
    $shop_id = $_GET['shop'];
    $sel = "SELECT * FROM shopkeeper_ac WHERE id = $shop_id";
    $sel_ex = mysqli_query($conn, $sel);
    $fetch = mysqli_fetch_assoc($sel_ex);
    $add = $fetch['address'];
} else if (isset($_GET['sname'])) {
    $add = $_GET['sname'];
} else {
    $add = null;
}

?>

<!DOCTYPE html>
<html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Map - NearBy ShopWay </title>
        <link href="./assets/img/trolley.png" rel="icon">
        <link href="./assets/img/apple-touch-icon.png" rel="apple-touch-icon">

        <!-- Vendor CSS Files -->
        <link href="./assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="./assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="./assets/css/admin_style.css" rel="stylesheet">


        <!-- font awesome link -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        body {
            background-color: #9880f6;
            color: white;
            padding: 10px;
        }

        #map {
            height: 77vh;
            width: 100%;
        }
        *{
            font-size: 1.7rem;
        }
    </style>

    <script>
        // Define a function to automatically click the button
        function autoClickButton() {
            const button = document.querySelector("button");
            if (button) {
                button.click(); // Trigger a click event on the button
            }
        }

        // Call the function when the page has loaded
        window.addEventListener("load", autoClickButton);
    </script>
    
</head>

<body >
    <!-- Navbar -->
     <!--
    <div class="container-fluid p-0" style="width: 98%;">
        <div class="navbar navbar-expand-lg" style="background-color: blue;">
            <div class="container-fluid">

                <div class="d-flex align-items-center justify-content-between">
                    <a href="index.php" class="logo d-flex align-items-center">
                        <img src="./assets/img/trolley.png" alt="">
                        <span class="d-none d-lg-block" style="color: white;">NearBy ShopWay</span>
                    </a>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="index.php" style="color: white;">Home</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link" href="map.php" target="_blank">Map</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link" href="Aboutus.php">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a style="color: white;" class="nav-link" href="Contactus.php">Contact Us</a>
                        </li>
                        <li>
                            <a style="color: white;" class="nav-link" href="./shopkeeper/login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
    </div>  -->
    <div>
        <br>
        <label for="startLocation">Start Location:</label>
        <input type="text" id="startLocation" placeholder="Enter start location" value="Don Bosco Institute of Technology, Vidyavihar">
        <label for="endLocation">End Location:</label>

        <input type="text" id="endLocation" placeholder="Enter end location" autocomplete="off" value="<?= $add ?>">
        <button onclick="calculateRoute()">Calculate Route</button>
        <p>Total Distance from dbit: <span id="totalDistance">-</span> </p>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {

            var autocomplete;
            var id = 'startLocation';

            autocomplete = new google.maps.places.Autocomplete((document.getElementById(id)), {
                types: ['geocode'],
            })
        })
    </script>

    <script type="text/javascript">
        $(document).ready(function() {

            var autocomplete;
            var id = 'endLocation';

            autocomplete = new google.maps.places.Autocomplete((document.getElementById(id)), {
                types: ['geocode'],
            })
        })
    </script>
    <div id="map"></div>

    <script>
        let map;
        let directionsService;
        let directionsDisplay;
        let startMarker;
        let endMarker;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 19.076090,
                    lng: 72.877426
                }, // Default center
                zoom: 11 // Default zoom level
            });

            directionsService = new google.maps.DirectionsService();
            directionsDisplay = new google.maps.DirectionsRenderer();
            directionsDisplay.setMap(map);
        }

        function calculateRoute() {
            const startLocation = document.getElementById('startLocation').value;
            const endLocation = document.getElementById('endLocation').value;

            const request = {
                origin: startLocation,
                destination: endLocation,
                travelMode: 'DRIVING'
            };

            directionsService.route(request, function(result, status) {
                if (status === 'OK') {
                    directionsDisplay.setDirections(result);

                    // Calculate and display the total distance
                    const route = result.routes[0];
                    let totalDistance = 0;
                    for (let i = 0; i < route.legs.length; i++) {
                        totalDistance += route.legs[i].distance.value;
                    }
                    totalDistance = (totalDistance / 1000).toFixed(2); // Convert meters to kilometers
                    document.getElementById('totalDistance').textContent = totalDistance + ' km';
                } else {
                    console.error('Directions request failed. Status: ' + status);
                    document.getElementById('totalDistance').textContent = 'N/A';
                }
            });
        }
    </script>
    <img src="z.png" alt="" srcset="" style="display: flex; justify-content:center;">


    <!-- Bootstrape js link -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    
<?php
include('./components/customer_footer.php');
?>