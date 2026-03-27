<?php
$error = false;
$insert = false;
$update = false;
$delete = false;

$servername = "gondola.proxy.rlwy.net";
$username = "root";
$password = "YOUR_PASSWORD";  // jo stars ke jagah hai
$database = "railway";
$port = 39137;

$conn = mysqli_connect($servername, $username, $password, $database, $port);

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
  die("connection failed: " . mysqli_connect_error());
}

// DELETE
if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `id`=$sno";
  mysqli_query($conn, $sql);
}

// INSERT + UPDATE
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
              SET `Title`='$Title', `Description`='$description' 
              WHERE `id`=$srno";

      if (mysqli_query($conn, $sql)) {
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

      $sql = "INSERT INTO `notes` (`Title`,`Description`) 
              VALUES ('$Title','$description')";

      if (mysqli_query($conn, $sql)) {
        $insert = true;
      }
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>iNotes App</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

  <style>
    body { background-color: #e4e6ec; }
  </style>
</head>

<body>

<!-- EDIT MODAL -->
<div class="modal" id="editmodal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post">
        <div class="modal-header">
          <h5>Edit Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" name="srnoEdit" id="srnoEdit">

          <input type="text" class="form-control mb-3" id="titleedit" name="titleedit" required>
          <textarea class="form-control" id="descriptionedit" name="descriptionedit" required></textarea>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ALERTS -->
<div class="container mt-3">
<?php
if ($error) echo "<div class='alert alert-danger'>$error</div>";
if ($insert) echo "<div class='alert alert-success'>Inserted Successfully</div>";
if ($update) echo "<div class='alert alert-success'>Updated Successfully</div>";
if ($delete) echo "<div class='alert alert-success'>Deleted Successfully</div>";
?>
</div>

<!-- FORM -->
<div class="container my-4">
  <h2>Add Note</h2>
  <form method="post">
    <input type="text" name="title" class="form-control mb-3" placeholder="Title" required>
    <textarea name="description" class="form-control mb-3" placeholder="Description" required></textarea>
    <button class="btn btn-primary">Add</button>
  </form>
</div>

<!-- TABLE -->
<div class="container">
  <table class="table" id="myTable">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Description</th>
        <th>Actions</th>
      </tr>
    </thead>

    <tbody>
<?php
$sql = "SELECT * FROM notes";
$result = mysqli_query($conn, $sql);

$count = 1;
while ($row = mysqli_fetch_assoc($result)) {
  echo "<tr>
    <td>$count</td>
    <td>{$row['Title']}</td>
    <td>{$row['Description']}</td>
    <td>
      <button class='edit btn btn-primary btn-sm' id='{$row['id']}'>Edit</button>
      <button class='delete btn btn-danger btn-sm' id='d{$row['id']}'>Delete</button>
    </td>
  </tr>";
  $count++;
}
?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
new DataTable('#myTable');

// EDIT
document.querySelectorAll('.edit').forEach(btn => {
  btn.onclick = e => {
    let tr = e.target.closest("tr");
    titleedit.value = tr.children[1].innerText;
    descriptionedit.value = tr.children[2].innerText;
    srnoEdit.value = e.target.id;
    new bootstrap.Modal(document.getElementById('editmodal')).show();
  };
});

// DELETE
document.querySelectorAll('.delete').forEach(btn => {
  btn.onclick = e => {
    let id = e.target.id.substring(1);
    if (confirm("Delete this note?")) {
      window.location = "?delete=" + id;
    }
  };
});
</script>

</body>
</html>
