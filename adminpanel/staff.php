<?php include("./database/connection.php") ?>
<?php include("./partial/header.php") ?>
<?php
include("./database/configue.php");

$configue= new Configue();

$db = new Database($configue->servername, $configue->database,$configue->username,$configue->password);

$staffMembers = $db->select('users', '*', ['role' => 'staff']);
?>

<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">The RareBaber Staff</h1>
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
      <i class="fa fa-plus"></i> Add Staff
    </button>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Staff Member</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="post" action="./bootstrap/staff/add_staff.php">
          <div class="modal-body">
            <div class="row">
              <div class="col-6">
                <label for="Name">Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Name" required />
              </div>
              <div class="col-6">
                <label for="Phone">Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="Enter Number" required />
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <label for="Email" class="mt-1">Email</label>
                <input type="email" name="email" class="form-control" placeholder="Set Email" required />
              </div>
              <div class="col-6">
                <label for="Password" class="mt-1">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Set Password" required />
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <label for="Bio" class="mt-1">Bio</label>
                <textarea name="bio" style="height:155px" class="form-control" placeholder="Enter Member Bio..." required></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
    <?php foreach ($staffMembers as $staff): ?>
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="d-flex justify-content-between">
                  <div class="font-weight-bold text-gray-800 text-dark text-uppercase mb-1">
                    <?= htmlspecialchars($staff['name']) ?>
                  </div>
                  <div>
                    <form method="post" action="./bootstrap/staff/delete_staff.php" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                      <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                      <button type="submit" style="background:none; border:none; padding:0;">
                        <i class="fas fa-trash text-danger"></i>
                      </button>
                    </form>
                  </div>
                </div>
                <hr>
                <div class="text-xs"><?= htmlspecialchars($staff['email']) ?></div>
                <hr>
                <div class="text-xs"><?= htmlspecialchars($staff['phone']) ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</div>

<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
  <div id="toastError" class="toast border-0 shadow rounded bg-danger text-white" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body fw-semibold" id="toastErrorMsg">
        Error message here.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>




<?php include("./partial/footer.php") ?>

<?php if (isset($_GET['error'])): ?>
<script>
  window.addEventListener('DOMContentLoaded', (event) => {
    const toastError = document.getElementById('toastError');
    const toastErrorMsg = document.getElementById('toastErrorMsg');
    toastErrorMsg.innerText = "<?= htmlspecialchars($_GET['error']) ?>";
    const toast = new bootstrap.Toast(toastError, { delay: 5000 });
    toast.show();
  });
</script>
<?php endif; ?>
