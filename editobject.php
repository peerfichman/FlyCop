<!-- PHP for updtaing database and query the mission to be edited -->
<?php

include "config.php";
include "urldefine.php";
session_start();
if (!isset($_SESSION["role"])) {
  header('Location: ' . URL);
}  elseif ($_SESSION["role"] != 1) {
  header('Location:' . URL);
}


if (isset($_POST["submit"])) {
  $dis = $_POST["mDistance"];
  if (!$dis) {
    $dis = 0;
  }

  $end = date('H:i:s', strtotime('+' . $_POST["mTime"] . ' minutes', strtotime($_POST["mStart"])));
  $queryUpdateDrone = "UPDATE tbl_activeDrones_209 SET 
                                            missionType= '" . $_POST["mType"] . "', 
                                            maxAltitude =" . $_POST["mAltitude"] . ", 
                                            maxDistance= " . $dis . ",  
                                            endTime= '" . $end . "'
                                            WHERE missionId= " . $_POST["mId"];

  mysqli_query($connection, $queryUpdateDrone);
  header('Location: ' . URL . 'mainobject.php?mission_id=' . $_POST["mId"] . '');
}

$query = "SELECT * FROM tbl_activeDrones_209 INNER JOIN tbl_users_209 using(user_id) WHERE missionId =" . $_POST["mission"];
$result = mysqli_query($connection, $query);
if ($result) {
  $mission = mysqli_fetch_assoc($result);
} else die("DB query failed.");

$to_time = strtotime($mission["endTime"]);
$from_time = strtotime("$mission[startTime]");
$dur = round(abs($to_time - $from_time) / 60, 2);

?>

<!-- End of PHP -->

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


  <title>#<?php echo $mission["droneId"]; ?> Edit</title>
</head>

<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"></a>
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header ">
          <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menu</h5>
          <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav flex-grow-1 pe-3" <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                                                  else echo 'style:"display: flex"'; ?>>
            <li class="nav-item">
              <?php if ($_SESSION["role"] == 1)
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
      <div id="person" <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
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
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                                                                                                                                              else echo 'style:"display: flex"'; ?>>
      <span class="navbar-toggler-icon"></span>
    </button>
  </nav>
  <!-- End of navbar -->
  <main>
    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
      <li><i class="bi bi-caret-right"></i><a href="index.php">Home Screen</a></li>
      <li><i class="bi bi-caret-right"></i><a href="dronelist.php">Active Drones</a></li>
      <li><i class="bi bi-caret-right"></i><a href="mainobject.php?mission_id=<?php echo $_POST["mission"]; ?>">Drone #<?php echo $mission["droneId"]; ?></a></li>
      <li><i class="bi bi-caret-right"></i><a href="#">Edit</a></li>
    </ul>
    <!-- End of Breadcrumbs -->
    <!-- Edit mission form -->
    <div id="editObj">
      <div class="grayBack">
        <h1>Drone #<?php echo $mission["droneId"]; ?></h1>
        <table>
          <tr>
            <th>Set by:</th>
            <td><?php echo $mission["firstName"] . " " . $mission["lastName"] . "&nbsp;";  ?> </td>
            <th>Start Time:</th>
            <td><?php echo $mission["startTime"]; ?></td>
          </tr>
        </table>
      </div>
      <form class="editForm grayBack" action="#" method="POST">
        <div>
          <button id="resetBtn" class="grayBtn" type="button"><i class="bi bi-x-octagon"></i></button>
          <p class="fw-bold">Mission:</p>
          <div class="form-group d-flex align-items-center">

            <input class="form-check-input align-self-center" type="radio" name="mType" value="patrol" checked id="patrol">
            <label class="form-check-label" for="inlineRadio1">Patrol</label>

            <input class="form-check-input align-self-center" type="radio" name="mType" value="standstill" id="standStill">
            <label class="form-check-label" for="inlineRadio2">Stand still</label>
          </div>
        </div>

        <div class="form-group">
          <div class="d-flex">
            <label class="form-label">Duration: </label>
            <div class="badge bg-dark d-flex justify-content-center">
              <output><?php echo $dur; ?></output> <span>mins</span>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <p>20 mins </p><input type="range" value="<?php echo $dur; ?>" name="mTime" class="form-range" min="20" max="300" step="5" oninput="updateValues(0, this.value);">
            <p> 300 mins</p>
          </div>
        </div>
        <div class="form-group">
          <div class="d-flex">
            <label class="form-label">Avg. Altitude: </label>
            <div class="badge bg-dark d-flex justify-content-center">
              <output><?php echo $mission["maxAltitude"] ?></output> <span>m</span>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <p>3 m </p><input type="range" name="mAltitude" class="form-range align-self-end" min="3" max="10" value="<?php echo $mission["maxAltitude"] ?>" step="0.2" oninput="updateValues(1, this.value);">
            <p> 10 m</p>
          </div>
        </div>
        <div class="form-group">
          <div class="d-flex">
            <label class="form-label">Max distance: </label>
            <div class="badge bg-dark d-flex justify-content-center">
              <output><?php echo $mission["maxDistance"] ?></output> <span>m</span>
            </div>
          </div>
          <div class="d-flex align-items-center">
            <p>25 m </p><input type="range" name="mDistance" class="form-range align-self-end" min="25" max="2500" value="<?php echo $mission["maxDistance"] ?>" step="5" oninput="updateValues(2, this.value);" id="maxDistance">
            <p> 2500 m</p>
          </div>
        </div>
        <input type="hidden" name="mStart" value="<?php echo $mission["startTime"]; ?>">
        <input type="hidden" name="mId" value="<?php echo $mission["missionId"]; ?>">
        <div class="buttonGroup d-flex justify-content-center">
          <a class="text-white btn btn-warning btn-md" href="mainobject.php?mission_id=<?php echo $_POST["mission"]; ?>" role="button">Abort</a>
          <button type="submit" value="Submit" name="submit" class="btn btn-success btn-md"><i class="bi bi-check-lg"></i>Submit</button>
        </div>
      </form>
    </div>
  </main>
  <!-- End of edit mission form -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="scripts/editscript.js"></script>
</body>

</html>