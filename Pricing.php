<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maxxify Pricing Plans</title>

    <link href="dist/output.css" rel="stylesheet">

    <link href="dist/input.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/mouse.css">
    <style>
        #cartPanel {
            transition-duration: 300ms;
        }
    </style>
    <style>
        .custom-shadow {
            box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.3);
        }
    </style>
    <style>
        .custom-btn {
            width: 100%;
            height: 70px;
            background-color: #f1f1f1;
            color: #2D3748;
            font-size: 16px;
            font-weight: bold;
            border-radius: 0.375rem;
            /* same as rounded-md */
            transition: all 0.3s ease;
        }

        .custom-btn:hover {
            background-color: #673AB7;
            color: white;
        }
    </style>
</head>

<?php
session_start();
require_once 'config.php';
$user_id = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 0;

$stmt = $pdo->query("SELECT * FROM packages ORDER BY id ASC");
$packages = $stmt->fetchAll();

$stmt = $pdo->query("SELECT pp.*, p.name as package_name FROM packagepoints pp 
                     LEFT JOIN packages p ON pp.package_id = p.id 
                     ORDER BY pp.package_id ASC, pp.id ASC");
$packagePoints = $stmt->fetchAll();

$pointsByPackage = [];
foreach ($packagePoints as $point) {
    $packageId = $point['package_id'];
    if (!isset($pointsByPackage[$packageId])) {
        $pointsByPackage[$packageId] = [];
    }
    $pointsByPackage[$packageId][] = $point['point'];
}

$package1Points = $pdo->query("SELECT * FROM packagepoints WHERE package_id = 1 ORDER BY id ASC")->fetchAll();
$package2Points = $pdo->query("SELECT * FROM packagepoints WHERE package_id = 2 ORDER BY id ASC")->fetchAll();
$package3Points = $pdo->query("SELECT * FROM packagepoints WHERE package_id = 3 ORDER BY id ASC")->fetchAll();
?>

<body class="bg-white min-h-screen flex items-start justify-center  lg:p-6 xl:p-6 relative">
    <div class="mouse-cursor cursor-outer"></div>
    <div class="mouse-cursor cursor-inner"></div>


    <div class="fullscreen-loader" id="preloader">
        <div class="dot-loader">
            <span></span>
            <span></span>
            <span></span>
            <span></span>

        </div>
    </div>
    <header
        class="lg:hidden fixed top-0 left-0 w-full bg-white shadow-md z-50 px-4 py-3 flex justify-between items-center">
        <button onclick="toggleSidebar()" class="w-6 h-6">
            <img src="assets/Images/quill_hamburger.png" alt="Menu" class="w-full h-full object-contain" />
        </button>
        <div class="absolute left-1/2 transform -translate-x-1/2">
            <img src="assets/Images/logo 34.png" alt="Logo" class="w-[46px] h-[46px] object-contain" />
        </div>
    </header>

    <div id="sidebarOverlay" class="fixed inset-0  bg-opacity-40 z-40 hidden lg:hidden"
        onclick="toggleSidebar()">
    </div>

    <aside id="sidebar"
        class="fixed lg:static top-0 left-0 z-50 bg-white w-[260px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none" style="width:260px">
        <?php include 'Includes/Sidebar.php'; ?>
    </aside>
    <main class="space-y-8 px-4 sm:px-6 py-6">
        <div class="mt-16 sm:mt-0 md:mt-0 lg:mt-0 xl:mt-0">
            <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-center text-[#673AB7] mb-3 md:mb-6">
                Maxxify Pricing Plans
            </h1>
            <h2 class="text-sm sm:text-base md:text-lg text-center text-gray-700 font-semibold mb-2 md:mb-4">
                Choose Plans
            </h2>

        </div>
        <div class="fixed top-4 right-4 z-50 text-center">
            <p class="text-sm font-semibold text-gray-600 mb-1">Sale Ends In:</p>
            <div class="flex-shrink-0 flex justify-center items-center w-[80px] h-[80px] rounded-full animate-pulse transition duration-300"
                style="background: linear-gradient(180deg, #E1E1E1 0%, #FFFFFF 100%);
           box-shadow: 
             0px 20px 40px rgba(51, 51, 51, 0.15),
             inset 0px 8px 8px rgba(255, 255, 255, 0.8);">
                <h3 id="timer" class="text-[#673AB7] font-bold leading-none text-sm md:text-base">
                    01:00
                </h3>
            </div>
        </div>

        <!-- Plan Buttons -->
        <!-- Button Group -->
        <div class="flex flex-wrap md:flex-nowrap justify-center items-center gap-3 mb-10 px-4">
            <button id="btn1" onclick="activateCard(1)"
                class="plan-btn font-[600] text-[#673AB7] text-[18px] md:text-[20px] leading-[20px] px-4 py-3 rounded-[10px] bg-white border border-[#673AB7] shadow-md hover:bg-[#f3f0fa] hover:shadow-lg transition-all duration-300">
                All In One
            </button>
            <button id="btn2" onclick="activateCard(2)"
                class="plan-btn font-[600] text-[#673AB7] text-[18px] md:text-[20px] leading-[20px] px-4 py-3 rounded-[10px] bg-white border border-[#673AB7] shadow-md hover:bg-[#f3f0fa] hover:shadow-lg transition-all duration-300">
                MDCAT
            </button>
            <button id="btn3" onclick="activateCard(3)"
                class="plan-btn font-[600] text-[#673AB7] text-[18px] md:text-[20px] leading-[20px] px-4 py-3 rounded-[10px] bg-white border border-[#673AB7] shadow-md hover:bg-[#f3f0fa] hover:shadow-lg transition-all duration-300">
                NUMS
            </button>
            <button id="btn4" onclick="activateCard(4)"
                class="plan-btn font-[600] text-[#673AB7] text-[18px] md:text-[20px] leading-[20px] px-4 py-3 rounded-[10px] bg-white border border-[#673AB7] shadow-md hover:bg-[#f3f0fa] hover:shadow-lg transition-all duration-300">
                MDCAT + NUMS
            </button>
        </div>




        <style>
            .border-gradient {
                border: 2px solid transparent;
                border-image: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%);
                border-image-slice: 1;
                box-sizing: border-box;
                font-family: 'Manrope', sans-serif;
            }

            .active-plan {
                background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%) !important;
                color: white !important;
            }
        </style>

        <script>
            function activateCard(id) {
                document.querySelectorAll('.plan-btn').forEach(btn => {
                    btn.classList.remove('active-plan');
                });

                const activeBtn = document.getElementById(`btn${id}`);
                activeBtn.classList.add('active-plan');
            }
        </script>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto py-10">
            <?php if (isset($packages[0])): ?>
                <div class="card">
                    <span class="badge">15% OFF</span>
                    <h3><?php echo htmlspecialchars($packages[0]['name']); ?></h3>
                    <div class="text-center mt-2">
                        <p class="card-price"><?php echo htmlspecialchars($packages[0]['price']); ?>/<span>Monthly</span></p>
                    </div>
                    <p class="text-sm font-semibold text-center mt-2 leading-tight">
                        Maxifay Academy Session<br />MDCAT + NUMS
                    </p>

                    <ul class="feature-list">
                        <?php
                        $totalPoints = count($package1Points);
                        foreach ($package1Points as $index => $point):
                            $isLastFour = ($index >= $totalPoints - 4);
                            if ($isLastFour): ?>
                                <li class="unavailable text-white">
                                    <span class="text-red-500 font-bold mr-1"></span> <?= htmlspecialchars($point['point']) ?>
                                </li>
                            <?php else: ?>
                                <li><?= htmlspecialchars($point['point']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>

                    <div class="buy-btn">
                        <button onclick="checkSubscriptionAndOpenCart('<?php echo htmlspecialchars($packages[0]['name']); ?>','<?php echo htmlspecialchars($packages[0]['price']); ?>', 1)">
                            BUY NOW <span>→</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($packages[1])): ?>
                <div class="card">
                    <span class="badge">25% OFF</span>
                    <h3><?php echo htmlspecialchars($packages[1]['name']); ?></h3>
                    <div class="text-center mt-2">
                        <p class="card-price"><?php echo htmlspecialchars($packages[1]['price']); ?>/<span>4 Monthly</span></p>
                    </div>
                    <p class="text-sm font-semibold text-center mt-2 leading-tight">
                        Maxifay Academy Session<br />MDCAT + NUMS + FSC
                    </p>

                    <ul class="feature-list">
                        <?php
                        $totalPoints2 = count($package2Points);
                        foreach ($package2Points as $index => $point):
                            $isLastOne = ($index >= $totalPoints2 - 1);
                            if ($isLastOne): ?>
                                <li class="unavailable text-white">
                                    <span class="text-red-500 font-bold mr-1"></span> <?= htmlspecialchars($point['point']) ?>
                                </li>
                            <?php else: ?>
                                <li><?= htmlspecialchars($point['point']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>

                    <div class="buy-btn">
                        <button onclick="checkSubscriptionAndOpenCart('<?php echo htmlspecialchars($packages[1]['name']); ?>','<?php echo htmlspecialchars($packages[1]['price']); ?>', 2)">
                            BUY NOW <span>→</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($packages[2])): ?>
                <div class="card">

                    <span class="badge">60% OFF</span>
                    <h3><?php echo htmlspecialchars($packages[2]['name']); ?></h3>
                    <div class="text-center mt-2">
                        <p class="card-price"><?php echo htmlspecialchars($packages[2]['price']); ?>/<span>Yearly</span></p>
                    </div>
                    <p class="text-sm font-semibold text-center mt-2 leading-tight">
                        Maxifay Academy Session<br />MDCAT + NUMS + FSC
                    </p>

                    <ul class="feature-list">
                        <?php
                        foreach ($package3Points as $point): ?>
                            <li><?= htmlspecialchars($point['point']) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <div class="buy-btn">
                        <button onclick="checkSubscriptionAndOpenCart('<?php echo htmlspecialchars($packages[2]['name']); ?>','<?php echo htmlspecialchars($packages[2]['price']); ?>', 3)">
                            BUY NOW <span>→</span>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
            <style>
                .card {
                    background: linear-gradient(to bottom, #0d47a1, #ba68c8);
                    border-radius: 1rem;
                    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
                    color: white;
                    min-height: 650px;
                    display: flex;
                    flex-direction: column;
                    padding: 1.3rem;
                    position: relative;
                }

                .card h3 {
                    font-size: 1.25rem;
                    font-weight: 700;
                    text-transform: uppercase;
                    margin-bottom: 0.5rem;
                }

                .badge {
                    position: absolute;
                    top: 0.5rem;
                    right: 0.3rem;
                    background-color: rgb(217, 217, 20);
                    color: white;
                    font-weight: 800;
                    font-size: 1.2rem;
                    padding: 0.25rem 0.75rem;
                    border-radius: 9999px;
                    font-weight: bold;
                }

                .card-price {
                    font-size: 2.25rem;
                    font-weight: 800;
                }

                .card-price span {
                    font-size: 1rem;
                    font-weight: 600;
                }

                .feature-list {
                    margin-top: 1.5rem;
                    font-size: 0.875rem;
                    font-weight: 500;
                    line-height: 1.6;
                }

                .feature-list li {
                    margin-bottom: 0.5rem;
                }

                .feature-list .unavailable {
                    color: #fca5a5;
                }

                .buy-btn {
                    margin-top: auto;
                    padding-top: 1.5rem;
                }

                .buy-btn button {

                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5rem;
                    padding: 0.5rem 1.5rem;
                    background-color: #ffeb3b;
                    color: #0d47a1;
                    font-weight: 700;
                    border-radius: 9999px;
                    transition: background-color 0.3s;
                    width: 100%
                }

                .buy-btn button:hover {
                    background-color: #fdd835;
                }

                .buy-btn span {
                    background-color: #0d47a1;
                    color: white;
                    border-radius: 9999px;
                    width: 1.5rem;
                    height: 1.5rem;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 0.75rem;
                }
            </style>

    </main>

    <!-- Sidebar -->
    <div id="sidebar1"
        class="fixed top-0 right-0 h-screen lg:w-[510px] 
         transform translate-x-full transition-transform duration-500 z-50 p-0 overflow-hidden flex items-start justify-center">

        <div class="scale-[0.8] origin-top-right h-[738px] w-full min-h-screen">
            <div
                class="bg-white rounded-lg shadow-xl w-full h-full flex flex-col items-center justify-between p-4 gap-5">

                <div class="w-full flex items-center justify-between border-b border-gray-300 pb-4">
                    <div class="flex items-center space-x-2">

                        <div class="flex items-center space-x-2">
                            <img src="assets/Images/material-symbols_cancel-rounded.png" alt="Close"
                                onclick="closeSidebar()" class="w-[30px] h-[30px] cursor-pointer" />

                            <span class="text-[22px] font-[400] text-[#000000] font-[Manrope]">Cart</span>
                        </div>
                    </div>
                </div>

      <div class="flex flex-col items-center w-full space-y-6 py-6 flex-grow overflow-y-auto px-1">


                    <button
                        class="w-[320px] h-[52px] rounded-[104px] border-4 border-[#BC97FF] bg-[#673AB7] text-white text-[20px] leading-[22px] font-bold font-[Manrope] hover:bg-[#5e2ea0] transition duration-300">
                        Cart
                    </button>

                    <div
                        class="w-[426px] h-[289px] rounded-[10px] border-2 border-purple-200 bg-purple-50 p-4 font-[Manrope]">
                        <h3 id="cartPackageName" class="font-semibold text-[18px] text-[#000000]">Pre-Medical Bundle
                        </h3>
                        <p id="cartPackageSKU" class="font-medium text-[18px] text-[#000000] mt-1">SKU: All In One Basic
                            (All In One)</p>
                        <p id="cartPackageDesc" class="font-medium text-[18px] text-[#000000] mt-1">Our most affordable
                            all in one plan to get you started.</p>
                   <div class="mt-4 space-y-3 text-[18px] text-[#000000] font-medium">

  <!-- Courses -->
  <div class="flex items-start justify-between gap-4">
    <span class="min-w-[90px] shrink-0">Courses:</span>
    <span class="text-right break-words flex-1">
      Physics, Chemistry, Biology
    </span>
  </div>

  <!-- Duration -->
  <div class="flex items-start justify-between gap-4">
    <span class="min-w-[90px] shrink-0">Duration:</span>
    <span class="text-right break-words flex-1">
      Till 2025 Exams
    </span>
  </div>

  <!-- Price -->
  <div class="flex items-center justify-between gap-4">
    <span class="min-w-[90px] shrink-0">Price:</span>
    <span class="text-right font-semibold" id="cartNewPrice"></span>
  </div>

  <!-- Dynamic Feature List -->
  <div class="pt-2">
    <ul class="feature-list space-y-2">
      <?php foreach ($package3Points as $point): ?>
        <li class="flex items-start gap-2 break-words">
          <span class="text-[#274d90] text-xl leading-none">•</span>
          <span><?= htmlspecialchars($point['point']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

</div>
      <!-- Total Price -->
                    <div class="w-full font-[Manrope] px-4">
                        <h2 class="text-[28px] font-bold leading-[30px] text-[#000000]">Total</h2>
                        <div class="flex items-baseline mt-1 space-x-2">
                            <span class="text-[28px] font-bold text-purple-700" id="cartTotalPrice"></span>
                        </div>
                    </div>
                    </div>

              
                </div>

                <!-- Bottom Checkout Button -->
                <div class="w-full flex justify-center pb-4">
                    <a id="proceedCheckoutBtn" href="#" onclick="return checkProceedHref();"
                        class=" text-center w-[426px] h-[46px] rounded-[30px] px-[28px] py-[12px] bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white font-[Manrope] font-semibold text-[14px] leading-[22px] hover:opacity-90 transition duration-300">Proceed
                        To Checkout</a>
                    </a>

                </div>

            </div>


            <!-- JavaScript -->
            <script>
                function openCartModal(packageName, price, packageId) {
                    document.getElementById('sidebar1').classList.remove('translate-x-full');
                    document.getElementById('proceedCheckoutBtn').href = 'checkout.php?package_name=' + encodeURIComponent(packageName) + '&price=' + encodeURIComponent(price) + '&package_id=' + encodeURIComponent(packageId);

                    document.getElementById('cartPackageName').textContent = packageName;
                    document.getElementById('cartNewPrice').textContent = 'PKR. ' + price;
                    document.getElementById('cartTotalPrice').textContent = 'Rs. ' + price;

                    document.getElementById('cartPackageSKU').textContent = 'SKU: ' + packageName;
                    document.getElementById('cartPackageDesc').textContent = packageName + ' package with comprehensive features.';

                    let duration = '';
                    if (packageId == 1) {
                        duration = '1 month';
                    } else if (packageId == 2) {
                        duration = '4 months';
                    } else if (packageId == 3) {
                        duration = '1 year';
                    }

                    const durationElement = document.querySelector('.grid.grid-cols-2.gap-2 div:nth-child(4)');
                    if (durationElement) {
                        durationElement.textContent = duration;
                    }

                    const coursesElement = document.querySelector('.grid.grid-cols-2.gap-2 div:nth-child(2)');
                    if (coursesElement && PACKAGE_POINTS[packageId]) {
                        coursesElement.textContent = PACKAGE_POINTS[packageId].join(', ');
                    }
                }

                function closeSidebar() {
                    document.getElementById('sidebar1').classList.add('translate-x-full');
                }

                function checkProceedHref() {
                    var href = document.getElementById('proceedCheckoutBtn').getAttribute('href');
                    if (!href || href === '#') {
                        alert('Please select a package first.');
                        return false;
                    }
                    return true;
                }
            </script>



        </div>

        <div id="cartPanel"
            class="fixed top-0 right-0 w-[320px] h-full bg-white shadow-2xl z-50 p-4 transition-transform transform translate-x-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold">Cart</h2>
                <button onclick="toggleCart(false)" class="text-red-600 font-bold text-lg">&times;</button>
            </div>
            <div class="bg-gray-100 p-3 rounded-xl shadow-inner mb-4">
                <h3 class="font-bold">Pre-Medical Bundle</h3>
                <p class="text-sm text-gray-600">SKU: All in One Basic (All in One)</p>
                <p class="text-sm text-gray-600">Our most affordable all in one plan to get you started.</p>
                <div class="mt-3">
                    <p class="text-sm font-semibold">Courses:</p>
                    <p class="text-sm">Duration: Till 2025 Exams</p>
                    <p class="text-sm">Price: <span class="line-through text-gray-400">Rs. 15500</span> <span
                            class="text-[#673AB7] font-bold">Rs. 13175</span></p>
                </div>
            </div>
            <div class="text-lg font-bold mb-2 text-[#673AB7]">Total</div>
            <div class="text-2xl text-[#673AB7] font-bold mb-4">Rs. 13175 <span
                    class="text-sm text-gray-400 line-through">Rs. 15500</span></div>
            <a href="checkout.html"
                class="block text-center w-full bg-gradient-to-r from-[#673AB7] to-[#512da8] text-white py-2 rounded-full font-bold">Proceed
                To Checkout</a>
        </div>

        <script>
            function showToast(message) {
                var toast = document.createElement('div');
                toast.textContent = message;
                toast.style.position = 'fixed';
                toast.style.top = '30px';
                toast.style.right = '30px';
                toast.style.zIndex = 9999;
                toast.style.background = '#673AB7';
                toast.style.color = '#fff';
                toast.style.padding = '12px 24px';
                toast.style.borderRadius = '8px';
                toast.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
                toast.style.fontSize = '16px';
                toast.style.width = '240px';
                toast.style.textAlign = 'center';
                document.body.appendChild(toast);
                setTimeout(function() {
                    toast.remove();
                }, 2000);
            }
            const USER_ID = <?php echo json_encode($user_id); ?>;
            const PACKAGE_POINTS = <?php echo json_encode($pointsByPackage); ?>;

            function checkSubscriptionAndOpenCart(packageName, price, packageId) {
                if (!USER_ID) {
                    showToast('Please login to purchase a package.');
                    return;
                }
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'subscription_check.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            var resp = JSON.parse(xhr.responseText);
                            if (resp.status === 'approved') {
                                showToast('You already have a subscription activated.');
                            } else if (resp.status === 'pending') {
                                showToast('You already have a subscription pending request.');
                            } else {
                                openCartModal(packageName, price, packageId);
                            }
                        } catch (e) {
                            showToast('Error checking subscription.');
                        }
                    } else {
                        showToast('Error checking subscription.');
                    }
                };
                xhr.send('user_id=' + encodeURIComponent(USER_ID));
            }
        </script>
        
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
        <script src="assets/js/loader.js"></script>
        <script src="assets/js/mouse.js"></script>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="assets/js/payment.js"></script>
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
                                const otherArrow = document.getElementById(otherTrigger.dataset
                                    .arrowTarget);
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