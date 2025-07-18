<?php


require_once __DIR__ . '/../database/configue.php';
require_once __DIR__ . '/../database/connection.php';

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

// $staff = $db->select('users', '*', ['role' => 'staff']);
$staff = $db->select(
  "staff s JOIN users u ON s.user_id = u.id",
  "s.id as staff_id, u.name as staff_name",
  "u.role = 'staff'"
);
?>



<?php
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
if ($month < 1) {
  $month = 12;
  $year--;
}
if ($month > 12) {
  $month = 1;
  $year++;
}

function generateCalendar($month, $year)
{
  $firstDay = mktime(0, 0, 0, $month, 1, $year);
  $daysInMonth = date('t', $firstDay);
  $startDay = date('w', $firstDay);
  $today = date('Y-m-d');
  $currentTime = date('H:i');

  $calendar = '<table class="table table-bordered text-center">';
  $calendar .= '<thead><tr class="table-secondary"><th>SU</th><th>MO</th><th>TU</th><th>WE</th><th>TH</th><th>FR</th><th>SA</th></tr></thead><tbody><tr>';

  for ($i = 0; $i < $startDay; $i++) $calendar .= '<td></td>';

  for ($day = 1; $day <= $daysInMonth; $day++) {
    $dateStr = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

    if (
      $dateStr < $today ||
      ($dateStr == $today && $currentTime >= "18:00")
    ) {
      $calendar .= '<td><button type="button" class="btn btn-secondary w-100 text-decoration-line-through text-dark" disabled>' . $day . '</button></td>';
    } else {
      $calendar .= '<td><button type="button" class="btn btn-outline-primary w-100 date-btn" data-date="' . $dateStr . '">' . $day . '</button></td>';
    }

    if (($startDay + $day) % 7 == 0) $calendar .= '</tr><tr>';
  }

  while (($startDay + $day - 1) % 7 != 0) {
    $calendar .= '<td></td>';
    $day++;
  }

  $calendar .= '</tr></tbody></table>';
  return $calendar;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Service Booking</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <style>
    body {
      background: #f2f4f7 url('https://www.transparenttextures.com/patterns/symphony.png');
      color: #2d3748;
    }

    .container {
      max-width: 980px;
      margin: 40px auto;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 32px rgba(0, 0, 0, 0.07);
      padding-bottom: 32px;
      border: 1px solid #e2e8f0;
    }

    @media (max-width: 800px) {
      .container {
        width: 100%;
        padding: 0 !important;
        border-radius: 0;
      }
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <h5 class="mb-3">Choose your staff</h5>
    <ul class="list-group" id="staffList">

      <?php
      if (isset($_POST['selected_services'])) {
        $services = json_decode($_POST['selected_services'], true);
        if (is_array($services)) {
          $lastIndex =  $services[array_key_last($services)];;
          foreach ($services as $srv) {
            if ($srv == $lastIndex) {
      ?>

              <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                  <div><span class="badge bg-secondary me-2">1ᵗʰ</span> <strong><?php echo htmlspecialchars($srv['id']) . ':' . htmlspecialchars($srv['name']); ?>
                    </strong> with</div>
                  <select class="form-select w-auto staff-select"
                    data-service-id="<?php echo htmlspecialchars($srv['id']); ?>"
                    onchange="logSelectedStaff(this)">
                    <option value="">Please Select</option>
                    <?php
                    foreach ($staff as $user) {
                      echo "<option value='" . htmlspecialchars($user['staff_id']) . "'>" . htmlspecialchars($user['staff_name']) . "</option>";
                    }
                    ?>
                  </select>

                </div>
              </li>


            <?php
            } else {


            ?>

              <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center">
                  <div><span class="badge bg-secondary me-2">1ᵗʰ</span> <strong><?php echo htmlspecialchars($srv['id']) . ':' . htmlspecialchars($srv['name']); ?>
                    </strong> with</div>
                  <select class="form-select w-auto staff-select"
                    data-service-id="<?php echo htmlspecialchars($srv['id']); ?>"
                    onchange="logSelectedStaff(this)">
                    <option value="">Please Select</option>
                    <?php
                    foreach ($staff as $user) {
                      echo "<option value='" . htmlspecialchars($user['staff_id']) . "'>" . htmlspecialchars($user['staff_name']) . "</option>";
                    }
                    ?>
                  </select>
                </div>
              </li>
      <?php


            }
          }
        } else {
          echo "No valid services found.";
        }
      } else {
        echo "No services selected.";
      }


      ?>


    </ul>

    <div class="row calendar-section mt-4" id="calendarContainer" style="display: none;">
      <div class="col-md-6">
        <?= generateCalendar($month, $year) ?>
      </div>
      <div class="col-md-6">
        <div class="time-slot-wrapper" id="timeSlots" style="display: none;"></div>
        <div class="mt-3" id="confirmWrapper" style="display: none; float: inline-end;">
          <button class="btn btn-success" id="confirmBtn" onclick="openBookModal()">Booking</button>
        </div>
        <div class="mt-3 alert alert-info d-none" id="confirmationMessage"></div>
      </div>
    </div>
    <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1055;">
      <!-- ✅ Success Toast -->
      <div id="toastSuccess" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body" id="toastSuccessMsg">
            Success message here.
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>

      <!-- ❌ Error Toast (already exists) -->
      <div id="toastError" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body" id="toastErrorMsg">
            Error message here.
          </div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>
    </div>

    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirm Your Booking</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="bookingSummary">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="finalConfirmBtn" onclick="confirmBookingButton()">Confirm Booking</button>
          </div>
        </div>
      </div>
    </div>


  </div>
  <script>
    let slotsTimes = [];

    document.addEventListener('DOMContentLoaded', () => {
      const selects = document.querySelectorAll('.staff-select');
      const calendarContainer = document.getElementById('calendarContainer');
      const timeSlots = document.getElementById('timeSlots');
      const confirmWrapper = document.getElementById('confirmWrapper');
      const confirmBtn = document.getElementById('confirmBtn');
      const confirmationMessage = document.getElementById('confirmationMessage');

      let selectedDate = '';
      let selectedTime = '';

      function checkAllSelected() {
        return Array.from(selects).every(select => select.value !== '');
      }

      selects.forEach(select => {
        select.addEventListener('change', () => {
          if (checkAllSelected()) {
            calendarContainer.style.display = 'flex';
          } else {
            calendarContainer.style.display = 'none';
            timeSlots.style.display = 'none';
            confirmWrapper.style.display = 'none';
            confirmationMessage.classList.add('d-none');
          }
        });
      });

      document.querySelectorAll('.date-btn').forEach(btn => {
        btn.addEventListener('click', () => {
          selectedDate = btn.getAttribute('data-date');
          document.querySelectorAll('.date-btn').forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline-primary');
          });
          btn.classList.remove('btn-outline-primary');
          btn.classList.add('btn-primary');

          selectedTime = '';
          confirmWrapper.style.display = 'none';

          const today = new Date();
          const yyyy = today.getFullYear();
          const mm = String(today.getMonth() + 1).padStart(2, '0');
          const dd = String(today.getDate()).padStart(2, '0');
          const todayStr = `${yyyy}-${mm}-${dd}`;

          const nowPlus5 = new Date(today.getTime() + 5 * 60000);
          const nowHHMM = nowPlus5.toTimeString().slice(0, 5);

          function buildSlots(startTime, endTime, currentServiceId) {
            let output = '<table class="table table-sm table-bordered text-center">';
            let current = new Date('1970-01-01T' + startTime);
            const end = new Date('1970-01-01T' + endTime);
            let count = 0;

            output += '<tr>';
            while (current <= end) {
              const timeStr = current.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
              });
              const slotHHMM = current.toTimeString().slice(0, 5);
              let disabled = '';
              if (selectedDate === todayStr && slotHHMM <= nowHHMM) {
                disabled = 'disabled style="text-decoration:line-through;background:#f2f4f7;"';
              }

              // output += `<td><button type="button" class="btn btn-outline-dark btn-sm time-btn" data-time="${timeStr}" ${disabled} onclick="confirmBooking('${timeStr}')">${timeStr}</button></td>`;
              output += `<td><button type="button" class="btn btn-outline-dark btn-sm time-btn" data-time="${timeStr}" data-service-id="${currentServiceId}" ${disabled} onclick="confirmBooking(this)">${timeStr}</button></td>`;
              current.setMinutes(current.getMinutes() + 5);
              count++;
              if (count % 6 === 0) output += '</tr><tr>';
            }
            output += '</tr></table>';
            return output;
          }

          let slotDay = selectedDate.split('-')[2];
          if (slotDay.startsWith('0')) slotDay = slotDay.slice(1);

          const matchedSlot = (window.slotsTimes || []).find(s => s.key === slotDay);

          if (!matchedSlot || matchedSlot.value.length === 0) {
            timeSlots.innerHTML = '<div class="alert alert-warning">No available slots for this date.</div>';
            timeSlots.style.display = 'block';
            return;
          }

          let dynamicHTML = '';
          // matchedSlot.value.forEach((slot, idx) => {
          //   dynamicHTML += `<div class="table-secondary text-center fw-bold">Available Slot ${idx + 1}: ${slot.startTime} - ${slot.endTime}</div>`;
          //   dynamicHTML += buildSlots(slot.startTime + ':00', slot.endTime + ':00', selectedServiceId);
          // });
          matchedSlot.value.forEach((slot, idx) => {
            const selectedServiceId = document.querySelector('.staff-select')?.getAttribute('data-service-id'); 
            dynamicHTML += `<div class="table-secondary text-center fw-bold">Available Slot ${idx + 1}: ${slot.startTime} - ${slot.endTime}</div>`;
            dynamicHTML += buildSlots(slot.startTime + ':00', slot.endTime + ':00', selectedServiceId);
          });

          timeSlots.innerHTML = dynamicHTML;
          timeSlots.style.display = 'block';
          attachTimeListeners();
        });
      });

      function attachTimeListeners() {
        document.querySelectorAll('.time-btn').forEach(btn => {
          if (btn.disabled) return;
          btn.addEventListener('click', () => {
            document.querySelectorAll('.time-btn').forEach(b => {
              b.classList.remove('btn-dark');
              b.classList.add('btn-outline-dark');
            });
            btn.classList.remove('btn-outline-dark');
            btn.classList.add('btn-dark');
            selectedTime = btn.getAttribute('data-time');

          });
        });
      }
    });

    function logSelectedStaff(selectElement) {
      let staffAvailability = [];
      let booking = [];
      const staffId = selectElement.value;
      const serviceId = selectElement.getAttribute('data-service-id');

      if (staffId && serviceId) {
        try {
          const availabilityURL = `/barberRepo/barber-shop-system/adminpanel/bootstrap/availability/get_availability.php?staff_id=${staffId}`;
          const bookingURL = `/barberRepo/barber-shop-system/adminpanel/bootstrap/booking/get_bookings.php?staff_id=${staffId}`;
          fetch(availabilityURL)
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                staffAvailability = data.data;
                return fetch(bookingURL);
              } else {
                showErrorToast("❌ " + data.message);
              }
            })
            .then(res => res.json())
            .then(data => {
              if (data.success) {
                booking = data.data;
                makeTimesSlots(staffAvailability, booking);
              } else {
                showErrorToast("❌ " + data.message);
              }
            })
            .catch(err => {
              showErrorToast("⚠️ Failed to connect to server");
            });

        } catch (e) {
          showErrorToast("❌ Failed to parse staff data");
        }
      } else {
        showErrorToast("⚠️ No staff selected or missing data");
      }
    }

    function makeTimesSlots(staffAvailability, bookings) {
      const groupedSlots = {};
      const toMinutes = (timeStr) => {
        const [h, m] = timeStr.split(':').map(Number);
        return h * 60 + m;
      };
      const toTimeStr = (mins) => {
        const h = String(Math.floor(mins / 60)).padStart(2, '0');
        const m = String(mins % 60).padStart(2, '0');
        return `${h}:${m}`;
      };

      if (!Array.isArray(bookings) || bookings.length === 0) {
        staffAvailability.forEach((item) => {
          const day = String(new Date(item.date).getDate());
          if (!groupedSlots[day]) groupedSlots[day] = [];
          groupedSlots[day].push({
            startTime: item.start_time.slice(0, 5),
            endTime: item.end_time.slice(0, 5),
          });
        });
      } else {
        staffAvailability.forEach((availability) => {
          const day = String(new Date(availability.date).getDate());
          const startMin = toMinutes(availability.start_time);
          const endMin = toMinutes(availability.end_time);
          const bookingsOnSameDay = bookings.filter(
            (b) => b.appointment_date === availability.date && b.staff_id === availability.staff_id
          );
          const bookedRanges = bookingsOnSameDay.map((b) => {
            const bStart = toMinutes(b.appointment_time);
            const bEnd = bStart + b.total_duration;
            return [bStart, bEnd];
          });
          bookedRanges.sort((a, b) => a[0] - b[0]);
          let currentStart = startMin;
          const freeSlots = [];
          for (const [bStart, bEnd] of bookedRanges) {
            if (bStart > currentStart) {
              freeSlots.push({
                startTime: toTimeStr(currentStart),
                endTime: toTimeStr(bStart),
              });
            }
            currentStart = Math.max(currentStart, bEnd);
          }
          if (currentStart < endMin) {
            freeSlots.push({
              startTime: toTimeStr(currentStart),
              endTime: toTimeStr(endMin),
            });
          }
          if (!groupedSlots[day]) groupedSlots[day] = [];
          groupedSlots[day].push(...freeSlots);
        });
      }

      window.slotsTimes = Object.entries(groupedSlots).map(([key, value]) => ({
        key,
        value,
      }));
      console.log("✅ Final Slots:", window.slotsTimes);
    }

    function showSuccessToast(message) {
      const toastMsg = document.getElementById("toastSuccessMsg");
      toastMsg.textContent = message;
      const toast = new bootstrap.Toast(document.getElementById("toastSuccess"));
      toast.show();
    }

    function showErrorToast(message) {
      const toastMsg = document.getElementById("toastErrorMsg");
      toastMsg.textContent = message;
      const toast = new bootstrap.Toast(document.getElementById("toastError"));
      toast.show();
    }

    let selectedBookingData = null;

    function confirmBooking(btn) {
      const timeStr = btn.getAttribute('data-time');
      const serviceId = btn.getAttribute('data-service-id');
      const selectEl = document.querySelector(`.staff-select[data-service-id="${serviceId}"]`);
      const staffId = selectEl ? selectEl.value : null;

      const serviceObj = allServices.find(s => String(s.id) === String(serviceId));
      const staffObj = allStaff.find(s => String(s.staff_id) === String(staffId));

      const today = new Date();
      const yyyy = today.getFullYear();
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const dd = String(today.getDate()).padStart(2, '0');
      const appointmentDate = `${yyyy}-${mm}-${dd}`;

      if (!staffObj || !serviceObj) {
        showErrorToast("Please select a staff member.");
        return;
      }
      selectedBookingData = {
        service: serviceObj,
        staff: staffObj,
        appointment_time: timeStr,
        appointment_date: appointmentDate
      };
      document.getElementById("confirmWrapper").style.display = "block";
    }

    const openBookModal = () => {
      if (!selectedBookingData) {
        alert("Please select time and staff first.");
        return;
      }

      const summaryHtml = `
      <p><strong>Service:</strong> ${selectedBookingData.service.name}</p>
      <p><strong>Staff:</strong> ${selectedBookingData.staff.staff_name}</p>
      <p><strong>Date:</strong> ${selectedBookingData.appointment_date}</p>
      <p><strong>Time:</strong> ${selectedBookingData.appointment_time}</p>
      <p><strong>Duration:</strong> ${selectedBookingData.service.duration} mins</p>
    `;
      document.getElementById("bookingSummary").innerHTML = summaryHtml;

      const modal = new bootstrap.Modal(document.getElementById("bookingModal"));
      modal.show();
    }


    const confirmBookingButton = () => {
      if (!selectedBookingData) {
        showErrorToast("❌ Failed no booking data available");
      }
      fetch("/barberRepo/barber-shop-system/adminpanel/bootstrap/booking/save_booking.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify(selectedBookingData)
      })
      .then(res => res.json())
      .then(response => {
        if (response.status) {
          showErrorToast("✅ Booking saved successfully!");
          location.reload(); 
        }else{
           showErrorToast("❌ Failed: " + response.message);
        }
      })
      .catch(error => {
        console.log(error)
        showErrorToast("❌ Failed: " + error);
      });
    }

  </script>
  <script>
    const allStaff = <?php echo json_encode($staff); ?>;
    const allServices = <?php echo isset($_POST['selected_services']) ? json_encode(json_decode($_POST['selected_services'], true)) : '[]'; ?>;
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>