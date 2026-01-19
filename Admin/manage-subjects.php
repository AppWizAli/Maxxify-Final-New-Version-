<?php require_once 'auth_check_mcq.php'; ?>
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

    <!--==================== Preloader Start ====================-->
    <div class="preloader">
        <div class="loader"></div>
    </div>
    <!--==================== Preloader End ====================-->

    <!--==================== Sidebar Overlay End ====================-->
    <div class="side-overlay"></div>
    <!--==================== Sidebar Overlay End ====================-->

    <!-- ============================ Sidebar Start ============================ -->
    <?php include "sidebar.php" ?>
    <!-- ============================ Sidebar End  ============================ -->

    <div class="dashboard-main-wrapper">
        <?php include "Includes/Header.php" ?>
        <div class="dashboard-body">
            <div class="row gy-4">
                <div class="col-lg-9">

                    <!-- Widgets Start -->
                    <div class="row gy-4">
                        <!-- Total MCQs -->
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2">7,240</h4>
                                    <span class="text-gray-600">Total MCQs</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span class="flex-center w-48 h-48 rounded-circle bg-main-600 text-white text-2xl">
                                            <i class="ph-fill ph-list-checks"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chapter-wise Topics -->
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2">84</h4>
                                    <span class="text-gray-600">Topics Added</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span class="flex-center w-48 h-48 rounded-circle bg-main-two-600 text-white text-2xl">
                                            <i class="ph-fill ph-bookmarks"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Past Papers -->
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2">29</h4>
                                    <span class="text-gray-600">Past Papers</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span class="flex-center w-48 h-48 rounded-circle bg-purple-600 text-white text-2xl">
                                            <i class="ph-fill ph-archive-box"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mock Tests -->
                        <div class="col-xxl-3 col-sm-6">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="mb-2">13</h4>
                                    <span class="text-gray-600">Mock Tests</span>
                                    <div class="flex-between gap-8 mt-16">
                                        <span class="flex-center w-48 h-48 rounded-circle bg-warning-600 text-white text-2xl">
                                            <i class="ph-fill ph-clipboard-text"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Widgets End -->

                    <!-- Chart Section -->
                    <div class="card mt-24">
                        <div class="card-body">
                            <div class="mb-20 flex-between flex-wrap gap-8">
                                <h4 class="mb-0">MCQ Upload Stats</h4>
                                <div class="flex-align gap-16 flex-wrap">
                                    <select class="form-select text-13 px-8 py-8 rounded-8">
                                        <option>Today</option>
                                        <option>Weekly</option>
                                        <option>Monthly</option>
                                        <option>Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div id="doubleLineChart" class="tooltip-style y-value-left"></div>
                        </div>
                    </div>

                    <!-- Recent Uploads -->
                    <div class="card mt-24">
                        <div class="card-body">
                            <h5>Recently Uploaded MCQs</h5>
                            <table class="table mt-3">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Type</th>
                                        <th>Subject</th>
                                        <th>Added By</th>
                                        <th>Method</th>
                                        <th>PDF</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Row -->
                                    <tr>
                                        <td>1</td>
                                        <td>What is the value of ...</td>
                                        <td>Past Paper</td>
                                        <td>Chemistry</td>
                                        <td>Admin1</td>
                                        <td>PDF Upload</td>
                                        <td><a href="#">View PDF</a></td>
                                    </tr>
                                    <!-- Dynamic rows here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Calendar + Actions -->
                <div class="col-lg-3">
                    <!-- Calendar Start -->
                    <div class="card">
                        <div class="card-body">
                            <div class="calendar">
                                <div class="calendar__header">
                                    <button class="calendar__arrow left"><i class="ph ph-caret-left"></i></button>
                                    <p class="display h6 mb-0">June 2025</p>
                                    <button class="calendar__arrow right"><i class="ph ph-caret-right"></i></button>
                                </div>
                                <div class="calendar__week week">
                                    <div class="calendar__week-text">Su</div>
                                    <div class="calendar__week-text">Mo</div>
                                    <div class="calendar__week-text">Tu</div>
                                    <div class="calendar__week-text">We</div>
                                    <div class="calendar__week-text">Th</div>
                                    <div class="calendar__week-text">Fr</div>
                                    <div class="calendar__week-text">Sa</div>
                                </div>
                                <div class="days"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Calendar End -->

                    <!-- Quick Actions -->
                    <div class="card mt-4">
                        <div class="card-body">
                            <h6>Quick Actions</h6>
                            <button class="btn btn-primary btn-sm w-100 mt-2">Add MCQ</button>
                            <button class="btn btn-outline-secondary btn-sm w-100 mt-2" style="color: black;">Upload Past Paper</button>
                            <button class="btn btn-outline-secondary btn-sm w-100 mt-2" style="color: black;">Create Mock Test</button>
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
    <!-- Calendar Js -->
    <script src="assets/js/calendar.js"></script>
    <!-- jvectormap Js -->
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>

    <!-- main js -->
    <script src="assets/js/main.js"></script>


</body>

</html>