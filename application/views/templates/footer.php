</main> <!-- Closes .main-content from header.php -->
    </div> <!-- Closes .app-container from header.php -->

    <!-- JAVASCRIPT LIBRARIES (Order is important) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js"></script>

    <!-- ONE SCRIPT TO RULE THEM ALL -->
    <script>
    $(document).ready(function() {
        
        // --- SIDEBAR TOGGLE ---
        $('#sidebarToggle').on('click', function() {
            $('#sidebar').toggleClass('collapsed');
        });

        // --- DATA TABLE INITIALIZATIONS ---

        if ($('#residentTable').length) {
            $('#residentTable').DataTable({ "processing": true, "serverSide": true, "order": [], "ajax": { "url": "<?= site_url('get_resident_data') ?>", "type": "POST" }, "columnDefs": [{ "orderable": false, "targets": 6 }] });
        }

        if ($('#familyListTable').length) {
            $('#familyListTable').DataTable({ "processing": true, "serverSide": true, "order": [[0, "asc"]], "ajax": { "url": "<?= site_url('get_resident_data') ?>", "type": "POST" }, "columns": [{ "data": 1 }, { "data": 7 }, { "data": 8 }, { "data": 3 }, { "data": 9 }] });
        }

        if ($('#secretaryTable').length) {
            $('#secretaryTable').DataTable({ "processing": true, "serverSide": true, "order": [], "ajax": { "url": "<?= site_url('get_secretary_data') ?>", "type": "POST" }, "columnDefs": [{ "orderable": false, "targets": 5 }] });
        }

        if ($('#activityLogTable').length) {
            $('#activityLogTable').DataTable({ "processing": true, "serverSide": true, "order": [[0, "desc"]], "ajax": { "url": "<?= site_url('get_log_data') ?>", "type": "POST" } });
        }

        if ($('#secretaryReportsTable').length) {
            $('#secretaryReportsTable').DataTable({ "processing": true, "serverSide": true, "order": [[2, "desc"]], "ajax": { "url": "<?= site_url('get_secretary_report_data') ?>", "type": "POST" }, "columns": [{ "data": "username" }, { "data": "brgy_name" }, { "data": "created_at" }, { "data": "message_content" }] });
        }

        // --- CHART INITIALIZATION FOR DASHBOARD ---
        
        if ($('#barangayResidentsBarChart').length) {
            var ctxBar = document.getElementById('barangayResidentsBarChart').getContext('2d');
            new Chart(ctxBar, { type: 'bar', data: { labels: <?= $barangay_chart_labels ?? '[]'; ?>, datasets: [{ label: 'Number of Residents', data: <?= $barangay_chart_data ?? '[]'; ?>, backgroundColor: 'rgba(75, 192, 192, 0.6)' }] }, options: { responsive: true, maintainAspectRatio: false, scales: { yAxes: [{ ticks: { beginAtZero: true, stepSize: 1 } }] } } });
        }
        if ($('#genderPieChart').length) {
            var ctxPie = document.getElementById('genderPieChart').getContext('2d');
            new Chart(ctxPie, { type: 'pie', data: { labels: ['Male', 'Female'], datasets: [{ data: [<?= $male_count_chart ?? 0; ?>, <?= $female_count_chart ?? 0; ?>], backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false } });
        }
        if ($('#voterDoughnutChart').length) {
            var ctxDoughnut = document.getElementById('voterDoughnutChart').getContext('2d');
            new Chart(ctxDoughnut, { type: 'doughnut', data: { labels: ['Registered Voters', 'Non-Voters'], datasets: [{ data: [<?= $voter_count_chart ?? 0; ?>, <?= $non_voter_count_chart ?? 0; ?>], backgroundColor: ['rgba(255, 159, 64, 0.7)', 'rgba(201, 203, 207, 0.7)'] }] }, options: { responsive: true, maintainAspectRatio: false } });
        }

        // --- SCRIPT FOR REPORTS PAGE ---

         if ($('#report-type').length) {

            // This is the correct logic for a pure Bootstrap checkbox dropdown.
            // It prevents the menu from closing when you click a checkbox.
            $('.dropdown-menu').on('click', function (e) {
                e.stopPropagation();
            });

            // Logic for the 'All Barangays' checkbox
            $('#barangay-all').on('change', function () {
                var isChecked = $(this).is(':checked');
                $('.barangay-checkbox:not(#barangay-all)').prop('checked', isChecked);
                updateDropdownButtonText();
            });

            // Logic for individual barangay checkboxes
            $('.barangay-checkbox:not(#barangay-all)').on('change', function () {
                let allAreChecked = $('.barangay-checkbox:not(#barangay-all):checked').length === $('.barangay-checkbox:not(#barangay-all)').length;
                $('#barangay-all').prop('checked', allAreChecked);
                updateDropdownButtonText();
            });

            // Helper function to update the button text
            function updateDropdownButtonText() {
                let count = $('.barangay-checkbox:not(#barangay-all):checked').length;
                if ($('#barangay-all').is(':checked')) {
                    $('#barangaysDropdown').text('All Barangays Selected');
                } else if (count > 0) {
                    $('#barangaysDropdown').text(count + ' Barangay(s) Selected');
                } else {
                    $('#barangaysDropdown').text('Select Barangays');
                }
            }
            
            // This function now correctly reads the CHECKBOXES
            function getSelectedBarangays() {
                var selected = [];
                if ($('#selected_barangay_id').length) {
                    selected.push($('#selected_barangay_id').val()); // For secretary view
                } else {
                    if ($('#barangay-all').is(':checked')) {
                        selected.push('all');
                    } else {
                        // This is the key part: it looks for checked checkboxes
                        $('.barangay-checkbox:not(#barangay-all):checked').each(function () {
                            selected.push($(this).val());
                        });
                    }
                }
                return selected;
            }

            // The button click handler is correct and does not need to change
            $('.btn-view-chart, .btn-download').on('click', function () {
                var reportType = $('#report-type').val();
                var selectedBarangays = getSelectedBarangays();
                if (!reportType) { alert('Please select a report type.'); return; }
                if (selectedBarangays.length === 0) { alert('Please select at least one barangay.'); return; }

                var section = $(this).data('section');
                var url = section === 'chart' ? "<?= site_url('reports/display_chart') ?>" : "<?= site_url('reports/download_report') ?>";
                var params = {
                    report_type: reportType,
                    barangays: selectedBarangays.join(',')
                };
                window.open(url + '?' + $.param(params), '_blank');
            });
         }
        // --- SCRIPT FOR REPORT CHART PAGE ---
     if ($('#myReportChart').length) {
        
        // 1. Get the chart data directly from the PHP variable
        var chartData = JSON.parse('<?= $chart_data_json ?? '{}'; ?>');
        
        // 2. Check if the data is valid
        if (chartData && chartData.labels && chartData.labels.length > 0) {
            var ctx = document.getElementById('myReportChart').getContext('2d');
            
            // 3. Render the chart
            new Chart(ctx, {
                type: '<?= $chart_type ?? 'bar'; ?>',
                data: {
                    labels: chartData.labels,
                    datasets: chartData.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        } else {
            // If no data, update the title to inform the user
            var titleElement = document.getElementById('chart-title');
            if (titleElement) {
                titleElement.textContent = 'No data available for the selected criteria.';
            }
        }

        function renderChart(data) {
            var ctx = document.getElementById('myReportChart').getContext('2d');
            var chartConfig = {
                type: 'bar', // Default
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: { responsive: true, maintainAspectRatio: false }
            };

            // Customize chart based on type
            if (reportType === 'gender-distribution') chartConfig.type = 'pie';
            else if (reportType === 'age-group') chartConfig.type = 'doughnut';
            else if (reportType === 'resident_type') chartConfig.type = 'pie';

            $('#chart-title').text(data.datasets[0].label || 'Generated Report');
            new Chart(ctx, chartConfig);
        }
    }
    });
    </script>

</body>
</html>