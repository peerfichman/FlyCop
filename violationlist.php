<?php
include "config.php";
include "urldefine.php";

session_start();
if (!isset($_SESSION["role"])) {
  header('Location: ' . URL);
}

if (!empty($_POST["sort"])) {
  switch ($_POST["sort"]) {
    case "date":
      $query  = "SELECT * FROM tbl_violation_209 ORDER BY dateV DESC, timeV DESC";
      break;
    case "severity":
      $query  = "SELECT * FROM tbl_violation_209 ORDER BY severity DESC";
      break;
    case "type":
      $query = "SELECT * FROM tbl_violation_209 ORDER BY type";
  }
} else {

  $query  = "SELECT * FROM tbl_violation_209";
}

$result = mysqli_query($connection, $query);
if (!$result) {
  die("DB query failed.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" />
    <title>Violation List</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php"></a>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header ">
                    <h5 class="offcanvas-title text-white" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close text-reset bg-white" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav flex-grow-1 pe-3" <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                                                  else echo 'style:"display: flex"'; ?>>
                        <li class="nav-item">
                            <?php if ($_SESSION["role"] == 1)
                                echo '<a class="nav-link" href="createobject.php">New Mission</a>';
                                elseif ($_SESSION["role"] == 2)
                                echo '<a class="nav-link" href="createviolation.php">New Violation</a>'; ?>
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
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
            aria-controls="offcanvasNavbar" <?php if (!isset($_SESSION["user"])) echo 'style="display: none;"';
                                                else echo 'style:"display: flex"'; ?>>
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <!-- End of navbar -->

    <main>
        <!-- Breadcrumbs -->
        <ul class="breadcrumbs">
            <li><i class="bi bi-caret-right"></i><a href="index.php">Home Screen</a></li>
            <li><i class="bi bi-caret-right"></i><a href="#">Violation List</a></li>
        </ul>
        <!-- End of breadcrumbs -->

        <!-- Violation list -->
        <div class="wrapper">
          <div class="d-flex justify-content-between">
            <h1>Violations List</h1>
            <?php
             if ($_SESSION["role"] == 2) { //Only hotline receptionist can add violation
              echo '<a href="createviolation.php"><i class="fs-2 bi bi-plus-square"></i></a>';
             }
            ?>
          </div>
          <!-- Violations Detected -->
          <section id="vioDet">
              <h2>Violations Detected: </h2>
              <form action="#" method="post" class="d-flex align-items-center">
                  <label>Sort by:</label>
                  <input type="submit" name="sort" value="date" class="btn btn-sm btn-secondary"> <!-- Sort by Date -->
                  <input type="submit" name="sort" value="severity" class="btn btn-sm btn-secondary"> <!-- Sort by Severity of violation-->
                  <input type="submit" name="sort" value="type" class="btn btn-sm btn-secondary"> <!-- Sort by Type of violation -->
              </form>
              <ul class="violationList" id="list">
                  <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                      echo '<li class="border-bottom border-dark ">';
                      echo '<a href="violationpage.php?vId=' . $row["violationId"] . '" class="d-flex justify-content-between align-items-end">';
                      switch ($row["severity"]) {
                        case 1:
                          echo '<p><img  src="images/signGr.png" class ="signIcn"></p>';
                          break;
                        case 2:
                          echo '<p><img  src="images/signYel.png" class ="signIcn"></p>';
                          break;
                        case 3:
                          echo '<p><img  src="images/signRed.png" class ="signIcn"></p>';
                          break;
                      }
                      echo '<p class="startLine">' . $row["type"] . '</p>';
                      echo '<p>' . $row["timeV"] . '</p>';
                      echo '<p>' . $row["dateV"] . '</p>';
                      echo '</a>';
                      echo '</li>';
                    }
                    ?>
              </ul>
          </section>
          <!-- End of Violations Detected -->
        </div>
        <!--End of Violation list -->
    </main>
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