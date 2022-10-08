<?php
include "config.php";
include "urldefine.php";

session_start();
if (!isset($_SESSION["role"])) {
  header('Location: ' . URL);
} elseif ($_SESSION["role"] != 2) {
  header('Location:' . URL);
}

if (isset($_POST["submit"])) {
  $date = date("y.m.d");
  $start = date('H:i:s');
  $sMissionQuery = "SELECT * FROM tbl_activeDrones_209 WHERE missionId=". $_POST["vMission"];
  $result = mysqli_query($connection, $sMissionQuery);
  if (!$result) {
    die("DB query failed.");
  }
  $dId = mysqli_fetch_assoc($result);

  $violationInsert  = "INSERT INTO tbl_violation_209(missionId, droneId, type, details, severity, carNumber, timeV, dateV) 
                VALUES(" . $_POST["vMission"] . "," . $dId["droneId"] . ",'" . $_POST["vType"] . "','" . $_POST["vDetails"] . "'," . $_POST["vSeverity"] . ", " . $_POST["vCarNumber"] . ", '" . $start . "', '" . $date . "')";
  mysqli_query($connection, $violationInsert);
  $vioNumberUpdate = "UPDATE tbl_activeDrones_209 SET violationDeteced = violationDeteced + 1 WHERE missionId=" . $_POST["vMission"];
  mysqli_query($connection, $vioNumberUpdate);
  mysqli_free_result($result);
  header('Location: ' . URL . 'violationlist.php');
}

$missionQuery  = "SELECT * FROM tbl_activeDrones_209";
$activeMission = mysqli_query($connection, $missionQuery);
if (!$activeMission) {
  die("DB query failed.");
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">


  <title>Create Violation</title>
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
  
  <main>
    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
      <li><i class="bi bi-caret-right"></i><a href="index.php">Home Screen</a></li>
      <li><i class="bi bi-caret-right"></i><a href="violationlist.php">Violation List</a></li>
      <li><i class="bi bi-caret-right"></i><a href="#">Create Mission</a></li>
    </ul>
    <!-- End of breadcrumbs -->
  <!-- Create violation form -->
    <div class="wrapper">
      <h1>Create Violation</h1>
      <form action="#" method="post" class="grayBack">

        <div class="form-group">
          <label class="form-label">Choose Mission: </label>
          <select name="vMission" class="form-select" aria-label="Default select example">
            <?php

            while ($mission = mysqli_fetch_assoc($activeMission)) {
              echo "<option value ='" . $mission["missionId"] . "'>Mission #" . $mission["missionId"] . "</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label>Violation Type:</label>
          <input type="text" name="vType" placeholder="Speeding">
        </div>

        <div class="form-group">
          <label>Violation Severity:</label>
          <select name="vSeverity">
            <option value="1">Low</option>
            <option value="2">Medium</option>
            <option value="3">High</option>
          </select>
        </div>

        <div class="form-group">
          <label>Car Number:</label>
          <input type="number" name="vCarNumber">
        </div>

        <div class="form-group">
          <label class="d-block">Violation Description:</label>
          <textarea name="vDetails" class="form-control"></textarea>
        </div>

        <div class="buttonGroup d-flex justify-content-end">
          <a href="violationlist.php" class="btn btn-warning">Abort</a>
          <button type="submit" name="submit" value="Submit" class="btn btn-success btn-md"><i class="bi bi-check-lg"></i>Submit</button>
        </div>
      </form>
    </div>
    <!-- End of create violation form -->
    <?php
    mysqli_free_result($activeMission);
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>

</html>