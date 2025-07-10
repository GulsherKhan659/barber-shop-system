<?php
include("./database/connection.php");
include("./partial/header.php");

$db = new Database('localhost', 'barberbookingsystem', 'root', '');

$staffList = $db->select(
    "staff s JOIN users u ON s.user_id = u.id",
    "s.id as staff_id, u.name as staff_name"
);

$availabilityData = $db->select(
    "availability a 
     JOIN staff s ON a.staff_id = s.id 
     JOIN users u ON s.user_id = u.id",
    "a.id, a.staff_id, u.name as staff_name, a.weekday, a.start_time, a.end_time"
);
?>

<div class="container-fluid">

<?php if (isset($_SESSION['notification'])): ?>
    <div class="alert alert-<?= $_SESSION['notification']['type'] ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['notification']['message']) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['notification']); ?>
  <?php endif; ?>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">The RareBarber Staff Availability</h1>

    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
      <i class="fa fa-clock"></i> Set Availability
    </button>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Set Availability Schedule</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form method="POST" action="./bootstrap/availability/save_availability.php" onsubmit="return validateTime()">
          <div class="modal-body">

            <label><strong>Select Staff Member</strong></label>
            <select name="staff_id" class="form-control" required>
              <option value="">-- Select Staff Member --</option>
              <?php foreach ($staffList as $staff): ?>
                <option value="<?= $staff['staff_id'] ?>">
                  <?= htmlspecialchars($staff['staff_name']) ?>
                </option>
              <?php endforeach; ?>
            </select>

            <label class="mt-3"><strong>Select Available Day</strong></label>
            <div class="btn-group d-flex flex-wrap" data-toggle="buttons">
              <?php
              $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
              foreach ($days as $day): ?>
                <label class="btn btn-outline-primary m-1">
                  <input type="radio" name="weekday" value="<?= $day ?>" required> <?= $day ?>
                </label>
              <?php endforeach; ?>
            </div>

              <div class="form-row mt-3">
                  <div class="form-group col-md-6">
                      <label for="start_time"><strong>Start Time</strong></label>
                      <input type="time" name="start_time" id="start_time" class="form-control" required>
                  </div>
                  <div class="form-group col-md-6">
                      <label for="end_time"><strong>End Time</strong></label>
                      <input type="time" name="end_time" id="end_time" class="form-control" required>
                  </div>
              </div>

              <!-- 🔥 Error Message -->
              <div id="time-error" class="text-danger mb-2" style="display: none;">
                  ⚠️ Start time must be earlier than end time.
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

    <script>
        function validateTime() {
            const startInput = document.getElementById("start_time");
            const endInput = document.getElementById("end_time");
            const errorMsg = document.getElementById("time-error");

            const startTime = startInput.value;
            const endTime = endInput.value;

            if (startTime && endTime) {
                if (startTime >= endTime) {
                    errorMsg.style.display = "block";
                    endInput.setCustomValidity("End time must be after start time.");
                    return false;
                } else {
                    errorMsg.style.display = "none";
                    endInput.setCustomValidity("");
                    return true;
                }
            }

            return true;
        }

        document.getElementById("start_time").addEventListener("change", validateTime);
        document.getElementById("end_time").addEventListener("change", validateTime);
    </script>


  <div class="card mt-4">
    <div class="card-header text-white" style="background: #000 !important;">
      <strong>Staff Availability</strong>
    </div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Staff Name</th>
            <th>Weekday</th>
            <th>Start Time</th>
            <th>End Time</th>
              <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($availabilityData)): ?>
            <?php foreach ($availabilityData as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['staff_name']) ?></td>
                <td><?= htmlspecialchars($row['weekday']) ?></td>
                <td><?= date("h:i A", strtotime($row['start_time'])) ?></td>
                <td><?= date("h:i A", strtotime($row['end_time'])) ?></td>
                  <td>
<!--                      <a href="edit_availability.php?id=--><?php //= $row['id'] ?><!--" class="btn btn-sm btn-primary">-->
<!--                          <i class="fa fa-edit"></i> Edit-->
<!--                      </a>-->
                      <a href="./bootstrap/availability/deleteAvailability.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this availability?');">
                          <i class="fa fa-trash"></i> Delete
                      </a>
                  </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" class="text-center">No availability records found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

</div>

<?php include("./partial/footer.php"); ?>
