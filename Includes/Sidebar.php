<aside class="w-full max-w-[283px] bg-[#E4E4E4] p-4 space-y-4 rounded-2xl h-screen">
  <div class="flex justify-center">
    <img src="assets/Images/logo 34.png" alt="Logo" class="w-[100px]  " />
  </div>

  <nav class="flex flex-col space-y-1" id="sidebarNav">

    <!-- Home -->
    <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="home">
      <img src="assets/Images/material-symbols_home-rounded1.png" class="icon h-[19px] w-[19px]">
      <a href="index.php" class="font-bold px-3 py-2">Home</a>
    </div>

    <!-- MDCAT -->
    <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="mdcat">
      <img src="assets/Images/courseicon.png" class="icon h-[19px] w-[19px]">
      <a href="mdcat.php" class="font-bold px-3 py-2">MDCAT</a>
    </div>

    <!-- NUMS -->
    <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="nums">
      <img src="assets/Images/courseicon.png" class="icon h-[19px] w-[19px]">
      <a href="nums.php" class="font-bold px-3 py-2">NUMS</a>
    </div>

    <!-- F.Sc -->
    <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="fsc">
      <img src="assets/Images/courseicon.png" class="icon h-[19px] w-[19px]">
      <a href="fsc.php" class="font-bold px-3 py-2">F.Sc</a>
    </div>

    <!-- Products -->
   <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="dashboard">
        <img src="assets/Images/dashboard4x.png" class="icon h-[19px] w-[19px]">
        <a href="dashboard.php" class="font-bold px-3 py-2">Dashboard</a>
      </div>

    <!-- About -->
    <?php if (isset($isHomePage) && $isHomePage): ?>
      <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="about">
        <img src="assets/Images/about4x.png" class="icon h-[19px] w-[19px]">
        <a href="#" class="font-bold px-3 py-2">About Us</a>
      </div>
        <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="pricing">
        <img src="assets/Images/Vector (1).png" class="icon h-[19px] w-[19px]">
        <a href="pricing.php" class="font-bold px-3 py-2">Pricing</a>
      </div>
      
    <?php else: ?>
      <div class="menu-item cursor-pointer flex items-center justify-between px-3 h-[46px] rounded-[11px]"
           data-dropdown-target="aboutDropdown" data-arrow-target="aboutArrowIcon" data-id="about">
        <div class="flex items-center">
          <img src="assets/Images/about4x.png" class="icon h-[19px] w-[19px]">
          <span class="font-bold px-3 py-2">About Us</span>
        </div>
        <img id="aboutArrowIcon" src="assets/Images/mingcute_down-fill (2).png"
             class="w-4 h-4 transition-transform duration-300 arrow-icon" />
      </div>

      <div id="aboutDropdown"
           class="ml-6 overflow-hidden max-h-0 transition-all duration-500 ease-in-out flex flex-col space-y-1 dropdown-content">
       
      </div>

      <!-- Dashboard -->
   

      <!-- Pricing -->
      <div class="menu-item flex items-center px-3 h-[46px] rounded-[11px]" data-id="pricing">
        <img src="assets/Images/Vector (1).png" class="icon h-[19px] w-[19px]">
        <a href="pricing.php" class="font-bold px-3 py-2">Pricing</a>
      </div>
    <?php endif; ?>
  </nav>
<div class="flex justify-center">
  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="logout.php" class="bg-[#673AB7] w-[178px] font-bold py-2 px-4 rounded-3xl text-white text-center block hover:bg-[#673AB7] transition duration-300">
      Logout
    </a>
  <?php else: ?>
    <a href="signup.php" class="bg-[#673AB7] w-[178px] font-bold py-2 px-4 rounded-3xl text-white text-center block hover:bg-[#5E35B1] transition duration-300">
      SignUp/Login
    </a>
  <?php endif; ?>
</div>

</aside>
