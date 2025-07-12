<?php 
include("../database/configue.php");
include("../database/connection.php");

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);
$services = $db->select('services');
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
      background-color: #121212;
      color: #fff;
    }
    .header {
      background-color: #2196f3;
      color: #fff;
      padding: 15px 0;
      text-align: center;
    }
    .nav-tabs .nav-link {
      background-color: #1e1e1e;
      color: #bbb;
      border: 1px solid #333;
    }
    .nav-tabs .nav-link.active {
      background-color: #333;
      color: #fff;
      border-color: #444 #444 #222;
    }
    .search-input {
      background-color: #1e1e1e;
      border: 1px solid #444;
      color: #fff;
    }
    .search-input::placeholder {
      color: #aaa;
    }
    .service-card {
      background-color: #1e1e1e;
      border: 1px solid #333;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 10px;
      cursor: pointer;
    }
    .service-card.selected {
      background-color: #0d6efd;
      border-color: #0a58ca;
    }
    .service-title {
      font-size: 1rem;
      font-weight: bold;
    }
    .service-price {
      color: rgb(18, 124, 223);
    }
    .btn-choose {
      background-color: rgb(18, 124, 223);
      color: white;
      font-weight: bold;
    }
    .footer-text {
      font-size: 14px;
      color: #aaa;
    }
    .btn-unselect {
      background: #dc3545 !important;
      color: #fff !important;
      border: none;
      border-radius: 50%;
      font-size: 1.2rem;
      width: 32px;
      height: 32px;
      line-height: 1;
      text-align: center;
      padding: 0;
      margin-left: 10px;
      transition: background 0.2s;
    }
    .btn-unselect:hover {
      background: #b02a37 !important;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <div class="header">
    <h5>The RareBarber</h5>
  </div>

  <div class="container mt-4">
    <ul class="nav nav-tabs mb-3" id="serviceTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">All Services</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="popular-tab" data-bs-toggle="tab" data-bs-target="#popular" type="button" role="tab">Most Popular</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="recent-tab" data-bs-toggle="tab" data-bs-target="#recent" type="button" role="tab">My Recently Booked</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="selection-tab" data-bs-toggle="tab" data-bs-target="#selection" type="button" role="tab">My Selections <span class="badge bg-light text-dark" id="selectionCounter">0</span></button>
      </li>
    </ul>

    <input type="text" class="form-control search-input mb-4" placeholder="Search services">

    <div class="tab-content" id="serviceTabsContent">
      <!-- All Services -->
      <div class="tab-pane fade show active" id="all" role="tabpanel">
        <?php if (!empty($services)): ?>
          <?php foreach ($services as $index => $service): ?>
            <div class="service-card" data-id="<?= $index ?>">
              <div class="d-flex justify-content-between">
                <span class="service-title"><?= htmlspecialchars($service['name']) ?></span>
                <span class="service-price">$<?= htmlspecialchars($service['price']) ?> · <?= htmlspecialchars($service['duration_minutes']) ?> min</span>
              </div>
              <small class="text-muted"><?= htmlspecialchars($service['description']) ?></small>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p class="text-center">No services available at the moment.</p>
        <?php endif; ?>
      </div>

      <!-- Popular -->
      <div class="tab-pane fade" id="popular" role="tabpanel">
        <div class="service-card">
          <div class="d-flex justify-content-between">
            <span class="service-title">Coming Soon</span>
          </div>
        </div>
      </div>

      <!-- Recent -->
      <div class="tab-pane fade" id="recent" role="tabpanel">
        <div class="service-card">
          <div class="d-flex justify-content-between">
            Coming Soon
          </div>
        </div>
      </div>

      <!-- Selections -->
      <div class="tab-pane fade" id="selection" role="tabpanel">
        <div id="selectedServices"></div>
      </div>
    </div>

    <div class="d-grid mt-4">
      <a href="client-calender.php" class="btn btn-choose">Choose staff & time</a>
    </div>

    <div class="text-center mt-4 footer-text">
      <p>Terms & conditions | Feedback</p>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    const selectedServices = new Map();

    function updateSelections() {
      const container = document.getElementById("selectedServices");
      container.innerHTML = '';

      if (selectedServices.size === 0) {
        container.innerHTML = "<p class='text-muted'>No services selected yet.</p>";
      }

      selectedServices.forEach((service, id) => {
        const div = document.createElement("div");
        div.className = "service-card";
        div.innerHTML = `
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="service-title">${service.name}</span>
              <span class="service-price">$${service.price} · ${service.duration} min</span><br>
              <small class="text-muted">${service.description}</small>
            </div>
            <button class="btn btn-unselect" data-id="${id}" title="Unselect">×</button>
          </div>
        `;
        container.appendChild(div);
      });

      document.getElementById("selectionCounter").textContent = selectedServices.size;

      // Add event listeners to all "Unselect" buttons
      container.querySelectorAll(".btn-unselect").forEach(btn => {
        btn.addEventListener("click", function(e) {
          e.stopPropagation(); // Prevent triggering card click
          const id = btn.getAttribute("data-id");
          selectedServices.delete(id);
          // Remove "selected" style from card in All Services tab if present
          const allCard = document.querySelector(`#all .service-card[data-id="${id}"]`);
          if (allCard) allCard.classList.remove("selected");
          updateSelections();
        });
      });
    }

    document.addEventListener("DOMContentLoaded", function () {
      const serviceCards = document.querySelectorAll("#all .service-card");

      serviceCards.forEach((card) => {
        card.addEventListener("click", function () {
          const id = card.getAttribute("data-id");
          const name = card.querySelector(".service-title").textContent.trim();
          const priceDuration = card.querySelector(".service-price").textContent.trim();
          const description = card.querySelector("small").textContent.trim();

          const price = priceDuration.split('·')[0].replace('$', '').trim();
          const duration = priceDuration.split('·')[1].replace('min', '').trim();

          if (selectedServices.has(id)) {
            selectedServices.delete(id);
            card.classList.remove("selected");
          } else {
            selectedServices.set(id, { name, price, duration, description });
            card.classList.add("selected");
          }
          updateSelections();
        });
      });
    });
  </script>
</body>
</html>
