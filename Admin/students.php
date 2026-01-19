<?php require_once 'auth_check.php'; ?>
<?php
include('config.php'); // Use your actual config file path

// Fetch all users
$stmt = $pdo->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
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
        <div class="top-navbar flex-between gap-16">

            <div class="flex-align gap-16">
                <!-- Toggle Button Start -->
                <button type="button" class="toggle-btn d-xl-none d-flex text-26 text-gray-500"><i class="ph ph-list"></i></button>
                <!-- Toggle Button End -->

                <form action="#" class="w-350 d-sm-block d-none">
                    <div class="position-relative">
                        <button type="submit" class="input-icon text-xl d-flex text-gray-100 pointer-event-none"><i class="ph ph-magnifying-glass"></i></button>
                        <input type="text" class="form-control ps-40 h-40 border-transparent focus-border-main-600 bg-main-50 rounded-pill placeholder-15" placeholder="Search...">
                    </div>
                </form>
            </div>

            <div class="flex-align gap-16">
                <div class="flex-align gap-8">
                    <!-- Notification Start -->
                    <div class="dropdown">
                        <button class="dropdown-btn shaking-animation text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="position-relative">
                                <i class="ph ph-bell"></i>
                                <span class="alarm-notify position-absolute end-0"></span>
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                            <div class="card border border-gray-100 rounded-12 box-shadow-custom p-0 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="py-8 px-24 bg-main-600">
                                        <div class="flex-between">
                                            <h5 class="text-xl fw-semibold text-white mb-0">Notifications</h5>
                                            <div class="flex-align gap-12">
                                                <button type="button" class="bg-white rounded-6 text-sm px-8 py-2 hover-text-primary-600"> New </button>
                                                <button type="button" class="close-dropdown hover-scale-1 text-xl text-white"><i class="ph ph-x"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-24 max-h-270 overflow-y-auto scroll-sm">
                                        <div class="d-flex align-items-start gap-12">
                                            <img src="assets/images/thumbs/notification-img1.png" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
                                            <div class="border-bottom border-gray-100 mb-24 pb-24">
                                                <div class="flex-align gap-4">
                                                    <a href="#" class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Ashwin Bose is requesting access to Design File - Final Project. </a>
                                                    <!-- Three Dot Dropdown Start -->
                                                    <div class="dropdown flex-shrink-0">
                                                        <button class="text-gray-200 rounded-4" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="ph-fill ph-dots-three-outline"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                                                            <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                                                <div class="card-body p-12">
                                                                    <div class="max-h-200 overflow-y-auto scroll-sm pe-8">
                                                                        <ul>
                                                                            <li class="mb-0">
                                                                                <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Mark as read</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="mb-0">
                                                                                <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Delete Notification</span>
                                                                                </a>
                                                                            </li>
                                                                            <li class="mb-0">
                                                                                <a href="#" class="py-6 text-15 px-8 hover-bg-gray-50 text-gray-300 rounded-8 fw-normal text-xs d-block">
                                                                                    <span class="text">Report</span>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Three Dot Dropdown End -->
                                                </div>
                                                <div class="flex-align gap-6 mt-8">
                                                    <img src="assets/images/icons/google-drive.png" alt="">
                                                    <div class="flex-align gap-4">
                                                        <p class="text-gray-900 text-sm text-line-1">Design brief and ideas.txt</p>
                                                        <span class="text-xs text-gray-200 flex-shrink-0">2.2 MB</span>
                                                    </div>
                                                </div>
                                                <div class="mt-16 flex-align gap-8">
                                                    <button type="button" class="btn btn-main py-8 text-15 fw-normal px-16">Accept</button>
                                                    <button type="button" class="btn btn-outline-gray py-8 text-15 fw-normal px-16">Decline</button>
                                                </div>
                                                <span class="text-gray-200 text-13 mt-8">2 mins ago</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-start gap-12">
                                            <img src="assets/images/thumbs/notification-img2.png" alt="" class="w-48 h-48 rounded-circle object-fit-cover">
                                            <div class="">
                                                <a href="#" class="fw-medium text-15 mb-0 text-gray-300 hover-text-main-600 text-line-2">Patrick added a comment on Design Assets - Smart Tags file:</a>
                                                <span class="text-gray-200 text-13">2 mins ago</span>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="#" class="py-13 px-24 fw-bold text-center d-block text-primary-600 border-top border-gray-100 hover-text-decoration-underline"> View All </a>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Notification Start -->

                    <!-- Language Start -->
                    <div class="dropdown">
                        <button class="text-gray-500 w-40 h-40 bg-main-50 hover-bg-main-100 transition-2 rounded-circle text-xl flex-center" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ph ph-globe"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu--md border-0 bg-transparent p-0">
                            <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                                <div class="card-body">
                                    <div class="max-h-270 overflow-y-auto scroll-sm pe-8">
                                        <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="arabic">
                                                <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag1.png" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Arabic</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language" id="arabic">
                                        </div>
                                        <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="germany">
                                                <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag2.png" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Germany</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language" id="germany">
                                        </div>
                                        <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0 mb-16">
                                            <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="english">
                                                <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag3.png" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">English</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language" id="english">
                                        </div>
                                        <div class="form-check form-radio d-flex align-items-center justify-content-between ps-0">
                                            <label class="ps-0 form-check-label line-height-1 fw-medium text-secondary-light" for="spanish">
                                                <span class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-8">
                                                    <img src="assets/images/thumbs/flag4.png" alt="" class="w-32-px h-32-px border borde border-gray-100 rounded-circle flex-shrink-0">
                                                    <span class="text-15 fw-semibold mb-0">Spanish</span>
                                                </span>
                                            </label>
                                            <input class="form-check-input" type="radio" name="language" id="spanish">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Language Start -->
                </div>


                <!-- User Profile Start -->
                <div class="dropdown">
                    <button class="users arrow-down-icon border border-gray-200 rounded-pill p-4 d-inline-block pe-40 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="position-relative">
                            <img src="assets/images/thumbs/user-img.png" alt="Image" class="h-32 w-32 rounded-circle">
                            <span class="activation-badge w-8 h-8 position-absolute inset-block-end-0 inset-inline-end-0"></span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu--lg border-0 bg-transparent p-0">
                        <div class="card border border-gray-100 rounded-12 box-shadow-custom">
                            <div class="card-body">
                                <div class="flex-align gap-8 mb-20 pb-20 border-bottom border-gray-100">
                                    <img src="assets/images/thumbs/user-img.png" alt="" class="w-54 h-54 rounded-circle">
                                    <div class="">
                                        <h4 class="mb-0">Michel John</h4>
                                        <p class="fw-medium text-13 text-gray-200">examplemail@mail.com</p>
                                    </div>
                                </div>
                                <ul class="max-h-270 overflow-y-auto scroll-sm pe-4">
                                    <li class="mb-4">
                                        <a href="setting.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-gear"></i></span>
                                            <span class="text">Account Settings</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="pricing-plan.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-bar"></i></span>
                                            <span class="text">Upgrade Plan</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="analytics.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chart-line-up"></i></span>
                                            <span class="text">Daily Activity</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="message.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-chats-teardrop"></i></span>
                                            <span class="text">Inbox</span>
                                        </a>
                                    </li>
                                    <li class="mb-4">
                                        <a href="email.html" class="py-12 text-15 px-20 hover-bg-gray-50 text-gray-300 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-primary-600 d-flex"><i class="ph ph-envelope-simple"></i></span>
                                            <span class="text">Email</span>
                                        </a>
                                    </li>
                                    <li class="pt-8 border-top border-gray-100">
                                        <a href="sign-in.html" class="py-12 text-15 px-20 hover-bg-danger-50 text-gray-300 hover-text-danger-600 rounded-8 flex-align gap-8 fw-medium text-15">
                                            <span class="text-2xl text-danger-600 d-flex"><i class="ph ph-sign-out"></i></span>
                                            <span class="text">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- User Profile Start -->

            </div>
        </div>


        <div class="dashboard-body">

            <div class="breadcrumb-with-buttons mb-24 flex-between flex-wrap gap-8">
                <!-- Breadcrumb Start -->
                <div class="breadcrumb mb-24">
                    <ul class="flex-align gap-4">
                        <li><a href="index.html" class="text-gray-200 fw-normal text-15 hover-text-main-600">Home</a></li>
                        <li> <span class="text-gray-500 fw-normal d-flex"><i class="ph ph-caret-right"></i></span> </li>
                        <li><span class="text-main-600 fw-normal text-15">Students</span></li>
                    </ul>
                </div>
                <!-- Breadcrumb End -->

                <!-- Breadcrumb Right Start -->
                <div class="flex-align gap-8 flex-wrap">
                    <div class="position-relative text-gray-500 flex-align gap-4 text-13">
                        <span class="text-inherit">Sort by: </span>
                        <div class="flex-align text-gray-500 text-13 border border-gray-100 rounded-4 ps-20 focus-border-main-600 bg-white">
                            <span class="text-lg"><i class="ph ph-funnel-simple"></i></span>
                            <select class="form-control ps-8 pe-20 py-16 border-0 text-inherit rounded-4 text-center">
                                <option value="1" selected>Popular</option>
                                <option value="1">Latest</option>
                                <option value="1">Trending</option>
                                <option value="1">Matches</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-align text-gray-500 text-13 border border-gray-100 rounded-4 ps-20 focus-border-main-600 bg-white">
                        <span class="text-lg"><i class="ph ph-layout"></i></span>
                        <select class="form-control ps-8 pe-20 py-16 border-0 text-inherit rounded-4 text-center" id="exportOptions">
                            <option value="" selected disabled>Export</option>
                            <option value="csv">CSV</option>
                            <option value="json">JSON</option>
                        </select>
                    </div>
                </div>
                <!-- Breadcrumb Right End -->
            </div>


            <div class="card overflow-hidden">
                <div class="card-body p-0 overflow-x-auto">
                    <table id="studentTable" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="fixed-width">
                                    <div class="form-check">
                                        <input class="form-check-input border-gray-200 rounded-4" type="checkbox" id="selectAll">
                                    </div>
                                </th>
                                <th class="h6 text-gray-300">Student Name</th>
                                <th class="h6 text-gray-300">Email ID</th>
                                <th class="h6 text-gray-300">Phone Number</th>
                                <th class="h6 text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td class="fixed-width">
                                        <div class="form-check">
                                            <input class="form-check-input border-gray-200 rounded-4" type="checkbox">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex-align gap-8">
                                            <span class="h6 mb-0 fw-medium text-gray-300"><?= htmlspecialchars($user['name']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="h6 mb-0 fw-medium text-gray-300"><?= htmlspecialchars($user['email']) ?></span>
                                    </td>
                                    <td>
                                        <span class="h6 mb-0 fw-medium text-gray-300"><?= htmlspecialchars($user['phone_number'] ?: 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <a href="view_user.php?id=<?= $user['id'] ?>" class="bg-main-50 text-main-600 py-2 px-14 rounded-pill hover-bg-main-600 hover-text-white">View More</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer flex-between flex-wrap">
                <span class="text-gray-900">Showing 1 to 10 of 12 entries</span>
                <ul class="pagination flex-align flex-wrap">
                    <li class="page-item active">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">...</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">8</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">9</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link h-44 w-44 flex-center text-15 rounded-8 fw-medium" href="#">10</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="dashboard-footer">
        <div class="flex-between flex-wrap gap-16">
            <p class="text-gray-300 text-13 fw-normal"> &copy; Copyright Edmate 2024, All Right Reserverd</p>
            <div class="flex-align flex-wrap gap-16">
                <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">License</a>
                <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">More Themes</a>
                <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Documentation</a>
                <a href="#" class="text-gray-300 text-13 fw-normal hover-text-main-600 hover-text-decoration-underline">Support</a>
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