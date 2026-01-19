<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
<aside class="sidebar">
    <!-- sidebar close btn -->
    <button type="button"
        class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i
            class="ph ph-x"></i></button>
    <!-- sidebar close btn -->

    <a href="index.html"
        class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="assets/images/logo/logo (2).png" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">
                <li class="sidebar-menu__item">
                    <a href="dashboard.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-squares-four"></i></span>
                        <span class="text">Dashboard</span>
                    </a>
                </li>

                <!-- Admin Management -->
                <li class="sidebar-menu__item">
                    <a href="manage-admins.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Manage Admins</span>
                    </a>
                </li>

                <!-- Users -->
                <li class="sidebar-menu__item">
                    <a href="students.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users-three"></i></span>
                        <span class="text">Students</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="manage_high_achievers.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users-three"></i></span>
                        <span class="text">Manage high Achievers</span>
                    </a>
                </li>
                <!-- Questions -->
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-list-magnifying-glass"></i></span>
                        <span class="text">MCQ Management</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item"><a href="mcq_types.php" class="sidebar-submenu__link">MCQ
                                Types</a></li>
                        <li class="sidebar-submenu__item"><a href="mcq_categories.php" class="sidebar-submenu__link">MCQ
                                Categories</a></li>
                        <li class="sidebar-submenu__item"><a href="subjects.php"
                                class="sidebar-submenu__link">Subjects</a></li>
                        <li class="sidebar-submenu__item"><a href="topics.php"
                                class="sidebar-submenu__link">Topics</a></li>
                        <li class="sidebar-submenu__item"><a href="mcqs.php" class="sidebar-submenu__link">Add MCQs</a></li>
                        <li class="sidebar-submenu__item"><a href="view_mcqs.php" class="sidebar-submenu__link">View MCQs</a></li>
                        <li class="sidebar-submenu__item"><a href="manage_topic_settings.php" class="sidebar-submenu__link">Topic Settings</a></li>
                    </ul>
                </li>

                <li class="sidebar-menu__item">
                    <a href="manage-teachers.blade.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Manage Teachers</span>
                    </a>
                </li>

                <li class="sidebar-menu__item">
                    <a href="manage-coupons.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-shield-check"></i></span>
                        <span class="text">Manage Coupons</span>
                    </a>
                </li>

                <li class="sidebar-menu__item">
                    <a href="subscription-requests.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Subscription Requests</span>
                    </a>
                </li>

                <li class="sidebar-menu__item">
                    <a href="shortlisting.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Shortlisting</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="packages.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Packages</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="accounts.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Accounts</span>
                    </a>
                </li>
                <li class="sidebar-menu__item">
                    <a href="reviews.php" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-users"></i></span>
                        <span class="text">Reviews</span>
                    </a>
                </li>
            </ul>

        </div>
    </div>

</aside>
<?php endif; ?>
<?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'mcq_management'): ?>
<aside class="sidebar">
    <!-- sidebar close btn -->
    <button type="button"
        class="sidebar-close-btn text-gray-500 hover-text-white hover-bg-main-600 text-md w-24 h-24 border border-gray-100 hover-border-main-600 d-xl-none d-flex flex-center rounded-circle position-absolute"><i
            class="ph ph-x"></i></button>
    <!-- sidebar close btn -->

    <a href="index.html"
        class="sidebar__logo text-center p-20 position-sticky inset-block-start-0 bg-white w-100 z-1 pb-10">
        <img src="assets/images/logo/logo (2).png" alt="Logo">
    </a>

    <div class="sidebar-menu-wrapper overflow-y-auto scroll-sm">
        <div class="p-20 pt-10">
            <ul class="sidebar-menu">

                <!-- Questions -->
                <li class="sidebar-menu__item has-dropdown">
                    <a href="javascript:void(0)" class="sidebar-menu__link">
                        <span class="icon"><i class="ph ph-list-magnifying-glass"></i></span>
                        <span class="text">MCQ Management</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li class="sidebar-submenu__item"><a href="mcq_types.php" class="sidebar-submenu__link">MCQ
                                Types</a></li>
                        <li class="sidebar-submenu__item"><a href="mcq_categories.php" class="sidebar-submenu__link">MCQ
                                Categories</a></li>
                        <li class="sidebar-submenu__item"><a href="subjects.php"
                                class="sidebar-submenu__link">Subjects</a></li>
                        <li class="sidebar-submenu__item"><a href="topics.php"
                                class="sidebar-submenu__link">Topics</a></li>
                        <li class="sidebar-submenu__item"><a href="mcqs.php" class="sidebar-submenu__link">Add MCQs</a></li>
                        <li class="sidebar-submenu__item"><a href="view_mcqs.php" class="sidebar-submenu__link">View MCQs</a></li>
                        <li class="sidebar-submenu__item"><a href="manage_topic_settings.php" class="sidebar-submenu__link">Topic Settings</a></li>
                    </ul>
                </li>

            </ul>

        </div>
    </div>

</aside>
<?php endif; ?>