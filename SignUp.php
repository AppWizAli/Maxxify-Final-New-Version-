<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maxxify Academy Login</title>
 
   <link href="dist/output.css" rel="stylesheet">
   
   <link href="dist/input.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/loader.css">
            <link rel="stylesheet" href="assets/css/mouse.css">

  
</head>
<body class="min-h-screen flex items-center justify-center bg-white relative">
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

  <!-- Back Arrow -->
  <button onclick="goBack()" class="fixed top-6 left-6 z-10 bg-[#673AB7] text-white p-3 rounded-full hover:bg-[#5E35B1] transition duration-300 shadow-lg">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
    </svg>
  </button>

  <div class="flex flex-col lg:flex-row w-full max-w-6xl shadow-lg rounded-lg overflow-hidden">

    <!-- Left Section (Illustration + Text) -->
    <div class="lg:w-1/2 w-full bg-[#673AB7] text-white p-10 flex flex-col justify-center items-center">
      <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-center">
        Your place to Study,<br />Examine your results
      </h2>
      <img src="assets/Images/Illustration.png" alt="Illustration"
           class="w-full max-w-xs mt-6" />
    </div>

    <!-- Right Section (Form) -->
   <!-- Right Side Login Form -->
<div class="lg:w-1/2 w-full bg-white px-6 py-10 flex flex-col justify-center items-center">

  <h2 class="text-[22px] leading-[100%] font-[700] font-[Manrope] text-[#2E1A51] text-center mb-6">
    Sign Up to Maxxify Academy
  </h2>

  <form method="POST" action="register.php" class="space-y-5 w-full max-w-[403px]">

  <div class="space-y-1">
      <label class="text-[#7D8592] font-[Manrope] font-bold text-[14px] leading-6"> Name</label>
      <input type="name" placeholder="Ali Bin Hussnain" name="name"
             class="w-full h-[48px] px-4 border border-[#D8E0F0] rounded-[14px] shadow-sm placeholder-[#7D8592] placeholder-font-manrope placeholder-text-[14px] focus:outline-none focus:ring-2 focus:ring-[#673AB7]" />
    </div>

      <div class="space-y-1">
      <label class="text-[#7D8592] font-[Manrope] font-bold text-[14px] leading-6">Phone Number</label>
      <input type="number" placeholder="Ali Bin Hussnain" name="phone"
             class="w-full h-[48px] px-4 border border-[#D8E0F0] rounded-[14px] shadow-sm placeholder-[#7D8592] placeholder-font-manrope placeholder-text-[14px] focus:outline-none focus:ring-2 focus:ring-[#673AB7]" />
    </div>
    <!-- Email -->
    <div class="space-y-1">
      <label class="text-[#7D8592] font-[Manrope] font-bold text-[14px] leading-6">Email Address</label>
      <input type="email" placeholder="youremail@gmail.com" name="email"
             class="w-full h-[48px] px-4 border border-[#D8E0F0] rounded-[14px] shadow-sm placeholder-[#7D8592] placeholder-font-manrope placeholder-text-[14px] focus:outline-none focus:ring-2 focus:ring-[#673AB7]" />
    </div>
  
    <!-- Password -->
    <div class="space-y-1 relative">
      <label class="text-[#7D8592] font-[Manrope] font-bold text-[14px] leading-6">Password</label>
      <input type="password" placeholder="••••••••" name="password"
             class="w-full h-[48px] px-4 border border-[#D8E0F0] rounded-[14px] shadow-sm placeholder-[#7D8592] placeholder-font-manrope placeholder-text-[14px] focus:outline-none focus:ring-2 focus:ring-[#673AB7]" />
      <!-- Eye Icon Image -->
      <img src="assets/Images/viewpassword.png" alt="Toggle Visibility"
           class="absolute w-6 h-6 right-3 top-[40px] cursor-pointer" />
    </div>
<div class="space-y-1 relative">
      <label class="text-[#7D8592] font-[Manrope] font-bold text-[14px] leading-6">Confirm New Password</label>
      <input type="password" placeholder="••••••••" name="confirm_password"
             class="w-full h-[48px] px-4 border border-[#D8E0F0] rounded-[14px] shadow-sm placeholder-[#7D8592] placeholder-font-manrope placeholder-text-[14px] focus:outline-none focus:ring-2 focus:ring-[#673AB7]" />
      <!-- Eye Icon Image -->
      <img src="assets/Images/viewpassword.png" alt="Toggle Visibility"
           class="absolute w-6 h-6 right-3 top-[40px] cursor-pointer" />
    </div>
    <!-- Remember me + Forgot -->
  

    <!-- Sign In Button -->
<div class="pt-3 flex flex-col items-center space-y-2">
  <button type="submit"
    class="w-[170px] h-[48px] bg-[#673AB7] text-white font-[Manrope] text-[16px] font-bold 
           rounded-[14px] shadow-[0px_6px_12px_0px_#673AB7] flex items-center justify-center gap-2 
           hover:bg-[#5e35b1] transition">
    <span>Sign Up</span>
    <img src="assets/Images/white.png" alt="Arrow Icon" class="w-6 h-6" />
  </button>

 <div class="pt-3 flex justify-end">
  <a href="login.php" class="text-[#673AB7] font-[Manrope] text-sm hover:underline">
    Already have an account? Log In
  </a>
</div>

</div>


  </form>
</div>


  </div>

  <!-- FontAwesome for eye icon -->
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <script src="assets/js/loader.js"></script>
    <script src="assets/js/mouse.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const passwordFields = document.querySelectorAll('input[type="password"]');
      const eyeIcons = document.querySelectorAll('img[alt="Toggle Visibility"]');
      eyeIcons.forEach(function(icon, idx) {
        icon.addEventListener('click', function() {
          const input = passwordFields[idx];
          if (input.type === 'password') {
            input.type = 'text';
          } else {
            input.type = 'password';
          }
        });
      });
    });

    function goBack() {
      if (document.referrer) {
        window.history.back();
      } else {
        window.location.href = 'index.php';
      }
    }
  </script>
</body>
</html>
