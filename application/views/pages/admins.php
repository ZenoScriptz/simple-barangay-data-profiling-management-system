<!DOCTYPE html>
<html lang="en">

<body style="min-width: auto">
    <div class="container mt-5">
        <div id="alertContainer" class="container mt-3" style="border-radius: 10px;	border: none;">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Admins</h3>
            <button class="btn btn-elections" type="button" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                <i class="bi bi-plus-circle"></i> Add Admin
            </button>
        </div>

        <div class="card shadow-sm" style="padding: 15px;border-radius:10px">
            <div class="card-body">
                <table id="adminsTable" class="table table-hover table-bordered">
                    <thead class="elections-header">
                        <tr>
                            <th scope="col">Email</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admins as $admin): ?>
                            <tr>
                                <td><?= htmlspecialchars($admin->username) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm me-2" style="background-color: #8d876a; color: white; border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#editAdminModal<?= $admin->id; ?>" title="Edit Admin">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" style="border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-id="<?= $admin->id; ?>" title="Delete Admin">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal for adding an admin -->
        <div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #a70807;">
                        <h5 class="modal-title" id="addAdminModalLabel" style="color: white;">Add Admin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="adminForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="username" class="form-label">Email</label>
                                            <input class="form-control custom-border" type="text" name="username" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="password" class="form-label">Password</label>
                                            <div class="input-group">
                                                <input class="form-control custom-border" type="password" name="password" id="addPasswordInput" required>
                                                <button type="button" class="btn btn-outline-secondary" id="toggleAddPassword">
                                                    <i class="bi bi-eye-fill" id="addEyeIcon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" style="border-radius: 20px;" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn" style="background-color: #a70807; color: white; border-radius: 20px; border-color: #a70807;" id="saveAdminButton">Add</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for editing an admin -->
        <?php foreach ($admins as $admin): ?>
            <div class="modal fade" id="editAdminModal<?= $admin->id; ?>" tabindex="-1" aria-labelledby="editAdminModalLabel<?= $admin->id; ?>" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #8d876a;">
                            <h5 class="modal-title" id="editAdminModalLabel<?= $admin->id; ?>" style="color: white;">Edit Admin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editAdminForm<?= $admin->id; ?>" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?= $admin->id; ?>">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="username" class="form-label">Email</label>
                                                <input class="form-control custom-border" type="text" name="username" value="<?= htmlspecialchars($admin->username) ?>" required>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col">
                                                <label for="password" class="form-label">Password</label>
                                                <div class="input-group">
                                                    <input class="form-control custom-border" type="password" name="password" id="passwordInput<?= $admin->id; ?>" required>
                                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword<?= $admin->id; ?>">
                                                        <i class="bi bi-eye-fill" id="eyeIcon<?= $admin->id; ?>"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" style="border-radius: 20px;" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" style="background-color: #8d876a; color: white; border-radius: 20px; border-color: #8d876a;" id="updateAdminButton<?= $admin->id; ?>">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Modal for confirming deletion -->
        <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this admin?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 20px;">Cancel</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteButton" style="border-radius: 20px;">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('assets/js/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/dataTables.bootstrap5.min.js'); ?>"></script>

    <script>
        $(document).ready(function() {
            function showAlert(message, type) {
                var alertDiv = $('<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                    '</div>');
                $('#alertContainer').html(alertDiv);

                // Auto-hide the alert after 5 seconds
                setTimeout(function() {
                    alertDiv.alert('close');
                }, 5000);
            }

            // Check if there's an alert message in local storage
            var alertMessage = localStorage.getItem('alertMessage');
            var alertType = localStorage.getItem('alertType');
            if (alertMessage && alertType) {
                showAlert(alertMessage, alertType);
                // Clear the local storage
                localStorage.removeItem('alertMessage');
                localStorage.removeItem('alertType');
            }

            // Toggle password visibility for Add Admin modal
            $('#toggleAddPassword').click(function() {
                var passwordInput = $('#addPasswordInput');
                var eyeIcon = $('#addEyeIcon');

                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    eyeIcon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
                } else {
                    passwordInput.attr('type', 'password');
                    eyeIcon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
                }
            });

            <?php foreach ($admins as $admin): ?>
                $('#togglePassword<?= $admin->id; ?>').click(function() {
                    var passwordInput = $('#passwordInput<?= $admin->id; ?>');
                    var eyeIcon = $('#eyeIcon<?= $admin->id; ?>');

                    if (passwordInput.attr('type') === 'password') {
                        passwordInput.attr('type', 'text');
                        eyeIcon.removeClass('bi-eye-fill').addClass('bi-eye-slash-fill');
                    } else {
                        passwordInput.attr('type', 'password');
                        eyeIcon.removeClass('bi-eye-slash-fill').addClass('bi-eye-fill');
                    }
                });
            <?php endforeach; ?>

            $('#adminsTable').DataTable();

            // Function to check form validity
            function checkFormValidity() {
                var form = $('#adminForm')[0];
                return form.checkValidity();
            }

            // Disable the "Add" button initially
            $('#saveAdminButton').prop('disabled', true);

            // Enable or disable the "Add" button based on form validity
            $('#adminForm input').on('input change', function() {
                if (checkFormValidity()) {
                    $('#saveAdminButton').prop('disabled', false);
                } else {
                    $('#saveAdminButton').prop('disabled', true);
                }
            });

            // Disable the "Add" button when the modal is shown
            $('#addAdminModal').on('shown.bs.modal', function() {
                $('#saveAdminButton').prop('disabled', true);
            });

            // Add Admin Form Submission
            $('#saveAdminButton').click(function() {
                var formData = new FormData($('#adminForm')[0]);
                $.ajax({
                    url: '<?php echo base_url('add_admin'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#addAdminModal').modal('hide');
                            $('#adminForm')[0].reset();
                            localStorage.setItem('alertMessage', res.message);
                            localStorage.setItem('alertType', 'success');
                            location.reload();
                        } else {
                            localStorage.setItem('alertMessage', res.message);
                            localStorage.setItem('alertType', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        localStorage.setItem('alertMessage', "Error: " + error);
                        localStorage.setItem('alertType', 'danger');
                    }
                });
            });

            // Edit Admin Form Submission
            <?php foreach ($admins as $admin): ?>
                $('#updateAdminButton<?= $admin->id; ?>').click(function() {
                    var formData = new FormData($('#editAdminForm<?= $admin->id; ?>')[0]);
                    $.ajax({
                        url: '<?php echo base_url('update_admin/' . $admin->id); ?>',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            var res = JSON.parse(response);
                            if (res.status === 'success') {
                                $('#editAdminModal<?= $admin->id; ?>').modal('hide');
                                $('#editAdminForm<?= $admin->id; ?>')[0].reset();
                                localStorage.setItem('alertMessage', res.message);
                                localStorage.setItem('alertType', 'success');
                                location.reload();
                            } else {
                                localStorage.setItem('alertMessage', res.message);
                                localStorage.setItem('alertType', 'danger');
                            }
                        },
                        error: function(xhr, status, error) {
                            localStorage.setItem('alertMessage', "Error: " + error);
                            localStorage.setItem('alertType', 'danger');
                        }
                    });
                });
            <?php endforeach; ?>

            $('.btn-danger').on('click', function() {
                var adminId = $(this).data('id');
                $('#confirmDeleteButton').attr('data-id', adminId);
                $('#confirmDeleteModal').modal('show');
            });

            $('#confirmDeleteButton').click(function() {
                var adminId = $(this).attr('data-id');
                $.ajax({
                    url: '<?php echo base_url('delete_admin/'); ?>' + adminId,
                    type: 'POST',
                    success: function(response) {
                        var res = JSON.parse(response);
                        if (res.status === 'success') {
                            $('#confirmDeleteModal').modal('hide');
                            localStorage.setItem('alertMessage', res.message);
                            localStorage.setItem('alertType', 'success');
                            location.reload();
                        } else {
                            localStorage.setItem('alertMessage', res.message);
                            localStorage.setItem('alertType', 'danger');
                        }
                    },
                    error: function(xhr, status, error) {
                        localStorage.setItem('alertMessage', "Error: " + error);
                        localStorage.setItem('alertType', 'danger');
                    }
                });
            });
        });
    </script>
</body>

</html>