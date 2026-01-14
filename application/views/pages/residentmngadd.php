<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h1 class="h3 mb-1 text-gray-800">Add New Resident</h1>
            <p class="mb-0 text-muted">Enter the details for the new resident.</p>
        </div>
    </div>
    
    <!-- Main Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">New Resident Form</h6>
        </div>
        <div class="card-body">
            <form id="addresident" method="post" action="<?= site_url('residents/create'); ?>">
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="first_name" class="form-label font-weight-bold">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="middle_name" class="form-label font-weight-bold">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Enter Middle Name">
                        </div>
                        <div class="form-group mb-3">
                            <label for="last_name" class="form-label font-weight-bold">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="birth_date" class="form-label font-weight-bold">Birthdate</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="gender" class="form-label font-weight-bold">Gender</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">-- Select --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="religion" class="form-label font-weight-bold">Religion</label>
                            <input type="text" class="form-control" id="religion" name="religion" placeholder="e.g., Roman Catholic">
                        </div>
                        <div class="form-group mb-3">
                            <label for="contact_number" class="form-label font-weight-bold">Contact Number</label>
                            <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter Contact Number">
                        </div>
                        <div class="form-group mb-3">
                            <label for="occupation" class="form-label font-weight-bold">Occupation</label>
                            <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation">
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="brgy_ID" class="form-label font-weight-bold">Barangay</label>
                            <select class="form-select" id="brgy_ID" name="brgy_ID" required>
                                <option value="">-- Select a Barangay --</option>
                                <?php if (!empty($barangays)): foreach ($barangays as $barangay): ?>
                                    <option value="<?= $barangay->brgy_ID; ?>"><?= $barangay->brgy_name; ?></option>
                                <?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="street" class="form-label font-weight-bold">Street Address</label>
                            <input type="text" class="form-control" id="street" name="street" placeholder="e.g., 123 Mabini Street">
                        </div>
                        <div class="form-group mb-3">
                            <label for="purok" class="form-label font-weight-bold">Purok / Zone</label>
                            <input type="text" class="form-control" id="purok" name="purok" placeholder="e.g., Purok 5">
                        </div>
                        <div class="form-group mb-3">
                            <label for="resident_type" class="form-label font-weight-bold">Resident Type</label>
                            <select class="form-select" id="resident_type" name="resident_type" required>
                                <option value="">-- Select --</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="resident_role" class="form-label font-weight-bold">Role in Family</label>
                            <select class="form-select" id="resident_role" name="resident_role" required>
                                <option value="">-- Select --</option>
                                <option value="Head">Head</option>
                                <option value="Member">Member</option>
                                <option value="Individual">Individual</option>
                            </select>
                        </div>
                        <hr>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" value="1" id="isVoter" name="isVoter">
                            <label class="form-check-label" for="isVoter">
                                Registered Voter?
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="isStudying" name="isStudying">
                            <label class="form-check-label" for="isStudying">
                                Currently Studying?
                            </label>
                        </div>
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Add Resident</button>
                <a href="<?= site_url('residents'); ?>" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>