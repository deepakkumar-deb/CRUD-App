<?php
$error = false;
$insert = false;
$update = false;
$delete = false;
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  die("connection failed: " . mysqli_connect_error());
}

if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE from `notes` where `Sr.No.`=$sno";
  $result = mysqli_query($conn, $sql);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST["srnoEdit"])) {
    $srno = $_POST["srnoEdit"];
    $Title = trim($_POST['titleedit']);
    $description = trim($_POST['descriptionedit']);

    if (strlen($Title) < 3 || strlen($description) < 3) {
      $error = "Title and Description must be at least 3 characters long.";
    } else {
      $Title = mysqli_real_escape_string($conn, $Title);
      $description = mysqli_real_escape_string($conn, $description);

      $sql = "UPDATE `notes` 
        SET `Title` = '$Title', `Description` = '$description' 
        WHERE `Sr.No.` = $srno";
      $result = mysqli_query($conn, $sql);
      if ($result) {
        $update = true;
      }
    }
  } else {
    $Title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (strlen($Title) < 3 || strlen($description) < 3) {
      $error = "Title and Description must be at least 3 characters long.";
    } else {

      $Title = mysqli_real_escape_string($conn, $Title);
      $description = mysqli_real_escape_string($conn, $description);

      $sql = "INSERT INTO `notes` (`Title`,`Description`) VALUES ('$Title','$description')";

      $result = mysqli_query($conn, $sql);
      if ($result) {
        $insert = true;
      }
    }
  }
}
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <title>iNotes App</title>
  <style>
    body {
      background-color: #e4e6ec;
    }
  </style>
</head>

<body>
  <div class="modal" tabindex="-1" id="editmodal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit This Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="/crud/index.php" method="post">
            <input type="hidden" name="srnoEdit" id="srnoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" placeholder="Title" id="titleedit" name="titleedit" aria-describedby="emailHelp" required minlength="3">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Note Description</label>
              <textarea class="form-control" placeholder="Leave a comment here" id="descriptionedit" name="descriptionedit" style="height: 100px" required minlength="3"></textarea>

            </div>
        </div>
        <div class="modal-footer d-block mr-auto">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">
        <img src="php.svg" alt="php logo" height="28">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">
              Contact us
            </a>
        </ul>
        <form class="d-flex gap-1" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
          <button class="btn btn-outline-success ms-3" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <?php
  if ($error) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> ' . $error . '
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>';
  }

  if ($insert) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>SUCCESS!</strong> Your notes has been inserted successfully.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
  }

  if ($delete) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>SUCCESS!</strong> Your notes has been deleted successfully.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
  }
  if ($update) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>SUCCESS!</strong> Your notes has been updated successfully.
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
  }
  ?>

  <div class="container my-4">
    <h2>Add a note</h2>
    <form action="/crud/index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" placeholder="Title" id="title" name="title" aria-describedby="emailHelp" required minlength="3">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Note Description</label>
        <textarea class="form-control" placeholder="Leave a comment here" id="description" name="description" style="height: 100px" required minlength="3"></textarea>

      </div>
      <button type="submit" class="btn btn-primary my-3">Add note</button>
    </form>
  </div>
  <div class="container my-4">

    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">Sr.NO.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * from `notes`";
        $result = mysqli_query($conn, $sql);
        $srno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $srno++;

          echo "<tr>
    <th scope='row'>" . $srno . "</th>
    <td>" . htmlspecialchars($row['Title']) . "</td>
    <td>" . nl2br(htmlspecialchars($row['Description'])) . "</td>
    <td>
      <button class='edit btn btn-sm btn-primary' id='" . $row['Sr.No.'] . "'>Edit</button>
      <button class='delete btn btn-sm btn-danger' id='d" . $row['Sr.No.'] . "'>Delete</button>
    </td>
  </tr>";
        }

        ?>

      </tbody>
    </table>
    <hr>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script
    src="https://code.jquery.com/jquery-3.7.1.js"
    integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    let table = new DataTable('#myTable');
  </script>
  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("edit", );
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[0].innerText.trim();
        description = tr.getElementsByTagName("td")[1].innerText.trim();
        console.log(title, description);
        titleedit.value = title;
        descriptionedit.value = description;
        srnoEdit.value = e.target.id;
        console.log(e.target.id);
        let modal = new bootstrap.Modal(document.getElementById('editmodal'));
        modal.show();
      })
    })

    deletes = document.getElementsByClassName('delete');
    Array.from(deletes).forEach((element) => {
      element.addEventListener("click", (e) => {
        console.log("delete", );
        sno = e.target.id.substr(1);

        if (confirm("Are you sure you want to delete this note! ")) {
          console.log("YES");
          window.location = `/crud/index.php?delete=${sno}`;
          // Create a form and use post request to submit a form
        } else {
          console.log("NO");
        }
      })
    })
  </script>
</body>

</html>
