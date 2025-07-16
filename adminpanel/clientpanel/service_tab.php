<?php
include("../database/configue.php");
include("../database/connection.php");

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);
$services = $db->select("services", "*", ["is_active" => 1]);
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
      max-width: 740px;
      margin: 40px auto;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 32px rgba(0, 0, 0, 0.07);
      padding-bottom: 32px;
      border: 1px solid #e2e8f0;
    }

    .bg-warning {
      background: #f7fafd !important;
      color: #000 !important;
      font-size: 1.65rem;
      /* font-weight: 700; */
      letter-spacing: 1px;
      border-radius: 14px 14px 0 0;
      padding: 13px 20px 7px 20px;
      margin-bottom: 0;
      text-align: left;
      border-bottom: 1px solid #e2e8f0;
    }

    .nav-tabs {
      background: #f7fafd;
      border: none;
      padding: 0 40px;
      border-bottom: 1px solid #e2e8f0;
      margin-bottom: 0 !important;
    }

    .nav-tabs .nav-link {
      color: #5b6770;
      background: transparent;
      border: none;
      border-radius: 0;
      font-weight: 500;
      font-size: 1rem;
      padding: 12px 16px 12px 0;
      margin-right: 22px;
      border-bottom: 3px solid transparent;
      transition: all 0.18s;
    }

    .nav-tabs .nav-link.active,
    .nav-tabs .nav-link:focus,
    .nav-tabs .nav-link:hover {
      color: #000 !important;
      background: transparent !important;
      border-bottom: 3px solid rgb(40, 43, 41) !important;
    }

    .search-input {
      background: #f7fafd;
      border: 1px solid #e2e8f0;
      color: #333 !important;
      border-radius: 8px;
      margin: 20px 0 18px 0;
      font-size: 1rem;
      padding: 10px 16px;
    }

    .search-input::placeholder {
      color: #9eabbc !important;
    }

    .tab-content {
      margin-top: 0;
    }

    .service-card {
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 18px 28px 10px 28px;
      margin-bottom: 14px;
      cursor: pointer;
      box-shadow: 0 1px 6px rgba(34, 54, 80, 0.03);
      transition: background 0.15s, border-color 0.15s;
      position: relative;
      min-height: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .service-card.selected {
      background: #e8f5e9;
      border-color: rgb(58, 59, 59);
    }

    .service-title {
      font-size: 1.08rem;
      font-weight: 600;
      color: #222;
    }

    .service-price {
      color: rgb(33, 36, 34);
      font-weight: 700;
      font-size: 1rem;
      margin-left: 12px;
      white-space: nowrap;
    }

    .service-card small.text-muted {
      color: #607080 !important;
      font-size: 0.98rem;
      margin-top: 3px;
      display: block;
    }

    .d-flex.justify-content-between {
      align-items: baseline;
    }

    .btn-choose {
      background: #000 !important;
      color: #fff !important;
      font-weight: 700;
      border-radius: 8px;
      font-size: 1.13rem;
      padding: 13px 0;
      margin: 34px 0 0 0;
      box-shadow: 0 1px 12px rgba(34, 54, 80, 0.08);
      border: none !important;
      transition: background 0.15s;
    }

    .btn-choose:hover {

      background: rgb(57, 59, 58) !important;
    }

    .footer-text {
      color: #8fa1b4 !important;
      font-size: 1.04rem !important;
      margin-top: 26px;
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
      margin-top: -4px;
    }

    .btn-unselect:hover {
      background: #b02a37 !important;
    }

    .select-price {
      width: 220px;
      position: fixed;
      top: 100px;
      right: 0px
    }

    @media (max-width: 800px) {
      .select-price {
        position: fixed;
        top: 25%;
        right: 0px
      }

      .container {
        padding: 0;
        border-radius: 0;
      }

      .bg-warning,
      .nav-tabs,
      .service-card {
        padding-left: 15px !important;
        padding-right: 15px !important;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>


  <div class="container">
    <div class="offset-md-3 col-md-6 col-12 offset-0 ">
      <div class="d-flex justify-content-center w-100 pt-4 pb-2">
        <img src="assets/shop-logo.png" style="width: 25%;" alt="" srcset="">

      </div>
      <div class="h6 bg-warning text-center">RAREBARBER </div>
    </div>
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
            <div class="service-card" data-id="<?= $service['id'] ?>">
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
      <form id="bookingForm" method="post" action="client_calender.php">
        <input type="hidden" name="selected_services" id="selected_services" value="">
        <button type="submit" class="btn btn-choose form-control">Choose staff & time</button>
      </form>
    </div>


    <div class="text-center mt-4 footer-text">
      <p>Terms & conditions | Feedback</p>
    </div>

  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById("bookingForm").addEventListener("submit", function(e) {
      const dataArr = Array.from(selectedServices.entries()).map(([id, obj]) => ({
        id,
        ...obj
      }));
      document.getElementById("selected_services").value = JSON.stringify(dataArr);
    });

    // console.log("selectedservices................", dataArr);
    const selectedServices = new Map();

    function updateSelections() {
      const container = document.getElementById("selectedServices");
      container.innerHTML = '';

      if (selectedServices.size === 0) {
        container.innerHTML = "<p class='text-muted'>No services selected yet.</p>";
        document.getElementById("selectionCounter").textContent = 0;
        return;
      }

      selectedServices.forEach((service, id) => {
        const div = document.createElement("div");
        div.className = "service-card selected";
        div.setAttribute("data-id", id);
        div.innerHTML = `

          <div class="d-flex justify-content-between">
                <span class="service-title">${service.name}</span>
                <span class="service-price">$ ${service.price} · ${service.duration} min</span>
              </div>
              <small class="text-muted">${service.description}</small>
     

      `;


        div.addEventListener("click", function(e) {
          if (!e.target.classList.contains("btn-unselect")) {
            selectedServices.delete(id);
            const allCard = document.querySelector(`#all .service-card[data-id="${id}"]`);
            if (allCard) allCard.classList.remove("selected");
            updateSelections();
          }
        });
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
      console.log("SelectedServices................", Array.from(selectedServices.entries()));

    }


    document.addEventListener("DOMContentLoaded", function() {
      const serviceCards = document.querySelectorAll("#all .service-card");

      serviceCards.forEach((card) => {
        card.addEventListener("click", function() {
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
            selectedServices.set(id, {
              name,
              price,
              duration,
              description
            });
            card.classList.add("selected");
          }
          updateSelections();
        });
      });
    });
  </script>
</body>

</html>