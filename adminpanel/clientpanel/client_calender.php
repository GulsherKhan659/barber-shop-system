<?php
// Handle month & year
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year  = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
if ($month < 1) { $month = 12; $year--; }
if ($month > 12) { $month = 1; $year++; }

function generateCalendar($month, $year) {
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $startDay = date('w', $firstDay);
    $today = date('Y-m-d');

    $calendar = '<table class="table table-bordered text-center">';
    $calendar .= '<thead><tr class="table-secondary"><th>SU</th><th>MO</th><th>TU</th><th>WE</th><th>TH</th><th>FR</th><th>SA</th></tr></thead><tbody><tr>';

    for ($i = 0; $i < $startDay; $i++) $calendar .= '<td></td>';

    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateStr = "$year-" . str_pad($month, 2, '0', STR_PAD_LEFT) . '-' . str_pad($day, 2, '0', STR_PAD_LEFT);

        if ($dateStr <= $today) {
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-5">
  <h5 class="mb-3">Choose your staff</h5>
  <ul class="list-group" id="staffList">
    <li class="list-group-item">
      <div class="d-flex justify-content-between align-items-center">
        <div><span class="badge bg-secondary me-2">1ᵗʰ</span> <strong>Skin Fade and Beard Trim Combo</strong> with</div>
     
      </div>
    </li>
    <li class="list-group-item">
      <div class="d-flex justify-content-between align-items-center">
        <div><span class="badge bg-secondary me-2">2ⁿᵈ</span> <strong>Child</strong> with</div>
        <select class="form-select w-auto staff-select" data-service-id="child">
          <option value="">Please select</option>
          <option value="Michael">Michael</option>
          <option value="Ali">Ali</option>
        </select>
      </div>
    </li>
  </ul>

  <div class="row calendar-section mt-4" id="calendarContainer" style="display: none;">
    <div class="col-md-6">
      <?= generateCalendar($month, $year) ?>
    </div>
    <div class="col-md-6">
      <div class="time-slot-wrapper" id="timeSlots" style="display: none;"></div>
      <div class="mt-3" id="confirmWrapper" style="display: none;">
        <button class="btn btn-success" id="confirmBtn">Confirm</button>
      </div>
      <div class="mt-3 alert alert-info d-none" id="confirmationMessage"></div>
    </div>
  </div>
</div>

<script>
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

      const buildSlots = (startTime, endTime) => {
        let output = '<table class="table table-sm table-bordered text-center">';
        let current = new Date('1970-01-01T' + startTime);
        let end = new Date('1970-01-01T' + endTime);
        let count = 0;

        output += '<tr>';
        while (current <= end) {
          const timeStr = current.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          output += `<td><button type="button" class="btn btn-outline-dark btn-sm time-btn" data-time="${timeStr}">${timeStr}</button></td>`;
          current.setMinutes(current.getMinutes() + 5);
          count++;
          if (count % 6 === 0) output += '</tr><tr>';
        }
        output += '</tr></table>';
        return output;
      };

      timeSlots.innerHTML =
        '<div class="table-secondary text-center fw-bold">Morning: 9:00 AM - 12:00 PM</div>' +
        buildSlots('09:00:00', '11:55:00') +
        '<div class="table-secondary text-center fw-bold">Afternoon: 12:00 PM - 5:00 PM</div>' +
        buildSlots('12:00:00', '16:55:00');

      timeSlots.style.display = 'block';
      attachTimeListeners();
    });
  });

  function attachTimeListeners() {
    document.querySelectorAll('.time-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.time-btn').forEach(b => {
          b.classList.remove('btn-dark');
          b.classList.add('btn-outline-dark');
        });
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-dark');
        selectedTime = btn.getAttribute('data-time');

        if (selectedDate && selectedTime) {
          confirmWrapper.style.display = 'block';
        }
      });
    });
  }

  confirmBtn.addEventListener('click', () => {
    confirmationMessage.innerHTML = `<strong>Date:</strong> ${selectedDate} &nbsp; <strong>Time:</strong> ${selectedTime}`;
    confirmationMessage.classList.remove('d-none');
  });
});
</script>
