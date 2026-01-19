<?php
session_start();
include 'config.php';

// Check if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Adjust path if needed
    exit();
}

// Fetch total MCQs count
$total_mcqs_count = 0;
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM mcqs");
    $total_mcqs_count = $stmt->fetchColumn();
} catch (Exception $e) {
    $total_mcqs_count = 0;
}

$total_attempted = 0;
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM answers WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $total_attempted = $stmt->fetchColumn();
} catch (Exception $e) {
    $total_attempted = 0;
}

$mcq_types = [];
try {
    $stmt = $pdo->query("SELECT id, name FROM mcq_types");
    $mcq_types = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $mcq_types = [];
}

$monthly_data = [];
try {
    $stmt = $pdo->prepare("SELECT DATE_FORMAT(created_at, '%Y-%m') as ym, COUNT(*) as cnt FROM answers WHERE user_id = ? GROUP BY ym ORDER BY ym");
    $stmt->execute([$_SESSION['user_id']]);
    $monthly_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $monthly_data = [];
}

$type_answer_counts = [];
$total_type_answers = 0;
foreach ($mcq_types as $type) {
    $count = 0;
    try {
        $stmt = $pdo->prepare("
            SELECT COUNT(*) FROM answers a 
            JOIN mcqs m ON a.mcq_id = m.id 
            JOIN topics t ON m.topic_id = t.id 
            JOIN subjects s ON t.subject_id = s.id 
            JOIN mcq_categories c ON s.category_id = c.id 
            WHERE a.user_id = ? AND c.mcq_type_id = ?
        ");
        $stmt->execute([$_SESSION['user_id'], $type['id']]);
        $count = $stmt->fetchColumn();
    } catch (Exception $e) {
        $count = 0;
    }
    $type_answer_counts[] = [
        'id' => $type['id'],
        'name' => $type['name'],
        'count' => $count
    ];
    $total_type_answers += $count;
}
$max_type_count = 0;
foreach ($type_answer_counts as $tac) if ($tac['count'] > $max_type_count) $max_type_count = $tac['count'];
$colors = ['#6366F1','#80BFFF','#9CF6AD','#D3DFFF','#F59E42','#F87171','#34D399','#FBBF24','#A78BFA','#F472B6','#60A5FA','#FCD34D'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Overview</title>

    <link href="dist/output.css" rel="stylesheet">

    <link href="dist/input.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">
    <style>
    /* Bar animation (grows on load) */
    @keyframes growBar {
        0% {
            height: 0;
        }

        100% {
            height: var(--final-height);
        }
    }

    .bar-animate {
        animation: growBar 1s ease-out forwards;
    }

    /* Donut chart animation */
    .donut-ring {
        transition: stroke-dashoffset 1s ease-out;
        stroke-dashoffset: 1000;
        animation: donutFill 1s ease-out forwards;
    }

    @keyframes donutFill {
        to {
            stroke-dashoffset: 0;
        }
    }
    </style>
    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById("mobileSidebar");
        sidebar.classList.toggle("-translate-x-full");
    }
    </script>
</head>

<body class="bg-white min-h-screen flex justify-center relative lg:p-6 xl:p-6">
<div class="mouse-cursor cursor-outer"></div>
<div class="mouse-cursor cursor-inner"></div>


  <!-- 🔵 Preloader -->
  <div class="fullscreen-loader" id="preloader">
    <div class="dot-loader">
      <span></span>
      <span></span>
      <span></span>
      <span></span>
  
    </div>
  </div>
    <!-- Wrapper for sidebar and main content -->
    <div class="flex w-full max-w-[1440px]">

        <!-- 📱 Mobile Header (hidden on lg and up) -->
        <header
            class="lg:hidden fixed top-0 left-0 w-full bg-white shadow-md z-50 px-4 py-3 flex justify-between items-center">
            <button onclick="toggleSidebar()" class="w-6 h-6">
                <img src="assets/Images/quill_hamburger.png" alt="Menu" class="w-full h-full object-contain" />
            </button>
            <div class="absolute left-1/2 transform -translate-x-1/2">
                <img src="assets/Images/logo 34.png" alt="Logo" class="w-[46px] h-[46px] object-contain" />
            </div>
        </header>

        <!-- 📱 Mobile Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden"
            onclick="toggleSidebar()">
        </div>

        <!-- 🧭 Sidebar -->
        <aside id="sidebar"
            class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
            <?php include 'Includes/Sidebar.php'; ?>
        </aside>

        <!-- 📝 Main Content -->
        <main class="flex-1 px-4 py-6 lg:pl-6 space-y-8 max-w-full overflow-x-hidden">
            <!-- Header -->
          <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center max-w-full px-4 sm:px-6 lg:px-10 mt-6 sm:mt-10 lg:mt-16 gap-4">

  <!-- Left Content -->
  <div class="text-left space-y-3 max-w-2xl">
    <h1 class="font-extrabold text-[24px] sm:text-[32px] lg:text-[36px] leading-snug sm:leading-tight font-[Manrope] bg-gradient-to-r from-[#673AB7] to-[#2E1A51] text-transparent bg-clip-text">
      Good Morning!
    </h1>
    <p class="font-medium text-[15px] sm:text-[18px] lg:text-[20px] leading-relaxed text-[#727272] font-[Manrope]">
      Here is your dashboard overview. What will you learn today?
    </p>
    <a href="pricing.php"
       class="font-medium text-[14px] sm:text-[15px] text-[#673AB7] font-[Manrope] inline-block hover:underline">
      View Plan Details »
    </a>
  </div>

  <!-- Right Content (Logout Button) -->
<div class="w-full sm:w-auto flex lg:block justify-start sm:justify-end">
  <a href="logout.php"
     class="text-white text-sm font-medium px-5 py-2 rounded-[30px] text-center
     transition-all duration-300 ease-in-out transform relative z-10
     hover:-translate-y-[2px] hover:shadow-lg 
     active:translate-y-[1px] active:shadow-inner focus:outline-none"
     style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%)">
    Logout
  </a>
</div>


</div>



            <!-- Overview Title -->
            <div>
                <h2
                    class="text-[22px] leading-[22px] font-[800] font-[Manrope] bg-gradient-to-r from-[#673AB7] to-[#2E1A51] text-transparent bg-clip-text">
                    Overview
                </h2>
            </div>


            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-x-4 gap-y-6">

                <!-- Card -->
                <div
                    class="bg-[#EDEEFC] p-3 rounded-[16px] shadow-md min-w-[140px] w-[140px] h-[80px] flex flex-col justify-center space-y-1.5">
                    <h3 class="text-[13px] leading-[18px] font-medium text-black font-[Manrope]">Total MCQs</h3>
                    <p class="text-[15px] font-semibold text-black flex items-center gap-1">
                        <?= $total_mcqs_count ?>
                    </p>
                </div>

                <!-- Repeat for others -->
                <div
                    class="bg-[#EDEEFC] p-3 rounded-[16px] shadow-md min-w-[140px] w-[140px] h-[80px] flex flex-col justify-center space-y-1.5">
                    <h3 class="text-[13px] leading-[18px] font-medium text-black font-[Manrope]">Total Attempted</h3>
                    <p class="text-[15px] font-semibold text-black flex items-center gap-1">
                        <?= $total_attempted ?>
                    </p>
                </div>

                <?php foreach ($mcq_types as $type): ?>
                <div
                    class="bg-[#EDEEFC] p-3 rounded-[16px] shadow-md min-w-[140px] w-[140px] h-[80px] flex flex-col justify-center space-y-1.5">
                    <h3 class="text-[13px] leading-[18px] font-medium text-black font-[Manrope]">
                        <?= htmlspecialchars($type['name']) ?></h3>
                    <p class="text-[15px] font-semibold text-black">
                        <?php
                            $count = 0;
                            try {
                                $stmt = $pdo->prepare("
                                    SELECT COUNT(*) FROM answers a 
                                    JOIN mcqs m ON a.mcq_id = m.id 
                                    JOIN topics t ON m.topic_id = t.id 
                                    JOIN subjects s ON t.subject_id = s.id 
                                    JOIN mcq_categories c ON s.category_id = c.id 
                                    WHERE a.user_id = ? AND c.mcq_type_id = ?
                                ");
                                $stmt->execute([$_SESSION['user_id'], $type['id']]);
                                $count = $stmt->fetchColumn();
                            } catch (Exception $e) {
                                $count = 0;
                            }
                            echo $count;
                            ?>
                    </p>
                </div>
                <?php endforeach; ?>

            </div>



            <style>
            @keyframes growBar {
                0% {
                    height: 0;
                }

                100% {
                    height: var(--final-height);
                }
            }

            .bar-animate {
                animation: growBar 1s ease-out forwards;
            }

            .donut-ring {
                transition: stroke-dashoffset 1s ease-out;
                stroke-dashoffset: 1000;
                animation: donutFill 1s ease-out forwards;
            }

            @keyframes donutFill {
                to {
                    stroke-dashoffset: 0;
                }
            }
            </style>

            <!-- Tighter Wrapper -->
            <div class="flex justify-center items-center flex-wrap gap-2 px-1 py-6 bg-white">

                <!-- Bar Chart Card -->
                <div class="bg-[#F9F9FA] rounded-[16px] shadow p-3 w-[320px] h-[220px]">
                    <h3 class="text-[14px] font-semibold text-gray-700 mb-3 font-[Manrope]">Your Progress</h3>
                    <div class="flex justify-around items-end h-[120px]">
                        <?php
                        $max = 0;
                        foreach ($monthly_data as $m) if ($m['cnt'] > $max) $max = $m['cnt'];
                        foreach ($monthly_data as $m):
                            $height = $max ? round(100 * $m['cnt'] / $max) : 0;
                            $month = date('M', strtotime($m['ym'].'-01'));
                        ?>
                        <div class="flex flex-col items-center space-y-1">
                            <div class="bg-[#9CA3FA] w-4 rounded-full bar-animate"
                                style="--final-height: <?= $height ?>px; height: 0;"></div>
                            <span class="text-[10px] text-gray-500"><?= $month ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Donut Chart Card -->
                <div
                    class="bg-[#F9F9FA] rounded-[16px] shadow p-3 w-[320px] h-[220px] flex items-center justify-center gap-2">
                    <?php
                $segments = [];
                $start = 0;
                foreach ($type_answer_counts as $i => $tac) {
                    $percent = $total_type_answers ? ($tac['count'] * 100 / $total_type_answers) : 0;
                    $end = $start + $percent;
                    $color = $colors[$i % count($colors)];
                    $segments[] = "$color $start% $end%";
                    $start = $end;
                }
                $gradient = implode(', ', $segments);
                ?>
                    <div style="position:relative;width:90px;height:90px;display:inline-block;">
                        <div
                            style="width:90px;height:90px;border-radius:50%;background:conic-gradient(<?= $gradient ?>);">
                        </div>
                        <div
                            style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:50px;height:50px;background:#fff;border-radius:50%;">
                        </div>
                    </div>
                    <div class="space-y-2 w-[140px]" style="display:inline-block;vertical-align:top;margin-left:16px;">
                        <?php foreach ($type_answer_counts as $i => $tac):
                        $percent = $total_type_answers ? round($tac['count']*100/$total_type_answers,1) : 0;
                        $color = $colors[$i % count($colors)];
                    ?>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center gap-2"><span class="w-2 h-2 rounded-full"
                                    style="background:<?= $color ?>"></span>
                                <?= htmlspecialchars($tac['name']) ?></span>
                            <span class="font-semibold"><?= $percent ?>%</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>


            <style>
            /* Line draw animation */
            .chart-line {
                stroke-dasharray: 300;
                stroke-dashoffset: 300;
                animation: drawLine 2s ease-out forwards;
            }

            @keyframes drawLine {
                to {
                    stroke-dashoffset: 0;
                }
            }
            </style>

            <div class="rounded-[16px] bg-[#f9f9fa] p-4 w-full max-w-full">
                <div class="mb-4 flex flex-wrap items-center gap-3">
                    <h3 class="text-base font-semibold text-gray-700">Total Progress</h3>
                    <div class="flex flex-wrap gap-3 text-xs text-gray-500">
                        <span class="font-semibold text-gray-700">Total MCQs</span>
                        <span>Total Practice Tests</span>
                        <span>This year</span>
                        <span>Last year</span>
                    </div>
                </div>

                <div class="w-full h-[280px] md:h-[320px] bg-[#f5f5f6] rounded-[12px] p-4 relative overflow-hidden">
                    <?php
                    $year = date('Y');
                    $stmt = $pdo->prepare("SELECT MONTH(created_at) as m, COUNT(*) as cnt FROM answers WHERE user_id = ? AND YEAR(created_at) = ? GROUP BY m ORDER BY m");
                    $stmt->execute([$_SESSION['user_id'], $year]);
                    $monthly = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $month_counts = array_fill(1, 12, 0);
                    foreach ($monthly as $row) $month_counts[(int)$row['m']] = (int)$row['cnt'];
                    $max = max($month_counts);
                    if ($max < 1) $max = 1;
                    $points = [];
                    $labels = [];
                    $n = 0;
                    foreach ($month_counts as $i => $cnt) {
                        $x = $n * (100/11);
                        $y = 90 - ($cnt/$max)*80;
                        $points[] = round($x,1).','.round($y,1);
                        $labels[] = date('M', mktime(0,0,0,$i,1));
                        $n++;
                    }
                    ?>
                    <div class="absolute top-4 bottom-10 left-2 flex flex-col justify-between text-[11px] text-gray-600 font-semibold">
                        <span><?= $max ?></span>
                        <span><?= round($max/2) ?></span>
                        <span>0</span>
                    </div>
                    <div class="absolute left-10 right-4 top-4 bottom-10">
                        <svg viewBox="0 0 100 100" preserveAspectRatio="none" class="w-full h-full">
                            <polyline fill="none" stroke="#6366F1" stroke-width="2" class="chart-line" points="<?= implode(' ', $points) ?>" />
                        </svg>
                    </div>
                    <div class="absolute bottom-3 left-10 right-4 flex justify-between text-[11px] text-gray-500 font-medium px-2">
                        <?php foreach ($labels as $label): ?><span><?= $label ?></span><?php endforeach; ?>
                    </div>
                </div>
            </div>

<!-- Scroll to Top Button -->
<div id="scrollToTopBtn" title="Go to top" class="w-12 h-12">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
    <path d="M12 19V6"></path>
    <polyline points="5 12 12 5 19 12"></polyline>
  </svg>
</div>

<script>
  const scrollBtn = document.getElementById("scrollToTopBtn");

  window.addEventListener("scroll", () => {
    // Show button after scrolling 100px
    if (window.scrollY > 100) {
      scrollBtn.classList.add("show");
    } else {
      scrollBtn.classList.remove("show");
    }
  });

  scrollBtn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
</script>

        </main>
    </div>
 <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const dropdownContents = document.querySelectorAll('.dropdown-content');
        const arrowIcons = document.querySelectorAll('.arrow-icon');
        const sidebarNav = document.getElementById('sidebarNav');
        const menuItems = sidebarNav.querySelectorAll('.menu-item');
        const LAST_ACTIVE_MENU_ITEM = 'lastActiveMenuItem';

        // 🔁 Sidebar Toggle (Mobile)
        window.toggleSidebar = function() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            sidebar.classList.toggle('-translate-x-full', !isHidden);
            overlay.classList.toggle('hidden', isHidden);
        };

        // 🔄 Reset All Menu Items
        function resetAllMenuItems() {
            menuItems.forEach(mi => {
                mi.classList.remove('bg-[#673AB7]', 'active-menu');
                mi.querySelector('a')?.classList.remove('text-white');
                mi.querySelector('span')?.classList.remove('text-white');
                const icon = mi.querySelector('.icon');
                if (icon) icon.style.filter = '';
            });

            dropdownContents.forEach(dropdown => {
                dropdown.classList.add('max-h-0');
                dropdown.style.maxHeight = '0px';
            });
            arrowIcons.forEach(arrow => {
                arrow.classList.remove('rotate-180');
                arrow.style.filter = '';
            });
        }

        // ✅ Set Active Item
        function setActiveMenuItem(itemToActivate) {
            resetAllMenuItems();

            if (itemToActivate) {
                itemToActivate.classList.add('bg-[#673AB7]', 'active-menu');
                itemToActivate.querySelector('a')?.classList.add('text-white');
                itemToActivate.querySelector('span')?.classList.add('text-white');
                const icon = itemToActivate.querySelector('.icon');
                if (icon) icon.style.filter = 'invert(100%)';

                let parentDropdownTrigger = null;
                dropdownContents.forEach(dropdown => {
                    if (dropdown.contains(itemToActivate)) {
                        parentDropdownTrigger = document.querySelector(
                            `[data-dropdown-target="${dropdown.id}"]`);
                    }
                });

                if (parentDropdownTrigger) {
                    parentDropdownTrigger.classList.add('bg-[#673AB7]', 'active-menu');
                    parentDropdownTrigger.querySelector('.icon').style.filter = 'invert(100%)';
                    parentDropdownTrigger.querySelector('span').classList.add('text-white');

                    const targetDropdown = document.getElementById(parentDropdownTrigger.dataset
                        .dropdownTarget);
                    targetDropdown.classList.remove('max-h-0');
                    targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';

                    const targetArrow = document.getElementById(parentDropdownTrigger.dataset.arrowTarget);
                    targetArrow.classList.add('rotate-180');
                    targetArrow.style.filter = 'invert(100%)';
                }

                localStorage.setItem(LAST_ACTIVE_MENU_ITEM, itemToActivate.dataset.id);
            } else {
                localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
            }
        }

        // ⬇️ Toggle Dropdown
        function toggleAnyDropdown(triggerElement) {
            const targetDropdown = document.getElementById(triggerElement.dataset.dropdownTarget);
            const targetArrow = document.getElementById(triggerElement.dataset.arrowTarget);
            const isCollapsed = targetDropdown.classList.contains('max-h-0');

            dropdownContents.forEach(dropdown => {
                if (dropdown.id !== triggerElement.dataset.dropdownTarget) {
                    dropdown.classList.add('max-h-0');
                    dropdown.style.maxHeight = '0px';
                    const otherTrigger = document.querySelector(
                        `[data-dropdown-target="${dropdown.id}"]`);
                    if (otherTrigger) {
                        const otherArrow = document.getElementById(otherTrigger.dataset.arrowTarget);
                        if (otherArrow) {
                            otherArrow.classList.remove('rotate-180');
                            otherArrow.style.filter = '';
                        }
                    }
                }
            });

            if (isCollapsed) {
                setActiveMenuItem(triggerElement);
                targetDropdown.classList.remove('max-h-0');
                targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                targetArrow.classList.add('rotate-180');
                targetArrow.style.filter = 'invert(100%)';
            } else {
                targetDropdown.style.maxHeight = targetDropdown.scrollHeight + 'px';
                void targetDropdown.offsetWidth;
                targetDropdown.classList.add('max-h-0');
                targetDropdown.style.maxHeight = '0px';

                triggerElement.classList.remove('bg-[#673AB7]', 'active-menu');
                triggerElement.querySelector('.icon').style.filter = '';
                triggerElement.querySelector('span').classList.remove('text-white');
                targetArrow.classList.remove('rotate-180');
                targetArrow.style.filter = '';
            }
        }

        // 📦 Sidebar Click Handler
        sidebarNav.addEventListener('click', function(event) {
            const clickedItem = event.target.closest('.menu-item');
            if (clickedItem) {
                if (clickedItem.hasAttribute('data-dropdown-target')) {
                    toggleAnyDropdown(clickedItem);
                } else {
                    setActiveMenuItem(clickedItem);

                    // Auto-close sidebar on mobile after selecting menu
                    if (window.innerWidth < 1024) toggleSidebar();
                }
            }
        });

        // ⚙️ Initial Active Item (URL or Storage)
        function initializeActiveState() {
            const currentPath = window.location.pathname.split('/').pop();
            let itemToActivate = null;

            menuItems.forEach(mi => {
                const link = mi.querySelector('a');
                if (link && link.href) {
                    const linkPath = link.href.split('/').pop();
                    const isHomePage = (linkPath === 'Sidebar.html' && (currentPath === '' ||
                        currentPath === 'Sidebar.html' || currentPath === 'index.html'));
                    if (linkPath === currentPath || isHomePage) {
                        itemToActivate = mi;
                    }
                }
            });

            if (itemToActivate) {
                setActiveMenuItem(itemToActivate);
            } else {
                const lastActiveId = localStorage.getItem(LAST_ACTIVE_MENU_ITEM);
                if (lastActiveId) {
                    itemToActivate = document.querySelector(`[data-id="${lastActiveId}"]`);
                    if (itemToActivate) {
                        setActiveMenuItem(itemToActivate);
                    } else {
                        localStorage.removeItem(LAST_ACTIVE_MENU_ITEM);
                        setActiveMenuItem(document.querySelector('[data-id="home"]'));
                    }
                } else {
                    setActiveMenuItem(document.querySelector('[data-id="home"]'));
                }
            }
        }

        initializeActiveState();
    });
    </script>
</body>

</html>