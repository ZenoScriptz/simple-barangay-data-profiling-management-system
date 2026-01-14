<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-body d-sm-flex align-items-center justify-content-between">
            <!-- THE TITLE IS NOW SET BY THE JAVASCRIPT -->
            <h1 id="chart-title" class="h3 mb-1 text-gray-800">Loading Report...</h1>
            <div>
                <button class="btn btn-primary btn-sm shadow-sm me-2" onclick="window.print();"><i class="fas fa-print fa-sm text-white-50"></i> Print</button>
                <a href="<?= site_url('reports'); ?>" class="btn btn-secondary btn-sm shadow-sm"><i class="fas fa-arrow-left fa-sm"></i> Back</a>
            </div>
        </div>
    </div>

    <!-- Chart & Data Table Card -->
    <div class="card shadow mb-4">
        <div class="card-body">
            
            <!-- THE FIX IS HERE: The chart canvas is now present -->
            <div class="chart-container mb-4" style="height: 400px;">
                <canvas id="myReportChart"></canvas>
            </div>
            
            <hr>
            
            <h5 class="mt-4">Data Table</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <?php if (!empty($headers)): foreach ($headers as $header): ?>
                                <th><?= htmlspecialchars($header); ?></th>
                            <?php endforeach; endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)): foreach ($results as $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <td><?= htmlspecialchars($cell); ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; else: ?>
                            <tr><td colspan="<?= count($headers ?? [1]); ?>" class="text-center">No data to display.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>