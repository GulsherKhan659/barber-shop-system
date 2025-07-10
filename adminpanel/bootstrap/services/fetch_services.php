<?php
include("../../database/configue.php");
include("../../database/connection.php");

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

$services = $db->select('services');

if ($services) {
    foreach ($services as $service) {
        ?>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="font-weight-bold text-uppercase text-dark mb-0"><?= htmlspecialchars($service['name']) ?></h6>
                        <i class="fas fa-trash text-danger delete-service" 
                           data-id="<?= $service['id'] ?>" style="cursor:pointer;" 
                           title="Delete Service"></i>
                    </div>
                    <hr>
                    <div class="text-xs row mb-1">
                        <div class="col-6">Duration:</div>
                        <div class="col-6"><?= htmlspecialchars($service['duration_minutes']) ?> mins</div>
                    </div>
                    <div class="text-xs row mb-1">
                        <div class="col-6">Price:</div>
                        <div class="col-6">$<?= htmlspecialchars($service['price']) ?></div>
                    </div>
                    <div class="form-group mt-3">
                        <div class="row">
                            <div class="col-6">
                                <label class="text-xs"><strong>Status</strong></label>
                            </div>
                            <div class="col-6 text-end">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" 
                                           class="custom-control-input toggle-status" 
                                           id="statusSwitch<?= $service['id'] ?>" 
                                           data-id="<?= $service['id'] ?>" 
                                           <?= $service['is_active'] ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="statusSwitch<?= $service['id'] ?>">
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='col-12 text-center'>No services found.</div>";
}
?>
