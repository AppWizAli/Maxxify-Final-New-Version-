<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM accounts LIMIT 1");
$account = $stmt->fetch();

$package_name = $_GET['package_name'] ?? $_POST['package_name'] ?? '';
$total_price = $_GET['price'] ?? $_POST['total_price'] ?? '';
$coupon_id = $_GET['coupon_id'] ?? $_POST['coupon_id'] ?? null;
$package_id = $_GET['package_id'] ?? $_POST['package_id'] ?? '';


try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
        $user_id = $_SESSION['user_id'] ?? null;
        $package_name = $_POST['package_name'] ?? '';
        $payment_method = $_POST['payment_method'] ?? '';
        $total_price = $_POST['total_price'] ?? '';
        $status = 'pending';
        $proof_file_name = '';

        if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/payment_proofs/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $ext = pathinfo($_FILES['payment_proof']['name'], PATHINFO_EXTENSION);
            $proof_file_name = uniqid('proof_', true) . '.' . $ext;
            move_uploaded_file($_FILES['payment_proof']['tmp_name'], $upload_dir . $proof_file_name);
        }

        if ($user_id && $package_name && $payment_method && $total_price && $proof_file_name) {
            $start_date = date('Y-m-d');
            $package_id = $_POST['package_id'] ?? '';
            
            if ($package_id == 1) {
                $duration = '1 month';
                $end_date = date('Y-m-d', strtotime('+1 month'));
            } elseif ($package_id == 2) {
                $duration = '4 months';
                $end_date = date('Y-m-d', strtotime('+4 months'));
            } elseif ($package_id == 3) {
                $duration = '1 year';
                $end_date = date('Y-m-d', strtotime('+1 year'));
            } else {
                // Fallback to name-based logic if ID is missing
                if (strtolower($package_name) === 'beginner') {
                    $duration = '1 month';
                    $end_date = date('Y-m-d', strtotime('+1 month'));
                } elseif (strtolower($package_name) === 'moderate') {
                    $duration = '4 months';
                    $end_date = date('Y-m-d', strtotime('+4 months'));
                } elseif (strtolower($package_name) === 'professional') {
                    $duration = '1 year';
                    $end_date = date('Y-m-d', strtotime('+1 year'));
                } else {
                    $duration = '';
                    $end_date = '';
                }
            }
            try {
                $stmt = $pdo->prepare("INSERT INTO subscriptions (user_id, package_name, payment_method, total_price, status, payment_proof, start_date, end_date, duration, coupon_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $package_name, $payment_method, $total_price, $status, $proof_file_name, $start_date, $end_date, $duration, $coupon_id ? $coupon_id : null]);
                $_SESSION['subscription_success'] = true;
                echo json_encode(['success' => true, 'redirect' => 'index.php']);
                exit;
            } catch (PDOException $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
                exit;
            }
        } else {
            $error = "Missing required information or payment proof.";
        }
    }
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
}
if (isset($error)) echo "<div style='color:red;text-align:center;'>$error</div>";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Payment & Checkout</title>

    <link href="dist/output.css" rel="stylesheet">

    <link href="dist/input.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">
    <style>
    body {
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
    <main class="space-y-8 px-4 sm:px-6 py-6">
        <h1 class="text-[24px] sm:text-[28px] md:text-[36px] lg:text-[36px] xl:text-[36px] 
           font-bold 
           leading-[32px] sm:leading-[36px] md:leading-[42px] lg:leading-[42px] 
           text-[#673AB7] 
           mt-10 sm:mt-12 md:mt-16 lg:mt-0 xl:mt-0 
           mb-4 sm:mb-6 md:mb-8">
            Payment & Checkout
        </h1>


        <div class="grid lg:grid-cols-2 gap-10">

            <!-- LEFT: Payment Options -->
            <div>
                <!-- Heading -->
                <h2 class="text-[20px] font-semibold leading-[22px] text-[#673AB7] mb-4 font-[Manrope]">
                    Select Payment Method
                </h2>

                <!-- Payment Options -->
                <div class="space-y-4 max-w-[600px] mx-auto" id="paymentOptions">
                    <!-- Easypaisa -->
                    <label for="easypaisa"
                        class="payment-card w-full block rounded-[10px] bg-white cursor-pointer transition-all duration-300 border-2 border-gray-300">
                        <input type="radio" name="payment" id="easypaisa" value="Easypaisa" style="display:none;">
                        <div class="flex justify-between items-center h-[60px] px-4">
                            <div class="flex items-center gap-3">
                                <div class="custom-radio w-6 h-6 rounded-full border-2 border-gray-300 relative"></div>
                                <span
                                    class="text-[#673AB7] font-[Manrope] text-[18px] font-semibold leading-[22px]">Easypaisa</span>
                            </div>
                            <img src="assets/Images/image 20.png" alt="easypaisa"
                                class="w-[70px] h-[24px] object-contain">
                        </div>
                    </label>

                    <!-- JazzCash -->
                    <label for="jazzcash"
                        class="payment-card w-full block rounded-[10px] bg-white cursor-pointer transition-all duration-300 border-2 border-gray-300">
                        <input type="radio" name="payment" id="jazzcash" value="JazzCash" style="display:none;">
                        <div class="flex justify-between items-center h-[60px] px-4">
                            <div class="flex items-center gap-3">
                                <div class="custom-radio w-6 h-6 rounded-full border-2 border-gray-300 relative"></div>
                                <span
                                    class="text-[#673AB7] font-[Manrope] text-[18px] font-semibold leading-[22px]">JazzCash</span>
                            </div>
                            <img src="assets/Images/image 21.png" alt="jazzcash"
                                class="w-[70px] h-[24px] object-contain">
                        </div>
                    </label>

                    <!-- Bank Transfer -->
                    <label for="bank"
                        class="payment-card w-full block rounded-[10px] bg-white cursor-pointer transition-all duration-300 border-2 border-gray-300">
                        <input type="radio" name="payment" id="bank" value="Bank Transfer" style="display:none;">
                        <div class="flex justify-between items-center h-[60px] px-4">
                            <div class="flex items-center gap-3">
                                <div class="custom-radio w-6 h-6 rounded-full border-2 border-gray-300 relative"></div>
                                <span
                                    class="text-[#673AB7] font-[Manrope] text-[18px] font-semibold leading-[22px]">Bank
                                    Transfer</span>
                            </div>
                            <img src="assets/Images/image 22.png" alt="bank" class="w-[70px] h-[24px] object-contain">
                        </div>
                    </label>
                </div>

                <!-- Cancel Button -->
             <button onclick="cancelPayment()"
  class="mt-6 w-[150px] h-[38px] bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white rounded-[30px] px-[48px] py-[8px] 
  text-[14px] font-semibold font-[Manrope] shadow-md 
  transition-all duration-300 ease-in-out transform
  hover:shadow-[0_8px_24px_rgba(103,58,183,0.4)] hover:-translate-y-[2px]
  active:translate-y-[1px] active:shadow-inner focus:outline-none">
  Cancel
</button>

                <script>
                function cancelPayment() {
                    const paymentSection = document.getElementById("paymentDetails");
                    if (paymentSection) {
                        paymentSection.classList.add("hidden");
                    }
                }
                </script>

                <!-- Note -->
                <p class="mt-6 text-[#4B4B4B] max-w-lg font-[Manrope] font-medium text-[16px] leading-[28px]">
                    No Refunds are allowed once the payment is made for the selected bundle. However, you can change the
                    bundle within 24 hours of purchase. For more details, please visit our Refund Policy Page.<br><br>
                    Maxxify does not offer any refunds once the payment has been made.<br><br>
                    Still have Questions? Read
                    <a href="#" class="text-[#673AB7] underline font-medium leading-[24px]">Frequently Asked Questions
                        (FAQs)</a>
                </p>
            </div>


            <!-- RIGHT: Payment Details -->
            <form method="POST" action="payment.php" enctype="multipart/form-data" id="orderForm">
                <input type="hidden" name="package_name" value="<?=htmlspecialchars($package_name)?>">
                <input type="hidden" name="total_price" value="<?=htmlspecialchars($total_price)?>">
                <input type="hidden" name="package_id" value="<?=htmlspecialchars($package_id)?>">
                <input type="hidden" name="payment_method" id="payment_method_input" value="">
                <input type="hidden" name="place_order" value="1">
                <input type="hidden" name="coupon_id" value="<?= isset($_GET['coupon_id']) ? htmlspecialchars($_GET['coupon_id']) : '' ?>">
                <div id="paymentDetails"
                    class="hidden bg-white rounded-[10px] shadow-[0px_10px_40px_0px_#673AB78F] p-4 w-full max-w-md space-y-5 mx-auto">

                    <div class="space-y-2 text-[#673AB7] font-[Manrope] text-[16px] leading-[24px]">
                        <p class="font-normal">
                            Transfer the amount to any one of these accounts and upload the screenshot of the receipt
                        </p>

                        <!-- Bank Transfer Details -->
                        <div id="bank-details" class="payment-details">
                            <p>
                                <strong class="font-bold">Name:</strong> <span class="font-normal">Bank Transfer</span><br>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Bank Name:</strong> <span class="font-normal"><?= htmlspecialchars($account['bank_name'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Account Holder Name:</strong> <span class="font-normal"><?= htmlspecialchars($account['bank_account_holder_name'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Account Number:</strong> <span
                                            class="font-normal"><?= htmlspecialchars($account['bank_account_number'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">IBAN:</strong> <span
                                            class="font-normal"><?= htmlspecialchars($account['bank_account_iban'] ?? '') ?></span></span>
                                    <img src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer" onclick="copyText(this)">
                                </span>
                            </p>
                        </div>

                        <!-- JazzCash Details -->
                        <div id="jazzcash-details" class="payment-details" style="display: none;">
                            <p>
                                <strong class="font-bold">Name:</strong> <span class="font-normal">JazzCash</span><br>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Title:</strong> <span class="font-normal"><?= htmlspecialchars($account['jazzCash_account_holder_name'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Account:</strong> <span
                                            class="font-normal"><?= htmlspecialchars($account['jazzCash_account_number'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                            </p>
                        </div>

                        <!-- EasyPaisa Details -->
                        <div id="easypaisa-details" class="payment-details" style="display: none;">
                            <p>
                                <strong class="font-bold">Name:</strong> <span class="font-normal">EasyPaisa</span><br>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Title:</strong> <span class="font-normal"><?= htmlspecialchars($account['easyPasisa_account_holder_name'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                                <span class="flex items-center justify-between">
                                    <span><strong class="font-bold">Account:</strong> <span
                                            class="font-normal"><?= htmlspecialchars($account['easyPasisa_account_number'] ?? '') ?></span></span>
                                    <img onclick="copyText(this)" src="assets/Images/solar_copy-bold.png" alt="icon"
                                        class="w-[20px] h-[20px] cursor-pointer">
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Upload Section -->
                    <div id="uploadSection"
                        class="w-full h-[150px] border-2 border-dashed border-[#bdbdbd] rounded-lg mx-auto flex flex-col justify-center items-center text-center cursor-pointer space-y-2">
                        <img src="assets/Images/fluent_reciept-20-filled1.png" alt="Upload Icon"
                            class="w-[35px] h-[35px]" id="uploadIcon">

                        <label for="receiptUpload"
                            class="text-[#673AB7] font-[Manrope] font-medium text-[16px] leading-[28px]" id="uploadLabel">
                            Drop your receipt here
                        </label>

                        <!-- Hidden file input -->
                        <input type="file" id="receiptUpload" name="payment_proof" class="hidden" accept="image/*">

                        <!-- Upload button -->
                       <button onclick="document.getElementById('receiptUpload').click(); return false;"
  class="w-[122px] h-[44px] bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white rounded-[10px] px-4 
  flex items-center justify-center gap-2 font-medium text-[15px] leading-[37px] font-[Manrope] 
  shadow-md transition-all duration-300 ease-in-out transform
  hover:shadow-[0_8px_24px_rgba(103,58,183,0.4)] hover:-translate-y-[2px]
  active:translate-y-[1px] active:shadow-inner focus:outline-none"
  id="uploadButton">
  Upload
  <img src="assets/Images/solar_upload-bold.png" alt="upload" class="w-[17px] h-[17px]">
</button>

                    </div>

                    <!-- Image Preview Section -->
                    <div id="imagePreviewSection" class="hidden w-full">
                        <div class="relative">
                            <img id="imagePreview" src="" alt="Payment Proof Preview" class="w-full h-[200px] object-cover rounded-lg border">
                            <button onclick="removeImage()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600">
                                ×
                            </button>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 text-center">Selected image preview</p>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-center gap-3 mt-3">
                            <button type="button" onclick="changeImage()" class="px-4 py-2 bg-[#673AB7] hover:bg-[#5a2fa0] text-white rounded-lg text-sm font-medium">
                                Change Image
                            </button>
                            <button type="button" onclick="removeImage()" class="px-4 py-2 bg-[#673AB7] hover:bg-[#5a2fa0] text-white rounded-lg text-sm font-medium">
                                Remove
                            </button>
                        </div>
                    </div>


                    <!-- Total Section -->
                    <div class="pt-3 border-t text-[16px] font-[Manrope] space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-700">Total</span>
                            <span class="text-gray-700" id="subtotal">Rs. <?= htmlspecialchars($total_price) ?></span>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                  <div class="w-full flex justify-center">
  <button type="submit" name="place_order"
    class="w-[300px] h-[40px] px-5 py-2 bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white rounded-[30px] 
    text-[14px] leading-[20px] font-semibold font-[Manrope] 
    shadow-md transition-all duration-300 ease-in-out transform
    hover:shadow-[0_8px_24px_rgba(103,58,183,0.4)] hover:-translate-y-[2px]
    active:translate-y-[1px] active:shadow-inner focus:outline-none
    flex items-center justify-center gap-[8px]">
    Place Order
  </button>
</div>

                </div>
            </form>
            <div id="ajax-message" style="text-align:center;margin-top:10px;"></div>

        </div>
    </main>
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
 


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/payment.js"></script>
    
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
 <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>
   <script>
  function copyText(imgElement) {
    const textToCopy = imgElement.parentElement.querySelector('.font-normal').textContent;

    navigator.clipboard.writeText(textToCopy)
      .then(() => {
        showCopyAlert("Text is copied!");
      })
      .catch(err => {
        console.error('Failed to copy:', err);
      });
  }

  function showCopyAlert(message) {
    const alertBox = document.createElement('div');
    alertBox.textContent = message;
    alertBox.style.position = 'fixed';
    alertBox.style.top = '20px';
    alertBox.style.right = '20px';
    alertBox.style.backgroundColor = '#4CAF50';
    alertBox.style.color = '#fff';
    alertBox.style.padding = '10px 15px';
    alertBox.style.borderRadius = '6px';
    alertBox.style.boxShadow = '0 2px 6px rgba(0,0,0,0.2)';
    alertBox.style.zIndex = '9999';
    alertBox.style.fontFamily = 'sans-serif';

    document.body.appendChild(alertBox);

    setTimeout(() => {
      alertBox.remove();
    }, 2000); // Disappears after 2 seconds
  }
</script>


    <script>
    function showImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const imagePreviewSection = document.getElementById('imagePreviewSection');
            const uploadSection = document.getElementById('uploadSection');
            
            imagePreview.src = e.target.result;
            imagePreviewSection.classList.remove('hidden');
            uploadSection.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }

    function removeImage() {
        const imagePreviewSection = document.getElementById('imagePreviewSection');
        const uploadSection = document.getElementById('uploadSection');
        const receiptInput = document.getElementById('receiptUpload');
        
        imagePreviewSection.classList.add('hidden');
        uploadSection.classList.remove('hidden');
        receiptInput.value = '';
        
        showToast('Image removed');
    }

    function changeImage() {
        const receiptInput = document.getElementById('receiptUpload');
        receiptInput.click();
    }

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

    function attachOrderFormHandler() {
        var form = document.getElementById('orderForm');
        if (form && !form.hasAttribute('data-handler-attached')) {
            form.setAttribute('data-handler-attached', 'true');
            form.addEventListener('submit', function(e) {
                var fileInput = form.querySelector('input[name="payment_proof"]');
                if (!fileInput || !fileInput.files || !fileInput.files.length) {
                    showToast('Payment proof is required');
                    e.preventDefault();
                    return;
                }
                e.preventDefault();
                var formData = new FormData(form);
                var xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        var msgDiv = document.getElementById('ajax-message');
                        msgDiv.innerHTML = '';
                        if (xhr.status === 200) {
                            try {
                                var resp = JSON.parse(xhr.responseText);
                                if (resp.success && resp.redirect) {
                                    var receiptInput = document.getElementById('receiptUpload');
                                    if (receiptInput) receiptInput.value = '';
                                    window.location.href = resp.redirect;
                                    return;
                                }
                                if (resp.error) {
                                    msgDiv.innerHTML = '<span style=\'color:red\'>' + resp.error +
                                    '</span>';
                                }
                            } catch (err) {
                                msgDiv.innerHTML = '<span style=\'color:red\'>AJAX error: ' + err +
                                    '</span>';
                            }
                        } else {
                            msgDiv.innerHTML = '<span style=\'color:red\'>HTTP error: ' + xhr.status +
                                '</span>';
                        }
                    }
                };
                xhr.send(formData);
            });
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        attachOrderFormHandler();
        document.querySelectorAll('input[name="payment"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('payment_method_input').value = this.value;
                
                // Hide all payment details
                document.querySelectorAll('.payment-details').forEach(detail => {
                    detail.style.display = 'none';
                });
                
                // Show appropriate payment details based on selection
                if (this.value === 'Easypaisa') {
                    document.getElementById('easypaisa-details').style.display = 'block';
                } else if (this.value === 'JazzCash') {
                    document.getElementById('jazzcash-details').style.display = 'block';
                } else if (this.value === 'Bank Transfer') {
                    document.getElementById('bank-details').style.display = 'block';
                }
            });
        });
        document.querySelectorAll('.payment-card').forEach(card => {
            card.addEventListener('click', function() {
                var radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', {
                        bubbles: true
                    }));
                }
            });
        });
        var receiptInput = document.getElementById('receiptUpload');
        if (receiptInput) {
            receiptInput.addEventListener('change', function() {
                if (this.files && this.files.length) {
                    showToast('File selected!');
                    showImagePreview(this.files[0]);
                }
            });
        }
    });
    </script>

</body>

</html>