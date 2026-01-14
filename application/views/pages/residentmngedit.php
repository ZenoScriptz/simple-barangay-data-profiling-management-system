<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-1 text-gray-800">Edit Resident</h1>
            <p class="mb-0 text-muted">Update the details for the selected resident.</p>
        </div>
    </div>

    <!-- Flash Messages for Errors -->
    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= $this->session->flashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Resident Details</h6>
        </div>
        <div class="card-body">
            <?php if (isset($resident) && $resident): ?>
                <!-- THE FIX IS HERE: The form action now points to the correct 'residents/update' route -->
                <form id="editresidentform" method="post" action="<?= site_url('residents/update/' . $resident->resident_ID); ?>">
                    <div class="row">
                        <!-- Column 1 -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="first_name" class="form-label font-weight-bold">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= set_value('first_name', $resident->first_name); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="middle_name" class="form-label font-weight-bold">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= set_value('middle_name', $resident->middle_name); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="last_name" class="form-label font-weight-bold">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= set_value('last_name', $resident->last_name); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="birth_date" class="form-label font-weight-bold">Birthdate</label>
                                <input type="date" class="form-control" id="birth_date" name="birth_date" value="<?= set_value('birth_date', $resident->birth_date); ?>" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="gender" class="form-label font-weight-bold">Gender</label>
                                <select class="form-select" id="gender" name="gender" required>
                                    <option value="Male" <?= set_select('gender', 'Male', ($resident->gender == 'Male')); ?>>Male</option>
                                    <option value="Female" <?= set_select('gender', 'Female', ($resident->gender == 'Female')); ?>>Female</option>
                                    <option value="Other" <?= set_select('gender', 'Other', ($resident->gender == 'Other')); ?>>Other</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="religion" class="form-label font-weight-bold">Religion</label>
                                <input type="text" class="form-control" id="religion" name="religion" value="<?= set_value('religion', $resident->religion); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="contact_number" class="form-label font-weight-bold">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?= set_value('contact_number', $resident->contact_number); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="occupation" class="form-label font-weight-bold">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation" value="<?= set_value('occupation', $resident->occupation); ?>">
                            </div>
                        </div>
                        <!-- Column 2 -->
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="brgy_ID" class="form-label font-weight-bold">Barangay</label>
                                <select class="form-select" id="brgy_ID" name="brgy_ID" required>
                                    <?php foreach ($barangays as $barangay): ?>
                                        <option value="<?= $barangay->brgy_ID; ?>" <?= set_select('brgy_ID', $barangay->brgy_ID, ($resident->brgy_ID == $barangay->brgy_ID)); ?>>
                                            <?= $barangay->brgy_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="street" class="form-label font-weight-bold">Street Address</label>
                                <input type="text" class="form-control" id="street" name="street" value="<?= set_value('street', $resident->street); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="purok" class="form-label font-weight-bold">Purok / Zone</label>
                                <input type="text" class="form-control" id="purok" name="purok" value="<?= set_value('purok', $resident->purok); ?>">
                            </div>
                            <div class="form-group mb-3">
                                <label for="resident_type" class="form-label font-weight-bold">Resident Type</label>
                                <select class="form-select" id="resident_type" name="resident_type" required>
                                    <option value="Permanent" <?= set_select('resident_type', 'Permanent', ($resident->resident_type == 'Permanent')); ?>>Permanent</option>
                                    <option value="Temporary" <?= set_select('resident_type', 'Temporary', ($resident->resident_type == 'Temporary')); ?>>Temporary</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="resident_role" class="form-label font-weight-bold">Role in Family</label>
                                <select class="form-select" id="resident_role" name="resident_role" required>
                                    <option value="Head" <?= set_select('resident_role', 'Head', ($resident->resident_role == 'Head')); ?>>Head</option>
                                    <option value="Member" <?= set_select('resident_role', 'Member', ($resident->resident_role == 'Member')); ?>>Member</option>
                                    <option value="Individual" <?= set_select('resident_role', 'Individual', ($resident->resident_role == 'Individual')); ?>>Individual</option>
                                </select>
                            </div>
                            <hr>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="isVoter" name="isVoter" <?= set_checkbox('isVoter', '1', ($resident->isVoter == 1)); ?>>
                                <label class="form-check-label" for="isVoter">Registered Voter?</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="isStudying" name="isStudying" <?= set_checkbox('isStudying', '1', ($resident->isStudying == 1)); ?>>
                                <label class="form-check-label" for="isStudying">Currently Studying?</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary">Update Resident</button>
                    <!-- THE FIX IS HERE: The cancel link now points to the correct 'residents' route -->
                    <a href="<?= site_url('residents'); ?>" class="btn btn-secondary">Cancel</a>
                </form>
            <?php else: ?>
                <p class="text-danger">Resident data not found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>