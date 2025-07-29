<ul style="background-color: #000 !important;" class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                    <img style="width: 55px;height:45px;" src="img/mobile-logo.png" alt="" srcset="">
                <div class="sidebar-brand-text mx-3">The Rarebarber</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <hr class="sidebar-divider">

            <?php if ($_SESSION['user_role'] !== 'staff'): ?>

                <!-- Staff Tab -->
                <li class="nav-item">
                    <a class="nav-link" href="staff.php">
                        <i class="fas fa-fw fa-user-friends"></i>
                        <span>Staff</span>
                    </a>
                </li>

                <!-- Services Tab -->
                <li class="nav-item">
                    <a class="nav-link" href="services.php">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>Services</span>
                    </a>
                </li>

                <!-- Payment Tab -->
                <li class="nav-item">
                    <a class="nav-link" href="payment.php">
                        <i class="fas fa-fw fa-money-bill"></i>
                        <span>Payment</span>
                    </a>
                </li>

            <?php endif; ?>
            <li class="nav-item">
                <a class="nav-link " href="avalibity.php" >
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Availability</span>
                </a>
            </li>

            <hr class="sidebar-divider">

              <li class="nav-item">
                <a class="nav-link " href="booking.php" >
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Booking</span>
                </a>
                
            </li>
            <hr class="sidebar-divider d-none d-md-block">

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
            <div class="sidebar-card d-none d-lg-flex">
                <img class="sidebar-card-illustration mb-2" width="100%"   src="img/logo.png" alt="...">
            </div>

        </ul>

