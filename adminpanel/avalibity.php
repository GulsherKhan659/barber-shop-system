<?php
include("./database/connection.php");
include("./partial/header.php");

$db = new Database('localhost', 'barberbookingsystem', 'root', '');

// Fetch staff list
$staffList = $db->select(
  "staff s JOIN users u ON s.user_id = u.id",
  "s.id as staff_id, u.name as staff_name",
  "u.role = 'staff'"
);

// Fetch all available slots
$slotsData = $db->select(
  "staff_available_slots sas 
     JOIN staff s ON sas.staff_id = s.id 
     JOIN users u ON s.user_id = u.id",
  "sas.id, sas.staff_id, u.name as staff_name, sas.date, sas.start_time, sas.end_time"
);



// $staff = $db->select('users', '*', ['role' => 'staff']);

?>
<!-- jQuery + jQuery UI (required for MultiDatesPicker) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/multidatespicker@1.6.6/jquery-ui.multidatespicker.js"></script>


<div class="container-fluid">

  <?php if (isset($_SESSION['notification'])): ?>
    <div class="alert alert-<?= $_SESSION['notification']['type'] ?> alert-dismissible fade show" role="alert">
      <?= htmlspecialchars($_SESSION['notification']['message']) ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php unset($_SESSION['notification']); ?>
  <?php endif; ?>

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Staff Calendar-Based Availability (Date-Specific Slots)</h1>

    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#slotModal">
      <i class="fa fa-clock"></i> Add Available Slot
    </button>
  </div>

  <!-- Modal for Adding Slot -->
  <div class="modal fade" id="slotModal" tabindex="-1" role="dialog" aria-labelledby="slotModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">

        <div class="modal-header">
          <h5 class="modal-title" id="slotModalLabel">Add Staff Available Slot</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form method="POST" action="./bootstrap/availability/save_staff_slot.php" onsubmit="return validateSlotTime()">
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

            <label class="mt-3"><strong>Select Date</strong></label>
            <input type="date" name="date" class="form-control" required min="<?= date('Y-m-d') ?>">

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
            <div id="slot-time-error" class="text-danger mb-2" style="display: none;">
              ⚠️ Start time must be earlier than end time.
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success">Save Slot</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script>
    function validateSlotTime() {
      const startInput = document.getElementById("start_time");
      const endInput = document.getElementById("end_time");
      const errorMsg = document.getElementById("slot-time-error");

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

    document.getElementById("start_time").addEventListener("change", validateSlotTime);
    document.getElementById("end_time").addEventListener("change", validateSlotTime);
  </script>

  <!-- Slot List Table -->
  <div class="card mt-4">
    <div class="card-header text-white" style="background: #000 !important;">
        <strong>Available Slots List</strong>
    </div>
    <div class="card-body">

        <!-- Accordion CSS -->
        <style>
            .custom-accordion {
                max-width: 100%;
                margin: 30px auto;
                border-radius: 12px;
                box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
                background: #fff;
                overflow: hidden;
                font-family: 'Segoe UI', Arial, sans-serif;
            }
            .accordion-item { border-bottom: 1px solid #f0f0f0; }
            .accordion-header {
                cursor: pointer;
                padding: 18px 24px;
                font-size: 1.05rem;
                font-weight: 600;
                background: #f8fafc;
                transition: background 0.2s;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .accordion-header:hover { background: #eef2f7; }
            .accordion-arrow {
                transition: transform 0.2s;
                font-size: 1rem;
            }
            .accordion-item.active .accordion-arrow { transform: rotate(90deg); }
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                background: #fff;
                transition: max-height 0.25s cubic-bezier(.4, 0, .2, 1);
                padding: 0 24px;
            }
            .accordion-item.active .accordion-content {
                padding: 18px 12px;
                max-height: 600px;
                border-top: 1px solid #f0f0f0;
            }
            .table-slots {
                margin-bottom: 0;
                background: #f9fafb;
                border-radius: 7px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.03);
                font-size: 15px;
            }
            .table-slots th {
                background: #f3f6fa;
                color: #333;
            }
            .table-slots td, .table-slots th {
                padding: 7px 10px;
                vertical-align: middle;
            }
            .table-slots .btn-danger {
                padding: 3px 9px;
                font-size: 0.95rem;
            }
        </style>

        <!-- Accordion Markup -->
        <div class="custom-accordion" id="staffAccordion">
            <?php foreach ($staffList as $index => $staff): ?>
                <div class="accordion-item<?= $index === 0 ? ' active' : '' ?>">
                    <div class="accordion-header">
                        <?= htmlspecialchars($staff['staff_name']) ?>
                        <span class="accordion-arrow">&#9654;</span>
                    </div>
                    <div class="accordion-content">
                        <?php
                        // Filter slots for this staff member
                        $staffSlots = array_filter($slotsData, function ($row) use ($staff) {
                            return $row['staff_id'] == $staff['staff_id']
;
                        });
                        ?>
                        <?php if (!empty($staffSlots)): ?>
                            <table class="table table-bordered table-striped table-slots">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($staffSlots as $row): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['staff_id']) ?></td>
                                            <td><?= htmlspecialchars($row['date']) ?></td>
                                            <td><?= date("h:i A", strtotime($row['start_time'])) ?></td>
                                            <td><?= date("h:i A", strtotime($row['end_time'])) ?></td>
                                            <td>
                                                <a href="./bootstrap/availability/delete_staff_slot.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this slot?');">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-muted" style="padding: 8px 2px;">
                                No slots found for this staff member.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Accordion JS -->
        <script>
            document.querySelectorAll('.custom-accordion .accordion-header').forEach(header => {
                header.addEventListener('click', function () {
                    const item = header.parentElement;
                    // Collapse others
                    document.querySelectorAll('.custom-accordion .accordion-item').forEach(i => {
                        if (i !== item) i.classList.remove('active');
                    });
                    // Toggle this one
                    item.classList.toggle('active');
                });
            });
        </script>
    </div>
</div>

  <!--  -->

</div>

<?php include("./partial/footer.php"); ?>