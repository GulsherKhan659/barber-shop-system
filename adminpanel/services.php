<?php
include("./database/configue.php");
include("./database/connection.php");

$config = new Configue();
$db = new Database($config->servername, $config->database, $config->username, $config->password);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $data = [
        'shop_id' => 1,
        'name' => $_POST['name'],
        'price' => $_POST['price'],
        'duration_minutes' => $_POST['duration_minutes'],
        'description' => $_POST['description'],
        'is_active' => 1
    ];
    $db->insert('services', $data);
    header("Location: services.php"); exit;
}

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $db->delete('services', ['id' => $id]);
    header("Location: services.php"); exit;
}

if (isset($_GET['toggle_id']) && isset($_GET['status'])) {
    $id = intval($_GET['toggle_id']);
    $isActive = intval($_GET['status']) ? 1 : 0;
    $db->update('services', ['is_active' => $isActive], ['id' => $id]);
    header("Location: services.php"); exit;
}

$services = $db->select('services');
?>

<?php include("./partial/header.php"); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">The RareBarber Services</h1>
        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus"></i> Add Service
        </button>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form method="POST" action="services.php" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Service</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="add_service" value="1">
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="name" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Price ($)</label>
                        <input type="number" name="price" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Duration (minutes)</label>
                        <input type="number" name="duration_minutes" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Service</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <?php if ($services): foreach ($services as $service): ?>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold text-uppercase text-dark mb-0">
                                <?= htmlspecialchars($service['name']) ?>
                            </h6>
                            <a href="services.php?delete_id=<?= $service['id'] ?>"
                               onclick="return confirm('Delete this service?');">
                                <i class="fas fa-trash text-danger" title="Delete Service"></i>
                            </a>
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
                                               class="custom-control-input"
                                               id="statusSwitch<?= $service['id'] ?>"
                                               onchange="toggleStatus(<?= $service['id'] ?>, this.checked)"
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
        <?php endforeach; else: ?>
            <div class="col-12 text-center">No services found.</div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleStatus(id, isActive) {
    window.location.href = `services.php?toggle_id=${id}&status=${isActive ? 1 : 0}`;
}
</script>

<?php include("./partial/footer.php"); ?>
