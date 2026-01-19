<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Maxxify Checkout with Sidebar</title>

    <link href="dist/output.css" rel="stylesheet">

    <link href="dist/input.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/loader.css">
    <link rel="stylesheet" href="assets/css/mouse.css">
    <style>
        * {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-start justify-center gap-10 lg:p-6 xl:p-6 relative">

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
    <!-- ✅ Main Content (Fully Adjusted) -->
    <main class="space-y-8  py-6">
        <div class="w-full  mx-autorounded-2xl p-6 space-y-6">

            <!-- Cancel and Heading -->
            <div class="flex justify-between items-start">
                <a href="../pricing.php">
                    <button
                        class="flex items-center gap-[10px] w-[90px] h-[24px] text-red-600 text-[16px] font-[Manrope] font-normal leading-[100%]">
                        <img src="assets/Images/material-symbols_cancel-rounded.png" alt="Cancel"
                            class="w-[22px] h-[22px]" />
                        <span>Cancel</span>
                    </button>
                </a>

            </div>


            <div class="space-y-1 text-left">
                <h2 class="text-[#673AB7]"
                    style="font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 32px; line-height: 36px; letter-spacing: 0%;">
                    You're almost there!
                </h2>
                <p class="text-[#673AB7]"
                    style="font-family: 'Manrope', sans-serif; font-weight: 500; font-size: 18px; line-height: 24px; letter-spacing: 0%;">
                    Kindly review your order
                </p>
            </div>



            <!-- Bundle Title -->
            <div class="flex justify-between items-center border-b pb-2">
                <div>
                    <h3
                        style="font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 24px; line-height: 32px; color: #673AB7;">
                        All In One Basic (All In One)
                    </h3>
                    <p
                        style="font-family: 'Manrope', sans-serif; font-weight: 500; font-size: 16px; line-height: 24px; color: #673AB7;">
                        Valid Till 2025 Exams
                    </p>
                </div>
                <button class="text-[#673AB7] text-[20px]">🗑️</button>
            </div>


            <!-- Features -->
            <div
                class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-y-2 w-full max-w-[1476px] text-[#727272] font-[Manrope] font-medium text-[16px] leading-[20px]">
                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Chapter-wise </span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Past Papers</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Mock Tests</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Flashcards</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Full Length Tests</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Progress Tracking</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Video Lectures</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Live Sessions</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Leaderboard</span>
                </div>

                <div class="flex items-center gap-2 h-[28px]">
                    <span
                        class="flex items-center justify-center w-5 h-5 bg-[#00BB00] text-white rounded-[4px] text-[10px] ">
                        <i class="fas fa-check"></i>
                    </span>
                    <span>Certificates</span>
                </div>
            </div>

            <?php
            $package = $_GET['package_name'] ?? '';
            $price = $_GET['price'] ?? '';
            $package_id = $_GET['package_id'] ?? '';
            ?>

            <div class="space-y-2 w-full">
                <label for="coupon" class="text-[#673AB7] font-bold text-[24px] leading-[22px]"
                    style="font-family: 'Manrope', sans-serif;">
                    Coupon Code
                </label>

                <div class="flex gap-3 items-center w-full">
                    <input type="text" id="coupon" placeholder="Enter coupon"
                        class="flex-1 h-[50px] px-4 rounded-[8px] border border-gray-200 shadow-[0_5px_20px_rgba(0,0,0,0.1)] bg-[#E9E9E9] focus:outline-none text-sm"
                        style="font-family: 'Manrope', sans-serif;" />
                    <input type="hidden" id="originalPrice" value="<?= htmlspecialchars($price) ?>">

<button type="button" id="applyCouponBtn"
  class="h-[50px] px-5 py-2 bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white text-[16px] leading-[20px] font-semibold rounded-[8px] 
  shadow-md transition-all duration-300 ease-in-out transform
  hover:shadow-[0_8px_24px_rgba(103,58,183,0.4)] hover:-translate-y-[2px]
  active:translate-y-[1px] active:shadow-inner focus:outline-none"
  style="font-family: 'Manrope', sans-serif;">
  Apply
</button>

                </div>
            </div>


            <!-- Price Summary -->
            <div class="w-full max-w-lg mx-auto mt-4">
                <div class="flex justify-between items-center py-1">
                    <span>Subtotal</span>
                    <span id="priceValue" class="text-[#3F3F3F]">Rs. <?= htmlspecialchars($price) ?></span>
                </div>
                <div class="flex justify-between items-center py-1" id="discountRow" style="display:none;">
                    <span class="text-[#673AB7]">Discount (<span id="discountPercent"></span>)</span>
                    <span class="text-[#673AB7]" id="discountValue"></span>
                </div>
                <div class="flex justify-between items-center py-1 font-bold" id="finalRow" style="display:none;">
                    <span class="text-[#673AB7]">Final Total</span>
                    <span class="text-[#673AB7]" id="finalValue"></span>
                </div>
            </div>


            <!-- Proceed Button -->
          <div class="text-center">
  <a id="proceedCheckoutBtn"
    href="payment.php?package_name=<?= urlencode($package) ?>&price=<?= urlencode($price) ?>&package_id=<?= urlencode($package_id) ?>"
    class="w-full h-[50px] px-5 py-2 bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white text-[18px] leading-[22px] font-semibold rounded-[10px] 
    shadow-md transition-all duration-300 ease-in-out transform
    hover:shadow-[0_8px_24px_rgba(103,58,183,0.4)] hover:-translate-y-[2px]
    active:translate-y-[1px] active:shadow-inner focus:outline-none flex items-center justify-center"
    style="font-family: 'Manrope', sans-serif;">
    Proceed
  </a>
</div>


            <!-- Note Section -->
            <div class="text-left space-y-2 text-[14px] leading-[20px]"
                style="font-family: 'Manrope', sans-serif; font-weight: 500; letter-spacing: 0%;">
                <p class="text-[#727272]">
                    No Refunds are allowed once the payment is made for the selected bundle. However, you can change the
                    bundle within 24 hours of purchase. For more details, please visit our Refund Policy Page.
                </p>
                <p class="text-[#727272]">
                    Maxxify does not offer any refunds once the payment has been made.
                </p>
                <p class="text-[#727272]">
                    Still have Questions?
                    <a href="#" class="text-[#673AB7] underline">
                        Frequently Asked Questions (FAQs)
                    </a>
                </p>
            </div>


        </div>
    </main>
    <!-- Checkout Section End -->

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/payment.js"></script>
    <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebarOverlay");
            const isOpen = !sidebar.classList.contains("-translate-x-full");

            if (isOpen) {
                sidebar.classList.add("-translate-x-full");
                overlay?.classList.add("hidden");
            } else {
                sidebar.classList.remove("-translate-x-full");
                overlay?.classList.remove("hidden");
            }
        }
    </script>
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
            setTimeout(function () { toast.remove(); }, 2000);
        }

        document.getElementById('applyCouponBtn').onclick = function () {
            var code = document.getElementById('coupon').value.trim();
            var price = parseFloat(document.getElementById('originalPrice').value);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'coupon_check.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                var resp = JSON.parse(xhr.responseText);
                if (!resp.valid) {
                    showToast(resp.error);
                    document.getElementById('discountRow').style.display = 'none';
                    document.getElementById('finalRow').style.display = 'none';
                } else {
                    document.getElementById('discountPercent').textContent = resp.percentage + '% off';
                    document.getElementById('discountValue').textContent = '-Rs. ' + parseFloat(resp.discount).toFixed(2);
                    document.getElementById('finalValue').textContent = 'Rs. ' + parseFloat(resp.final).toFixed(2);
                    document.getElementById('discountRow').style.display = '';
                    document.getElementById('finalRow').style.display = '';
                    var proceedBtn = document.getElementById('proceedCheckoutBtn');
                    var href = proceedBtn.getAttribute('href');
                    href = href.replace(/([&?]price=)[^&]*/, '$1' + encodeURIComponent(resp.final));
                    if (href.indexOf('coupon_id=') > -1) {
                        href = href.replace(/([&?]coupon_id=)[^&]*/, '$1' + encodeURIComponent(resp.coupon_id));
                    } else {
                        href += '&coupon_id=' + encodeURIComponent(resp.coupon_id);
                    }
                    if (href.indexOf('package_id=') > -1) {
                        // Keep package_id if it exists
                    } else {
                        var package_id = "<?= urlencode($package_id) ?>";
                        if (package_id) {
                            href += '&package_id=' + encodeURIComponent(package_id);
                        }
                    }
                    proceedBtn.setAttribute('href', href);
                }
            };
            xhr.send('code=' + encodeURIComponent(code) + '&price=' + encodeURIComponent(price));
        };
    </script>
</body>

</html>