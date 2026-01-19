<?php
require_once 'auth_check.php';
require_once '../config.php';

// Fetch total MCQs count
$total_mcqs_count = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM mcqs");
    $total_mcqs_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $total_mcqs_count = 0;
}

// Fetch topics count from specific categories (1, 4, 7)
$topics_count = 0;
try {
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT t.id) 
        FROM topics t 
        INNER JOIN subjects s ON t.subject_id = s.id 
        INNER JOIN mcq_categories mc ON s.category_id = mc.id 
        WHERE mc.id IN (1, 4, 7)
    ");
    $topics_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $topics_count = 0;
}

$past_paper_count = 0;
try {
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT t.id) 
        FROM topics t 
        INNER JOIN subjects s ON t.subject_id = s.id 
        INNER JOIN mcq_categories mc ON s.category_id = mc.id 
        WHERE mc.id IN (2, 5, 8)
    ");
    $past_paper_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $past_paper_count = 0;
}

$mock_test_count = 0;
try {
    $stmt = $pdo->query("
        SELECT COUNT(DISTINCT t.id) 
        FROM topics t 
        INNER JOIN subjects s ON t.subject_id = s.id 
        INNER JOIN mcq_categories mc ON s.category_id = mc.id 
        WHERE mc.id IN (3, 6, 9)
    ");
    $mock_test_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $mock_test_count = 0;
}

$latest_mcqs = [];
try {
    $stmt = $pdo->query("
        SELECT 
            m.id,
            m.question,
            t.name as topic_name,
            s.name as subject_name,
            mc.name as category_name,
            mt.name as mcq_type_name
            FROM mcqs m
            INNER JOIN topics t ON m.topic_id = t.id
            INNER JOIN subjects s ON t.subject_id = s.id
            INNER JOIN mcq_categories mc ON s.category_id = mc.id
            INNER JOIN mcq_types mt ON mc.mcq_type_id = mt.id
            ORDER BY m.id DESC
            LIMIT 3
    ");
    $latest_mcqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $latest_mcqs = [];
}

// Fetch MCQ upload data for each month of current year
$mcq_monthly_data = [];
try {
    $current_year = date('Y');
    $stmt = $pdo->query("
        SELECT 
            MONTH(created_at) as month,
            COUNT(*) as count
        FROM mcqs 
        WHERE YEAR(created_at) = $current_year
        GROUP BY MONTH(created_at)
        ORDER BY month
    ");
    $monthly_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialize array with 0 for all months
    for ($i = 1; $i <= 12; $i++) {
        $mcq_monthly_data[$i] = 0;
    }

    // Fill in actual data
    foreach ($monthly_results as $row) {
        $mcq_monthly_data[(int)$row['month']] = (int)$row['count'];
    }
} catch (Exception $e) {
    // If error, use default data
    $mcq_monthly_data = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title -->
    <title>Maxxify Academy</title>
    <!-- Favicon -->
    <link rel="shortcut icon" href="assets/images/logo/favicon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/file-upload.css">
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/plyr.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css">
    <!-- full calendar -->
    <link rel="stylesheet" href="assets/css/full-calendar.css">
    <!-- jquery Ui -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="assets/css/editor-quill.css">
    <!-- apex charts Css -->
    <link rel="stylesheet" href="assets/css/apexcharts.css">
    <!-- calendar Css -->
    <link rel="stylesheet" href="assets/css/calendar.css">
    <!-- jvector map Css -->
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <div class="side-overlay"></div>

    <?php include "sidebar.php" ?>

    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>

        <div class="dashboard-body">
            <div class="row gy-4">
                <div class="col-lg-9">
                    <div class="row gy-4">
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2"><?php echo number_format($total_mcqs_count); ?></h4>
                                    <span class="text-gray-600">Total MCQs</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span
                                            class="flex-center w-48 h-48 rounded-circle bg-main-600 text-white text-2xl">
                                            <i class="ph-fill ph-list-checks"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2"><?php echo number_format($topics_count); ?></h4>
                                    <span class="text-gray-600">Topic Added</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span
                                            class="flex-center w-48 h-48 rounded-circle bg-main-two-600 text-white text-2xl">
                                            <i class="ph-fill ph-bookmarks"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2"><?php echo number_format($past_paper_count); ?></h4>
                                    <span class="text-gray-600">Past Papers</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span
                                            class="flex-center w-48 h-48 rounded-circle bg-purple-600 text-white text-2xl">
                                            <i class="ph-fill ph-archive-box"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2"><?php echo number_format($mock_test_count); ?></h4>
                                    <span class="text-gray-600">Mock Tests</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span
                                            class="flex-center w-48 h-48 rounded-circle bg-warning-600 text-white text-2xl">
                                            <i class="ph-fill ph-clipboard-text"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-24">
                        <div class="card-body">
                            <div class="mb-20 flex-between flex-wrap gap-8">
                                <h4 class="mb-0">MCQ Upload Stats</h4>
                            </div>
                            <div id="doubleLineChart" class="tooltip-style y-value-left"></div>
                        </div>
                    </div>
                    <div class="card mt-24">
                        <div class="card-body">
                            <h5>Recently Uploaded MCQs</h5>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Type</th>
                                        <th>Category</th>
                                        <th>Subject</th>
                                        <th>Topic</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($latest_mcqs)): ?>
                                        <?php foreach ($latest_mcqs as $index => $mcq): ?>
                                            <tr>
                                                <td><?php echo $index + 1; ?></td>
                                                <td><?php echo htmlspecialchars(substr($mcq['question'], 0, 50)) . (strlen($mcq['question']) > 50 ? '...' : ''); ?></td>
                                                <td><?php echo htmlspecialchars($mcq['mcq_type_name']); ?></td>
                                                <td><?php echo htmlspecialchars($mcq['category_name']); ?></td>
                                                <td><?php echo htmlspecialchars($mcq['subject_name']); ?></td>
                                                <td><?php echo htmlspecialchars($mcq['topic_name']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No MCQs found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6>Quick Actions</h6>
                            <a href="mcqs.php" class="btn btn-primary btn-sm w-100 mt-2">Add MCQ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>

    <!-- Jquery js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <!-- Phosphor Js -->
    <script src="assets/js/phosphor-icon.js"></script>
    <!-- file upload -->
    <script src="assets/js/file-upload.js"></script>
    <!-- file upload -->
    <script src="assets/js/plyr.js"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <!-- full calendar -->
    <script src="assets/js/full-calendar.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/editor-quill.js"></script>
    <!-- apex charts -->
    <script src="assets/js/apexcharts.min.js"></script>
    <!-- Calendar Js - Removed as not needed on this page -->
    <!-- jvectormap Js -->
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>

    <!-- main js -->
    <script src="assets/js/main.js"></script>




    <script>
        function createChart(chartId, chartColor) {

            let currentYear = new Date().getFullYear();

            var options = {
                series: [{
                    name: 'series1',
                    data: [18, 25, 22, 40, 34, 55, 50, 60, 55, 65],
                }, ],
                chart: {
                    type: 'area',
                    width: 80,
                    height: 42,
                    sparkline: {
                        enabled: true // Remove whitespace
                    },

                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 1,
                    colors: [chartColor],
                    lineCap: 'round'
                },
                grid: {
                    show: true,
                    borderColor: 'transparent',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                fill: {
                    type: 'gradient',
                    colors: [chartColor],
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        gradientToColors: [`${chartColor}00`],
                        inverseColors: false,
                        opacityFrom: .5,
                        opacityTo: 0.3,
                        stops: [0, 100],
                    },
                },
                markers: {
                    colors: [chartColor],
                    strokeWidth: 2,
                    size: 0,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [`Jan ${currentYear}`, `Feb ${currentYear}`, `Mar ${currentYear}`, `Apr ${currentYear}`, `May ${currentYear}`, `Jun ${currentYear}`, `Jul ${currentYear}`, `Aug ${currentYear}`, `Sep ${currentYear}`, `Oct ${currentYear}`, `Nov ${currentYear}`, `Dec ${currentYear}`],
                    tooltip: {
                        enabled: false,
                    },
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }
        if (document.querySelector('#complete-course')) {
            createChart('complete-course', '#2FB2AB');
        }
        if (document.querySelector('#earned-certificate')) {
            createChart('earned-certificate', '#27CFA7');
        }
        if (document.querySelector('#course-progress')) {
            createChart('course-progress', '#6142FF');
        }
        if (document.querySelector('#community-support')) {
            createChart('community-support', '#FA902F');
        }

        function createLineChart(chartId, chartColor) {
            var mcqData = <?php echo json_encode(array_values($mcq_monthly_data)); ?>;

            var options = {
                series: [{
                    name: 'MCQs Uploaded',
                    data: mcqData,
                }, ],
                chart: {
                    type: 'area',
                    width: '100%',
                    height: 300,
                    sparkline: {
                        enabled: false
                    },
                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                colors: [chartColor],
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                    colors: [chartColor],
                    lineCap: 'round',
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.9,
                        opacityTo: 0.2,
                        stops: [0, 100]
                    }
                },
                grid: {
                    show: true,
                    borderColor: '#E6E6E6',
                    strokeDashArray: 3,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: 0,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                markers: {
                    colors: [chartColor],
                    strokeWidth: 3,
                    size: 0,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [`Jan`, `Feb`, `Mar`, `Apr`, `May`, `Jun`, `Jul`, `Aug`, `Sep`, `Oct`, `Nov`, `Dec`],
                    tooltip: {
                        enabled: false,
                    },
                    labels: {
                        formatter: function(value) {
                            return value;
                        },
                        style: {
                            fontSize: "14px"
                        }
                    },
                },
                yaxis: {
                    labels: {
                        show: true,
                        style: {
                            fontSize: "12px",
                            colors: "#666"
                        }
                    },
                    min: 0,
                    tickAmount: 5
                },
                tooltip: {
                    y: {
                        formatter: function(value) {
                            return value + " MCQs";
                        }
                    }
                },
                legend: {
                    show: false,
                    position: 'top',
                    horizontalAlign: 'right',
                    offsetX: -10,
                    offsetY: -0
                }
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }
        createLineChart('doubleLineChart', '#27CFA7');
        var options = {
            series: [100, 60, 25],
            chart: {
                height: 172,
                type: 'radialBar',
            },
            colors: ['#3D7FF9', '#27CFA7', '#020203'],
            stroke: {
                lineCap: 'round',
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '30%',
                    },
                    dataLabels: {
                        name: {
                            fontSize: '16px',
                        },
                        value: {
                            fontSize: '16px',
                        },
                        total: {
                            show: true,
                            formatter: function(w) {
                                return '82%'
                            }
                        }
                    }
                }
            },
            labels: ['Completed', 'In Progress', 'Not Started'],
        };

        // Only create radial chart if element exists
        if (document.querySelector("#radialMultipleBar")) {
            var chart = new ApexCharts(document.querySelector("#radialMultipleBar"), options);
            chart.render();
        }

        // Only add export functionality if element exists
        if (document.getElementById('exportOptions')) {
            document.getElementById('exportOptions').addEventListener('change', function() {
                const format = this.value;
                const table = document.getElementById('studentTable');
                let data = [];
                const headers = [];

                table.querySelectorAll('thead th').forEach(th => {
                    headers.push(th.innerText.trim());
                });
                table.querySelectorAll('tbody tr').forEach(tr => {
                    const row = {};
                    tr.querySelectorAll('td').forEach((td, index) => {
                        row[headers[index]] = td.innerText.trim();
                    });
                    data.push(row);
                });

                if (format === 'csv') {
                    downloadCSV(data);
                } else if (format === 'json') {
                    downloadJSON(data);
                }
            });
        }

        function downloadCSV(data) {
            const csv = data.map(row => Object.values(row).join(',')).join('\n');
            const blob = new Blob([csv], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'students.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function downloadJSON(data) {
            const json = JSON.stringify(data, null, 2);
            const blob = new Blob([json], {
                type: 'application/json'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'students.json';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>


</body>

</html>