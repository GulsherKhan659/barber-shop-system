<?php include("./database/connection.php") ?>
<?php include("./partial/header.php") ?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">The RareBarber Services</h1>
        <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-plus"></i> Add Service
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Service</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="serviceForm">
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveService">Save Service</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Service List -->
    <div class="row" id="serviceList"></div>

</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    function loadServices() {
        $.ajax({
            url: "./bootstrap/services/fetch_services.php",
            success: function(data) {
                $("#serviceList").html(data);
            }
        });
    }

    // Save Service
    $("#saveService").click(function() {
        $.ajax({
            url: "./bootstrap/services/add_service.php",
            type: "POST",
            data: $("#serviceForm").serialize(),
            success: function(response) {
                alert(response);
                $('#exampleModal').modal('hide');
                loadServices();
            },
            error: function() {
                alert("Failed to add service.");
            }
        });
    });

    // Delete service
    $(document).on("click", ".delete-service", function () {
        const id = $(this).data("id");
        if (confirm("Are you sure you want to delete this service?")) {
            $.ajax({
                url: "./bootstrap/services/delete_service.php",
                type: "POST",
                data: { id: id },
                success: function (response) {
                    alert(response.trim());
                    loadServices(); 
                },
                error: function () {
                    alert("Delete request failed.");
                }
            });
        }
    });

    // Toggle active status
    $(document).on("change", ".toggle-status", function () {
        const id = $(this).data("id");
        const is_active = $(this).is(":checked") ? 1 : 0;

        $.post("./bootstrap/services/service_toggle_status.php", { id: id, is_active: is_active }, function (response) {
            console.log(response);
        });
    });

    loadServices();
});
</script>

<?php include("./partial/footer.php"); ?>
