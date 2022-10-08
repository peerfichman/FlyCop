<?php
include "urldefine.php";
include "config.php";

session_start();
if (!isset($_SESSION["role"])) {
  header('Location: ' . URL);
}

$queryDrones  = "SELECT * FROM tbl_activeDrones_209 INNER JOIN tbl_users_209 using(user_id)";
$result = mysqli_query($connection, $queryDrones);

if (!$result) {
  die("DB query failed.");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <title>Active Drone</title>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"></a>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header ">
            <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav flex-grow-1 pe-3" <?php   if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                                                            else echo 'style:"display: flex"'; ?>>
              <li class="nav-item">
                <?php   if ($_SESSION["role"] == 1)
                            echo '<a class="nav-link" href="createobject.php">New Mission</a>';
                        elseif ($_SESSION["role"] == 2)
                            echo '<a class="nav-link" href="createviolation.php">New Violation</a>'; 
                ?>
              </li>
              <li class="nav-item">
                <a class="nav-link " href="dronelist.php">Active Drones</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="violationlist.php">Violations</a>
              </li>
            </ul>
        </div>
      </div>
      <!-- User Details in Navbar -->
      <div id="person" <?php  if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                              else echo 'style:"display: flex"'; ?>>
          <?php
            echo '<img id="personImg" src="' . $_SESSION["img"] . '" alt="">';
            echo '<div class="text-white">';
            echo '<h5>' . $_SESSION["fName"] . ' ' . $_SESSION["lName"] . '</h5>';
            echo '<p>' . $_SESSION["rName"] . '</p>';
          ?>
          <div>
            <a href="logout.php" title="Logout"><i class="bi bi-door-closed-fill"></i></a>
          </div>
      </div>
    </div>
    <!-- End of user details -->
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
        <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
              else echo 'style:"display: flex"'; ?>>
        <span class="navbar-toggler-icon"></span>
    </button>
  </nav>
  <!-- End of navbar -->

  <!-- Breadcrumbs -->
  <ul class="breadcrumbs">
      <li><i class="bi bi-caret-right"></i><a href="index.php">Home Screen</a></li>
      <li><i class="bi bi-caret-right"></i><a href="#">Active Drones</a></li>
  </ul>
  <!-- End of Breadcrumbs -->
  <!-- Active drones list -->
  <div id="listWrapper">
      <div class="d-flex justify-content-between">
          <h1>Active Drones</h1>
          <?php
            if ($_SESSION["role"] == 1) { //Only police officer can add drone mission
              echo '<a href="createobject.php"><i class="fs-2 bi bi-plus-square"></i></a>';
            }
          ?>
      </div>
      <?php
  while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="droneObject">';
    echo    '<a class="droneObject" href="mainobject.php?mission_id=' . $row["missionId"] . '">';
    echo      '<img class="imgStyle" src="images/drone.png" alt="">';
    echo      '<div class="col-9">';
    echo         '<h4>Drone #' . $row["droneId"] . '</h4>';
    echo         '<table class="tableStyle">';
    echo            '<tr>';
    echo               '<th>Mission: </th><td>' . $row["missionType"] . '</td>';
    echo               '<th>Violation Detected: </th><td>' . $row["violationDeteced"] . '</td>';
    echo            '</tr>';
    echo            '<tr>';
    echo               '<th>Set by: </th><td>' . $row["firstName"] . '</td>';
    echo               '<th>Time: </th><td>' . $row["startTime"] . ' - ' . $row["endTime"] . '</td>';
    echo            '</tr>';
    echo         '</table>';
    echo      '</div>';
    echo     '</a>';
    echo '</div>';
    echo '<hr class="my-4">';
  }
  ?>
  </div>
  <!-- End of active drones list -->
  <?php
  mysqli_free_result($result);
  ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
</body>

</html>

<?php
mysqli_close($connection);
?>