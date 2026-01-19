<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include 'config.php';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maxxify Academy</title>

  <link href="dist/output.css" rel="stylesheet">

  <link href="dist/input.css" rel="stylesheet">


  <!-- Slick Carousel CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
  <link rel="stylesheet" type="text/css"
    href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css" />

  <link rel="stylesheet" href="assets/css/loader.css">
  <link rel="stylesheet" href="assets/css/mouse.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <!-- Latest Font Awesome via CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <style>
    html,
    body {
      overflow-x: hidden;
    }

    .card-active {
      color: white !important;
      background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%) !important;
      box-shadow: 15px 25px 250px 0px #00000040 !important;
    }

    .card-active h2,
    .card-active p,
    .card-active h3 {
      color: white !important;
    }
  </style>

</head>

<body class="bg-white min-h-screen  justify-center relative ">
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

  <!-- MOBILE HEADER -->
  <header
    class="lg:hidden flex justify-between items-center px-4 py-3 bg-white shadow-md fixed top-0 left-0 w-full z-50">
    <!-- Sidebar Toggle Button -->
    <button onclick="toggleSidebar()" class="w-[21px] h-[21px]">
      <img src="assets/Images/quill_hamburger.png" alt="Menu Icon" class="w-full h-full object-contain" />
    </button>

    <!-- Centered Logo -->
    <div class="absolute left-1/2 transform -translate-x-1/2">
      <img src="assets/Images/logo 34.png" alt="Logo" class="w-[46px] h-[46px] object-contain" />
    </div>
  </header>

  <div class="grid lg:grid-cols-[260px_1fr] min-h-screen pt-[60px] lg:pt-0 lg:p-6 xl:p-6 " style="margin-top: 10px;">

    <!-- 🧭 Sidebar -->
    <aside id="mobileSidebar"
      class="fixed lg:static top-0 left-0 z-50 bg-white w-[240px] h-screen transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out shadow lg:shadow-none">
      <?php
      $isHomePage = true;
      include 'Includes/Sidebar.php';
      ?>
    </aside>
    <script>
      function showNewContent() {
        const content = document.getElementById('mainContent');
        content.classList.remove('opacity-100', 'translate-y-0');
        content.classList.add('opacity-0', '-translate-y-5');

        setTimeout(() => {
          // Replace content here if needed
          content.innerHTML = '<div class="text-center text-xl font-bold text-[#673AB7]">Coming Soon: More Features</div>';

          // Animate in
          content.classList.remove('opacity-0', '-translate-y-5');
          content.classList.add('opacity-100', 'translate-y-0');
        }, 300);
      }
    </script>


    <!-- MAIN CONTENT -->
    <main
      class="w-full overflow-hidden flex items-start bg-white min-h-screen p-4 justify-center relative lg:p-6 xl:p-6">

      <div class="w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[2fr_1fr] gap-6">

        <!-- LEFT SECTION -->
        <section class="space-y-6">
          <div class="text-2xl sm:text-3xl font-bold leading-snug">
            <h1>Welcome to <span class="text-[#673AB7] text-3xl sm:text-4xl">MaXXify</span> Academy</h1>
            <h2 class="text-[#673AB7] text-2xl sm:text-3xl font-bold mt-4">
              Pakistan’s #1 Platform for your Entrance Exams
            </h2>
          </div>

          <!-- Add this to your <head> or before </body> -->
          <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
          <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
          <script>
            // Delay WOW.js init by 2 seconds
            setTimeout(function() {
              new WOW().init();
            }, 2000);
          </script>

          <!-- Animated Grid -->
          <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">

            <!-- MDCAT -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0s">
              <a href="mdcat.php"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-red-700 hover:bg-red-50 transition"
                style="border: 1px solid #FF0000; box-shadow: 4px 19px 20px -15px #FF0000;">
                <img src="assets/Images/mdi_doctor.png" alt="MDCAT Icon" class="w-5 h-5">
                <span class="text-sm font-semibold">MDCAT</span>
              </a>
            </div>

            <!-- AI -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0.1s">
              <a href="javascript:void(0)" onclick="checkLoginAndRedirect('quizBuilder.php')"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-blue-700 hover:bg-blue-50 transition"
                style="border: 1px solid #0B50B8; box-shadow: 4px 19px 20px -15px #0B50B8;">
                <img src="assets/Images/mingcute_ai-fill.png" alt="AI Icon" class="w-5 h-5">
                <span class="text-sm font-semibold">AI Quiz Builder</span>
              </a>
            </div>

            <!-- NUMS -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
              <a href="nums.php"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-green-700 hover:bg-green-50 transition"
                style="border: 1px solid #079200; box-shadow: 4px 19px 20px -15px #08A300;">
                <img src="assets/Images/ion_document.png" alt="NUMS Icon" class="w-5 h-5">
                <span class="text-sm font-semibold">NUMS</span>
              </a>
            </div>

            <!-- Shortlisting -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">
              <a href="shortlisting.php"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-blue-800 hover:bg-blue-50 transition"
                style="border: 1px solid #052452; box-shadow: 4px 19px 20px -15px #0B50B8;">
                <img src="assets/Images/fluent_clipboard-task-list-20-filled.png" alt="Shortlisting"
                  class="w-5 h-5 mr-2">
                <span class="text-sm font-semibold">Shortlisting</span>
              </a>
            </div>

            <!-- F.SC -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
              <a href="fsc.php"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-green-700 hover:bg-green-50 transition"
                style="border: 1px solid; box-shadow: 4px 19px 20px -15px #089D00;">
                <img src="assets/Images/wpf_books.png" alt="F.SC Icon" class="w-5 h-5">
                <span class="text-sm font-semibold">F.SC</span>
              </a>
            </div>

            <!-- Flashcards -->
            <div class="wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
              <a href="flashcards.php"
                class="rounded-lg flex items-center justify-around p-2 h-[48px] text-red-800 hover:bg-red-50 transition"
                style="border: 1px solid; box-shadow: 4px 19px 20px -15px #990000;">
                <img src="assets/Images/solar_card-bold.png" alt="Flashcards" class="w-5 h-5">
                <span class="text-sm font-semibold">Flashcards</span>
              </a>
            </div>

          </div>



          <!-- Discount Timer -->
          <section class="my-10 font-[Manrope]">
            <!-- Heading -->
            <h2 class="text-[#673AB7] font-bold mb-6 leading-[41px] text-[18px] lg:text-3xl">
              Discount Ends In
            </h2>

            <!-- Flex Layout (Always Row) -->
            <div class="flex items-center justify-start gap-4 flex-nowrap">

              <!-- Timer -->
              <div
                class="flex-shrink-0 flex justify-center items-center w-[124px] h-[124px] md:w-36 md:h-36 lg:w-44 lg:h-44 rounded-full animate-pulse transition duration-300"
                style="background: linear-gradient(180deg, #E1E1E1 0%, #FFFFFF 100%);
           box-shadow: 
             0px 30px 60px rgba(51, 51, 51, 0.15),
             inset 0px 10px 10px rgba(255, 255, 255, 0.8);">
                <h3 id="timer" class="text-[#673AB7] font-bold leading-none text-[16px] md:text-xl lg:text-2xl">
                  00:00:00
                </h3>
              </div>


              <!-- Button + Text -->
              <div class="flex flex-col items-start gap-2 text-left">
                <a href="https://whatsapp.com/channel/0029Vb6AUWh3bbV6AeINsZ1q" target="_blank" rel="noopener noreferrer">
                  <button class="text-white text-[12px] md:text-[15px] leading-[22px] font-semibold rounded-[30px] px-[19px] md:px-[39px] md:py-[10px] w-[100px] md:w-[178px] h-[29px] md:h-[42px] 
                        transition-all duration-300 ease-in-out transform 
                        hover:-translate-y-[1px] hover:shadow-[0_4px_12px_rgba(103,58,183,0.4)] 
                        active:translate-y-[1px] active:shadow-inner focus:outline-none"
                    style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%)">
                    Avail Now
                  </button>
                </a>

                <p class="font-semibold text-[6px] md:text-sm leading-[22px] text-black">
                  Join Our Guidance Community
                </p>
              </div>


            </div>
          </section>

        </section>

        <!-- RIGHT SECTION (Achievers) -->
        <?php
        include 'config.php';
        $topAchievers = [];
        $allAchievers = [];

        $stmt = $pdo->query("SELECT * FROM high_achievers WHERE top_rated = 1");
        if ($stmt) {
          $topAchievers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $stmt = $pdo->query("SELECT * FROM high_achievers");
        if ($stmt) {
          $allAchievers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        ?>
        <?php if (!empty($topAchievers)): ?>
          <aside class="px-2 py-3 lg:p-0">
            <h2 class="font-[Manrope] font-bold text-[24px] text-center leading-[22px] text-[#673AB7] mb-4">
              Our High Achievers
            </h2>
            <div class="flex flex-col justify-center items-center">
              <?php foreach ($topAchievers as $achiever): ?>
                <div
                  class="relative border-2 rounded-xl flex justify-center border-[#673AB7] w-52 h-60 bg-[#E4D5FF] overflow-hidden mb-4">
                  <div class="absolute -top-2 -right-2 z-10">
                    <img src="assets/Images/medal.png" alt="medal" class="w-[30px] h-[30px]">
                  </div>
                  <?php if (!empty($achiever['image'])): ?>
                    <img src="Admin/<?= htmlspecialchars($achiever['image']) ?>" alt="Achiever"
                      class="object-contain h-full px-2">
                  <?php else: ?>
                    <img src="assets/Images/achiver.png" alt="Achiever" class="object-contain h-full px-2">
                  <?php endif; ?>
                </div>
                <div
                  class="flex flex-col relative -mt-6 justify-center text-center bg-[#673AB7] text-white rounded-xl w-40 py-1 px-2 text-xs font-semibold shadow mb-6">
                  <p><?= htmlspecialchars($achiever['name']) ?></p>
                  <p><?= htmlspecialchars($achiever['marks']) ?></p>
                  <p><?= htmlspecialchars($achiever['category']) ?></p>
                </div>
              <?php endforeach; ?>
            </div>
          </aside>
        <?php endif; ?>
      </div>

    </main>
  </div>

  <!-- Sidebar Toggle Script -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('mobileSidebar');
      sidebar.classList.toggle('-translate-x-full');
    }
  </script>






  <main class="w-full px-4 py-10 max-w-[1440px] mx-auto space-y-20">
    <div class="text-center mt-10 space-y-5 px-4 lg:px-0 font-[Manrope]">

      <!-- Bigger Heading -->
      <h1 class="text-[#673AB7] text-[30px] sm:text-[38px] md:text-[46px] leading-[42px] font-bold
             wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
        Trusted By 90K+ Students
      </h1>

      <!-- Stats Section -->
      <div class="mt-8 flex flex-wrap justify-center gap-8 sm:gap-12">

        <!-- Success Rate -->
        <div class="space-y-2 text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.3s">
          <h2 class="text-[#673AB7] text-[32px] sm:text-[38px] md:text-[44px] leading-[42px] font-bold">97%</h2>
          <p class="text-[#535353] text-[18px] sm:text-[20px] md:text-[22px] font-semibold leading-[30px]">
            Success Rate
          </p>
        </div>

        <!-- MCQ Attempts -->
        <div class="space-y-2 text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
          <h2 class="text-[#673AB7] text-[32px] sm:text-[38px] md:text-[44px] leading-[42px] font-bold">500K</h2>
          <p class="text-[#535353] text-[18px] sm:text-[20px] md:text-[22px] font-semibold leading-[30px]">
            MCQ Attempts
          </p>
        </div>

        <!-- Minutes Spent -->
        <div class="space-y-2 text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
          <h2 class="text-[#673AB7] text-[32px] sm:text-[38px] md:text-[44px] leading-[42px] font-bold">1650K</h2>
          <p class="text-[#535353] text-[18px] sm:text-[20px] md:text-[22px] font-semibold leading-[30px]">
            Minutes Spent
          </p>
        </div>

      </div>
    </div>


    <div class="space-y-10 px-4 lg:px-0">

      <!-- Home Section -->
      <div class="pt-8 font-[Manrope] px-4 lg:px-20">

        <!-- Animated Heading -->
        <h1
          class="text-[#535353] text-[24px] sm:text-[28px] md:text-[32px] lg:text-[36px] leading-[36px] font-bold text-start wow animate__animated animate__fadeInDown animate__delay-0.3s">
          What are you <span class="text-[#673AB7]">looking</span> for?
        </h1>

        <!-- Animated Button Group -->
        <div class="flex flex-col lg:flex-row items-center justify-center gap-4 sm:gap-5 lg:gap-6 mt-6">

          <!-- Entrance Exams Button -->
          <div id="btn1" onclick="setActive(this)"
            class="exam-btn w-[138px] h-[40px] rounded-[5px] border border-[#FF0000] shadow-[4px_19px_20px_-15px_#FF0000] flex items-center justify-center bg-white wow animate__animated animate__zoomIn animate__delay-0.6s">
            <button class="text-[12px] leading-[22px] font-medium bg-clip-text text-transparent"
              style="background-image: linear-gradient(180deg, #FF0000 0%, #990000 100%);">
              Entrance Exams
            </button>
          </div>

          <!-- Board Exams Button -->
          <div id="btn2" onclick="setActive(this)"
            class="exam-btn w-[138px] h-[40px] rounded-[5px] border border-[#0B50B8] shadow-[4px_19px_20px_-15px_#0B50B8] flex items-center justify-center bg-white wow animate__animated animate__zoomIn animate__delay-0.9s">
            <button class="text-[12px] leading-[22px] font-medium bg-clip-text text-transparent"
              style="background-image: linear-gradient(180deg, #8000FF 0%, #4B0082 100%);">
              Board Exams
            </button>
          </div>

          <!-- Other Exams Button -->
          <div id="btn3" onclick="setActive(this)"
            class="exam-btn w-[138px] h-[40px] rounded-[5px] border border-[#079200] shadow-[4px_19px_20px_-15px_#08A300] flex items-center justify-center bg-white wow animate__animated animate__zoomIn animate__delay-1.2s">
            <button class="text-[12px] leading-[22px] font-medium bg-clip-text text-transparent"
              style="background-image: linear-gradient(180deg, #089D00 0%, #033700 100%);">
              Other Exams
            </button>
          </div>
        </div>
      </div>

    </div>

    <script>
      function setActive(clickedDiv) {
        const buttons = document.querySelectorAll('.exam-btn');

        buttons.forEach(btn => {
          btn.classList.add('bg-white');

          // Reset button text
          const btnText = btn.querySelector('button');
          btnText.classList.remove('text-white');
          btnText.classList.add('bg-clip-text', 'text-transparent');

          // Set gradient text back for each button
          if (btn.id === 'btn1') {
            btnText.style.backgroundImage = 'linear-gradient(180deg, #FF0000 0%, #990000 100%)';
          } else if (btn.id === 'btn2') {
            btnText.style.backgroundImage = 'linear-gradient(180deg, #8000FF 0%, #4B0082 100%)';
          } else if (btn.id === 'btn3') {
            btnText.style.backgroundImage = 'linear-gradient(180deg, #089D00 0%, #033700 100%)';
          }

          // Reset background
          btn.style.background = 'white';
        });

        // Set clicked button as active
        clickedDiv.classList.remove('bg-white');
        clickedDiv.style.background = 'linear-gradient(180deg, #0B50B8 0%, #052452 100%)';

        const btnText = clickedDiv.querySelector('button');
        btnText.classList.remove('bg-clip-text', 'text-transparent');
        btnText.classList.add('text-white');
        btnText.style.backgroundImage = 'none';
      }
    </script>
    <div class="pt-10 px-4 lg:px-20">
      <h1
        class="text-[#535353] text-[24px] sm:text-[28px] md:text-[32px] lg:text-[36px] leading-[36px] font-bold text-start font-[Manrope] wow fadeInUp"
        data-wow-delay="0.2s">
        We have everything for you
      </h1>
    </div>

    <!-- Cards Container -->
    <div class="hidden lg:flex flex-wrap justify-center items-start gap-6 max-w-[1240px] mx-auto mt-16 px-4">
      <!-- Card 1 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.3s">
        <h2
          class="text-transparent text-[28px] font-bold leading-[32px] bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]">
          70,000+</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">MCQ’s with Detailed Explanation</p>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-1">On your One click Away</p>
        <div class="absolute bottom-4 right-4 w-[90px] h-[90px]">
          <img src="assets/Images/card1.png" alt="MCQ Icon" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Card 2 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.4s">
        <h2
          class="text-transparent text-[28px] font-bold leading-[32px] bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]">
          Real Time</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">Stats And Analysis</p>
        <div class="absolute bottom-4 right-4 w-[90px] h-[90px]">
          <img src="assets/Images/card2.png" alt="Stats Icon" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Card 3 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.5s">
        <h2
          class="text-transparent text-[28px] font-bold leading-[32px] bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]">
          AI Quiz</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">Make full length papers with just one
          click.</p>
        <div class="absolute bottom-4 right-4 w-[90px] h-[90px]">
          <img src="assets/Images/card3.png" alt="AI Icon" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Card 4 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.6s">
        <h2 class="text-[28px] font-bold leading-[32px] text-[#0B50B8]">Lectures</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">Watch our Lectures for best exam
          preparation</p>
        <div class="absolute bottom-4 right-4 w-[90px] h-[90px]">
          <img src="assets/Images/card4.png" alt="Lectures Icon" class="w-full h-full object-contain">
        </div>
      </div>

      <!-- Card 5 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.7s">
        <h2
          class="text-transparent text-[28px] font-bold leading-[32px] bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]">
          Video Solutions</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">Master your concepts with our video
          lectures</p>
        <div class="flex items-center mt-3">
          <h3 class="text-[18px] font-bold text-[#0B50B8]">Coming Soon!</h3>
          <img src="assets/Images/card5.png" alt="Video Icon" class="w-[70px] h-[70px] object-contain ml-2">
        </div>
      </div>

      <!-- Card 6 -->
      <div onclick="activateCard(this)"
        class="card bg-white shadow-[10px_15px_100px_6px_rgba(0,0,0,0.15)] transition-all duration-300 cursor-pointer rounded-[10px] p-4 w-[300px] h-[400px] relative wow fadeInUp"
        data-wow-delay="0.8s">
        <h2
          class="text-transparent text-[28px] font-bold leading-[32px] bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]">
          Flashcards</h2>
        <p class="text-[#535353] text-[18px] font-medium leading-[28px] mt-3">Explore our Flashcards for the best
          Revisions</p>
        <div class="absolute bottom-4 right-4 w-[90px] h-[90px]">
          <img src="assets/Images/card6.png" alt="Flashcard Icon" class="w-full h-full object-contain">
        </div>
      </div>
    </div>



    <script>
      function activateCard(card) {
        document.querySelectorAll('.card').forEach(c => {
          c.classList.remove('card-active');
        });
        card.classList.add('card-active');
      }
    </script>
    <div class="block lg:hidden max-w-[600px] mx-auto mt-12 p-4 font-[Manrope]">
      <div class="grid grid-cols-2 gap-x-4 justify-items-center">

        <!-- Card 1 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp bg-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.1s">
          <h2 class="text-transparent bg-clip-text font-bold text-[18px] leading-[28px]"
            style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">70,000+</h2>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px] mt-1">MCQ’s with Detailed Explanation</p>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px]">On your One click Away</p>
          <div class="absolute bottom-2 left-2 w-[38px] h-[38px]">
            <img src="assets/Images/card1.png" alt="MCQ Icon" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Card 2 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp mt-12 bg-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.2s">
          <h2 class="text-transparent bg-clip-text font-bold text-[18px] leading-[28px]"
            style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">Real Time</h2>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px] mt-1">Stats And Analysis</p>
          <div class="absolute bottom-2 right-2 w-[38px] h-[38px]">
            <img src="assets/Images/card2.png" alt="Stats Icon" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Card 3 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp bg-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.3s">
          <h2 class="text-transparent bg-clip-text font-bold text-[18px] leading-[28px]"
            style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">AI Quiz</h2>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px] mt-1">Make full length papers with just one
            click.</p>
          <div class="absolute bottom-2 left-2 w-[38px] h-[38px]">
            <img src="assets/Images/card3.png" alt="AI Icon" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Card 4 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp mt-12 bg-gradient-to-b from-[#673AB7] to-[#2E1A51] text-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.4s">
          <h2 class="font-bold text-[18px] leading-[28px]">Lectures</h2>
          <p class="font-medium text-[10px] leading-[16px] mt-1">Watch our Lectures for best exam preparation</p>
          <div class="absolute bottom-2 right-2 w-[38px] h-[38px]">
            <img src="assets/Images/card4.png" alt="Lectures Icon" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Card 5 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp bg-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.5s">
          <h2 class="text-transparent bg-clip-text font-bold text-[18px] leading-[28px]"
            style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">Video Solutions</h2>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px] mt-1">Master your concepts with our video
            lectures</p>
          <div class="absolute bottom-2 left-2 w-[38px] h-[38px]">
            <img src="assets/Images/card5.png" alt="Video Icon" class="w-full h-full object-contain">
          </div>
        </div>

        <!-- Card 6 -->
        <div onclick="activateMobileCard(this)"
          class="mobile-card wow animate__animated animate__fadeInUp mt-12 bg-white rounded-[10px] p-4 w-[160px] h-[150px] relative shadow-[10px_10px_80px_6px_#00000020]"
          data-wow-delay="0.6s">
          <h2 class="text-transparent bg-clip-text font-bold text-[18px] leading-[28px]"
            style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">Flashcards</h2>
          <p class="text-[#535353] font-medium text-[10px] leading-[16px] mt-1">Explore our Flashcards for the best
            Revisions</p>
          <div class="absolute bottom-2 right-2 w-[38px] h-[38px]">
            <img src="assets/Images/card6.png" alt="Flashcard Icon" class="w-full h-full object-contain">
          </div>
        </div>

      </div>
    </div>

    <script>
      function activateMobileCard(card) {
        document.querySelectorAll('.mobile-card').forEach(c => {
          c.classList.remove('bg-gradient-to-b', 'from-[#673AB7]', 'to-[#2E1A51]', 'text-white');
          c.querySelectorAll('h2, p').forEach(el => {
            el.classList.remove('text-white');
            if (el.tagName === 'H2') {
              el.classList.add('text-transparent', 'bg-clip-text');
              el.style.backgroundImage =
                'linear-gradient(180deg, #0B50B8 0%, #052452 100%)';
            }
          });
        });

        card.classList.add('bg-gradient-to-b', 'from-[#673AB7]', 'to-[#2E1A51]', 'text-white');
        card.querySelectorAll('h2, p').forEach(el => {
          el.classList.add('text-white');
          if (el.tagName === 'H2') {
            el.classList.remove('text-transparent', 'bg-clip-text');
            el.style.backgroundImage = 'none';
          }
        });
      }
    </script>
    <div class="hidden lg:flex flex-row items-center justify-between max-w-screen-lg mx-auto mt-5">
      <!-- Text Column -->
      <div class="w-1/2 text-left space-y-5">
        <h2 class="text-[38px] leading-[54px] font-semibold text-[#535353] wow animate__animated animate__fadeInUp"
          data-wow-delay="0.2s">
          The New Revolution<br>is Here
        </h2>

        <h1
          class="text-[52px] leading-[64px] font-bold bg-clip-text text-transparent wow animate__animated animate__fadeInUp"
          data-wow-delay="0.4s" style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">
          AI Quiz<br>Generator
        </h1>

        <p class="text-[18px] font-medium text-[#535353] wow animate__animated animate__fadeInUp" data-wow-delay="0.6s">
          Less Practice. Best Results
        </p>

        <div class="flex flex-wrap justify-start gap-3 pt-3 wow animate__animated animate__zoomIn"
          data-wow-delay="0.8s">
          <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
              transition-all duration-300 ease-in-out transform 
              hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
              active:translate-y-[1px] active:shadow-inner focus:outline-none"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Generate FLP
          </button>

          <!-- Go to Quiz Builder Button -->
        <a href="quizBuilder.php">
  <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
              transition-all duration-300 ease-in-out transform 
              hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(103,58,183,0.4)] 
              active:translate-y-[1px] active:shadow-inner focus:outline-none"
          style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%)">
    Go to Quiz Builder
  </button>
</a>


        </div>
      </div>

      <!-- Image Column -->
      <div class="w-1/2 flex justify-end wow animate__animated animate__fadeInRightBig" data-wow-delay="1s">
        <img src="assets/Images/mobileimage2.jpg" alt="AI Quiz Generator" class="w-[380px] h-[480px] object-contain">
      </div>
    </div>

    <!-- Mobile and Tablet Only -->

    <div
      class="flex flex-row items-center justify-start gap-4 py-8 max-w-screen-lg mx-auto px-1 lg:hidden overflow-x-hidden">

      <!-- Text Column -->
      <div class="w-1/2 text-left space-y-3 wow animate__animated animate__fadeInLeft" data-wow-duration="1s"
        data-wow-delay="0.2s">
        <!-- Headline -->
        <h2 class="font-semibold text-[16px] leading-[20px] text-[#535353] font-[Manrope]">
          The New Revolution<br>is Here
        </h2>

        <!-- Title -->
        <h1 class="font-bold text-[26px] leading-[30px] text-transparent bg-clip-text font-[Manrope]"
          style="background-image: linear-gradient(180deg, #0B50B8 0%, #052452 100%)">
          AI Quiz<br>Generator
        </h1>

        <!-- Subtext -->
        <p class="text-[15px] font-medium text-[#535353] font-[Manrope]">
          Less Practice. Best Results
        </p>

        <!-- Buttons -->
        <div class="flex flex-wrap justify-start gap-2 pt-3">
          <!-- Button 1 -->
          <button
            class="text-white text-[11px] leading-[22px] font-semibold rounded-[30px] font-[Manrope] w-[108px] h-[30px]"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Generate FLP
          </button>
       <a href="quizBuilder.php">
          <!-- Button 2 -->
          <button
            class="text-white text-[11px] leading-[22px] font-semibold rounded-[30px] font-[Manrope] w-[108px] h-[30px]"
            style="background: linear-gradient(180deg, #673AB7 0%, #2E1A51 100%)">
            Go to Quiz Builder
          </button>
</a>
        </div>
      </div>

      <!-- Image Column -->
      <div class="w-1/2 flex justify-center wow animate__animated animate__fadeInRight" data-wow-duration="1s"
        data-wow-delay="0.4s">
        <img src="assets/Images/mobileimage2.jpg" alt="AI Quiz Generator"
          class="w-[120px] h-[150px] rounded-[12px] object-contain">
      </div>

    </div>
    
<section class="howitworks">
  <div class="container">
    <!-- Left Side Steps -->
  <div>
  <h2 class="section-title">How It Works</h2>

  <!-- Step 1: Sign Up -->
  <div class="step-row">
    <h3 class="step-number">01</h3>
    <div class="step-card animate-float">
      <div class="step-icon bg-blue-light">
        <img src="assets/Images/images2/solar_login-3-broken.png" alt="Step 1" class="step-img">
      </div>
      <div>
        <p class="step-title">Sign Up</p>
        <p class="step-desc">Join Maxxify and begin your journey.</p>
      </div>
    </div>
  </div>

  <!-- Step 2: Choose Plan -->
  <div class="step-row">
    <div class="step-card animate-float">
      <div class="step-icon bg-orange-light">
        <img src="assets/Images/images2/solar_login-3-broken (1).png" alt="Step 2" class="step-img">
      </div>
      <div>
        <p class="step-title">Choose Plan</p>
        <p class="step-desc">Pick a package that suits you.</p>
      </div>
    </div>
    <h3 class="step-number">02</h3>
  </div>

  <!-- Step 3: Start Learning -->
  <div class="step-row">
    <h3 class="step-number">03</h3>
    <div class="step-card animate-float">
      <div class="step-icon bg-pink-light">
        <img src="assets/Images/images2/solar_login-3-broken (2).png" alt="Step 3" class="step-img">
      </div>
      <div>
        <p class="step-title">Start Practicing</p>
        <p class="step-desc">Solve MCQs and prep for FSc & MDCAT.</p>
      </div>
    </div>
  </div>
</div>


    <!-- Right Side Layout -->
    <div class="right-grid">
      <!-- Left Column -->
      <div class="left-col">
        <img src="assets/Images/images2/Photo.png" alt="Small Step" class="small-img">

        <!-- 10K Card with floating animation -->
        <div class="stats-card animate-float">
          <!-- Circle images -->
          <div class="circle-stack">
            <img src="assets/Images/images2/3.png" class="circle-img" alt="User 1">
            <img src="assets/Images/images2/1.png" class="circle-img" alt="User 2">
            <img src="assets/Images/images2/2.png" class="circle-img" alt="User 3">
          </div>
          <!-- Text -->
        <div class="stats-text">
  <p class="stats-number">10K+</p>
  <p class="stats-label">Bright Minds Transformed</p>
</div>

        </div>
      </div>

      <!-- Right Column (big image) -->
      <div>
        <img src="assets/Images/images2/Photo (1).png" alt="Big Step" class="big-img">
      </div>
    </div>
  </div>
</section>


<style>
/* Container */
.howitworks {
  padding: 4rem 1rem;
  background: #fff;
}
.container {
  max-width: 72rem; /* 6xl */
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1fr;
  gap: 3rem;
  align-items: start;
}
@media (min-width: 1024px) {
  .container {
    grid-template-columns: 1fr 1fr;
  }
}

/* Left Side */
.section-title {
  font-size: 1.875rem; /* text-3xl */
  font-weight: 700;
  color: #1E242C;
  margin-bottom: 2.5rem;
}
@media (min-width: 768px) {
  .section-title {
    font-size: 2.25rem; /* text-4xl */
  }
}
.step-row {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 1rem;
  align-items: start;
  margin-bottom: 2.5rem;
}
.step-number {
  font-size: 50px;
  font-weight: 500;
  line-height: 120%;
  color: #002B6B;
  grid-column: span 1 / span 1;
}
.step-card {
  grid-column: span 4 / span 4;
  width: 380px;
  max-width: 100%;
  background: #fff;
  border: 1px solid #EDEEF0;
  box-shadow: 6px 15px 60px rgba(0,43,107,0.15);
  border-radius: 0.75rem;
  padding: 1.25rem;
  display: flex;
  gap: 1rem;
  align-items: flex-start;
}
.step-icon {
  width: 3rem;
  height: 3rem;
  display: flex;
  justify-content: center;
  align-items: center;
  border-radius: 9999px;
}
.bg-blue-light { background: #E1E9FE; }
.bg-orange-light { background: #FFE4CC; }
.bg-pink-light { background: #FFD6E7; }
.step-img {
  width: 2rem;
  height: 2rem;
  object-fit: contain;
}
.step-title {
  font-size: 1.125rem;
  font-weight: 500;
  color: #1E242C;
}
.step-desc {
  font-size: 0.875rem;
  color: #6B7280; /* gray-500 */
}

/* Right Side */
.right-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
  align-items: start;
}
.left-col {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.small-img {
  width: 180px;
  height: 280px;
  object-fit: cover;
  border-radius: 0.75rem;
  box-shadow: 0 10px 15px rgba(0,0,0,0.1);
  margin: 0 auto;
}
.stats-card {
  width: 230px;
  height: 70px;
  background: #fff;
  border: 1px solid #EDEEF0;
  box-shadow: 6px 15px 60px rgba(0,43,107,0.15);
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 0.75rem;
  margin: 0 auto;
}
.circle-stack {
  display: flex;
  margin-left: -0.5rem;
}
.circle-img {
  width: 3rem;
  height: 3rem;
  border-radius: 9999px;
  border: 2px solid #fff;
  object-fit: cover;
  margin-left: -0.5rem;
}
.stats-text { text-align: right; }
.stats-number {
  font-size: 1rem;
  font-weight: 700;
  color: #2563EB; /* blue-600 */
}
.stats-label {
  font-size: 0.75rem;
  color: #6B7280;
}
.big-img {
  width: 280px;
  height: 400px;
  object-fit: cover;
  border-radius: 0.75rem;
  box-shadow: 0 10px 15px rgba(0,0,0,0.1);
  margin: 0 auto;
}

/* Floating Animation */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}
.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>

    <div
      class="grid grid-cols-2 items-start gap-4 px-2 py-4 bg-white max-w-screen-xl mx-auto lg:hidden mt-6 mb-6 wow animate__animated animate__fadeInUp"
      data-wow-delay="0.3s">

      <!-- Left Content -->
      <div class="text-left space-y-3">
        <h2 class="font-[600] text-[14px] leading-[18px] text-[#535353] font-[Manrope]">
          Download Our App
        </h2>

        <h1
          class="text-transparent bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452] font-[700] text-[24px] leading-[27px] font-[Manrope]">
          Maxxify Academy App
        </h1>

        <p class="text-[13px] font-medium text-[#535353] font-[Manrope]">
          Less Practice. Best Results
        </p>
        <div class="flex items-center gap-3 pt-1">
          <button class="text-white  text-[10px] leading-[22px] rounded-[30px] font-[Manrope] px-[19px] py-[4px] w-[100px] sm:w-[120px] md:w-[140px] 
           transition-all duration-300 ease-in-out transform
           hover:-translate-y-[1px] hover:shadow-[0_4px_12px_rgba(255,0,0,0.4)]
           active:translate-y-[1px] active:shadow-inner focus:outline-none"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Generate FLP
          </button>
        </div>

      </div>

      <!-- Right Image -->
      <div class="flex justify-end items-center">
        <img src="assets/Images/mobileimage1.jpg" alt="Maxxify App Screenshot" class="w-[180px] h-[200px] object-contain">
      </div>

    </div>
    <div
      class="hidden lg:flex flex-col lg:flex-row items-center justify-between gap-6 py-14 bg-white w-full px-4 md:px-10 wow animate__animated animate__fadeIn">

      <!-- Left Content -->
      <div class="w-full lg:w-1/2 text-left space-y-5 wow animate__animated animate__fadeInLeft" data-wow-delay="0.2s">
        <h2 class="text-[44px] leading-[60px] font-semibold text-[#535353] font-[Manrope]">
          Download Our App
        </h2>

        <h1 class="text-transparent bg-clip-text bg-gradient-to-b from-[#0B50B8] to-[#052452]
       font-[700] text-[60px] leading-[68px] font-[Manrope]">
          Maxxify Academy App
        </h1>

        <p class="text-[20px] font-medium text-[#535353] font-[Manrope]">
          Less Practice. Best Results
        </p>

        <div class="flex flex-wrap lg:flex-nowrap items-center gap-4 pt-2">
          <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
            transition-all duration-300 ease-in-out transform 
            hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
            active:translate-y-[1px] active:shadow-inner focus:outline-none"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Generate FLP
          </button>

          <img src="assets/Images/image 34.png" alt="App Icon" class="w-[140px] h-auto">
        </div>
      </div>

      <!-- Right Image -->
      <div class="w-full lg:w-1/2 flex justify-center lg:justify-end wow animate__animated animate__fadeInRight"
        data-wow-delay="0.4s">
        <img src="assets/Images/mobileimage1.jpg" alt="Maxxify App Screenshot" class="w-[460px] h-[460px] object-contain">
      </div>
    </div>
    <div
      class="hidden lg:flex flex-col lg:flex-row items-center justify-between gap-6 px-4 md:px-10 lg:px-20 py-10 bg-white max-w-screen-xl mx-auto wow animate__animated animate__fadeInUp"
      data-wow-duration="1.2s" data-wow-delay="0.2s">
      <!-- Left Content -->
      <div class="w-full lg:w-1/2 text-left space-y-4">
        <h1
          class="text-[32px] md:text-[48px] lg:text-[60px] font-bold text-blue-800 leading-tight font-[Manrope] wow animate__animated animate__fadeInLeft"
          data-wow-delay="0.3s">
          The Reserve
        </h1>

        <p class="text-[16px] md:text-[20px] lg:text-[24px] font-medium text-[#535353] font-[Manrope] wow animate__animated animate__fadeInLeft"
          data-wow-delay="0.5s">
          Everything a Student Needs in One Place
        </p>
               <a href="shortlisting.php">
        <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
    transition-all duration-300 ease-in-out transform 
    hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
    active:translate-y-[1px] active:shadow-inner focus:outline-none"
          style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
          Unlock The Vault
        </button>
        </a>

      </div>

      <!-- Right Image -->
      <div
        class="w-full lg:w-1/2 flex justify-center lg:justify-end mt-6 lg:mt-0 wow animate__animated animate__fadeInRight"
        data-wow-delay="0.6s">
        <img src="assets/Images/image 36.png" alt="The Reserve"
          class="w-[280px] md:w-[400px] lg:w-[500px] h-auto object-contain" />
      </div>
    </div>



    <!-- 🔹 Mobile View Only -->
    <div class="block lg:hidden px-5 py-12 bg-white max-w-screen-xl mx-auto wow animate__animated animate__fadeInUp"
      data-wow-delay="0.2s" data-wow-duration="1s">
      <div class="flex flex-row items-center justify-between gap-4">

        <!-- Left Content -->
        <div class="w-1/2 space-y-3 wow animate__animated animate__zoomIn" data-wow-delay="0.3s"
          data-wow-duration="0.8s">
          <h1 class="text-[22px] font-bold text-blue-800 leading-tight font-[Manrope]">
            The Reserve
          </h1>
          <p class="text-[13px] font-medium text-[#535353] font-[Manrope] leading-[18px]">
            Everything a Student Needs in One Place
          </p>
            <a href="shortlisting.php">
          <button
            class="text-white font-semibold text-[10px] leading-[22px] rounded-[30px] font-[Manrope] px-[20px] py-[4px]"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Unlock The Vault
          </button>
          </a>
        </div>

        <!-- Right Image -->
        <div class="w-1/2 flex justify-end wow animate__animated animate__zoomIn" data-wow-delay="0.5s"
          data-wow-duration="0.8s">
          <img src="assets/Images/image 36.png" alt="The Reserve" class="w-[140px] sm:w-[160px] h-auto object-contain">
        </div>
      </div>
    </div>


    <section class="w-full px-4 py-16 max-w-[1440px] mx-auto">
      <div class="flex flex-col items-center gap-24">

        <!-- Top Images Row (V shape) -->
        <div class="flex flex-col lg:flex-row justify-center gap-8 mt-4">
          <img src="assets/Images/side1.png" alt="Left Image" class="object-cover w-full max-w-[300px] self-start" />
          <img src="assets/Images/bet1.png" alt="Center Image"
            class="object-cover w-full max-w-[300px] self-center mt-6 lg:mt-12" />
          <img src="assets/Images/side2.png" alt="Right Image" class="object-cover w-full max-w-[300px] self-start" />
        </div>

        <!-- Text Content -->
        <div class="w-full md:w-2/3 lg:w-1/2 text-left space-y-5 mt-10 font-[Manrope]">

          <!-- Subheading -->
          <p class="wow fadeInDown text-[#535353] font-semibold text-[18px] md:text-[26px] lg:text-[34px] leading-[28px] md:leading-[34px] lg:leading-[40px]"
            data-wow-delay="0.2s" data-wow-duration="1s">
            Our Journey
          </p>

          <!-- Main Heading -->
          <h1 class="wow zoomIn font-bold text-[24px] md:text-[38px] lg:text-[50px] leading-[34px] md:leading-[48px] lg:leading-[60px] 
             bg-gradient-to-b from-[#0B50B8] to-[#052452] text-transparent bg-clip-text" data-wow-delay="0.4s"
            data-wow-duration="1.1s">
            Our Path to Maxxify
          </h1>

          <!-- Paragraph -->
          <p class="wow fadeInUp text-[#535353] font-semibold text-[14px] md:text-[22px] lg:text-[30px] leading-[22px] md:leading-[34px] lg:leading-[44px]"
            data-wow-delay="0.6s" data-wow-duration="1.2s">
            Explore Maxxify Journey: The Pioneer of Pakistan’s Ed-Tech Revolution
          </p>

          <!-- Button -->
          <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
    transition-all duration-300 ease-in-out transform 
    hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
    active:translate-y-[1px] active:shadow-inner focus:outline-none"
            style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
            Follow the Dream
          </button>

        </div>


        <!-- Bottom Images Row (∧ shape) -->
        <div class="flex flex-col lg:flex-row justify-center gap-8 mt-12">
          <img src="assets/Images/side3.png" alt="Left Image" class="object-cover w-full max-w-[300px] self-end" />
          <img src="assets/Images/bet2.png" alt="Center Image"
            class="object-cover w-full max-w-[300px] self-center mb-6 lg:mb-12" />
          <img src="assets/Images/side4.png" alt="Right Image" class="object-cover w-full max-w-[300px] self-end" />
        </div>

      </div>
    </section>


    <!-- ............................ -->

    <section class="w-full px-4 mt-6 space-y-4 max-w-[1440px] mx-auto text-center font-[Manrope]">

      <!-- Gradient Heading -->
      <h2
        class="text-[28px] md:text-[40px] lg:text-[46px] font-extrabold leading-[52px] bg-gradient-to-b from-[#0B50B8] to-[#052452] text-transparent bg-clip-text wow fadeInDown"
        data-wow-delay="0.2s" data-wow-duration="1.2s">
        Meet Our High Achievers
      </h2>

      <!-- Subheading -->
      <p class="text-[18px] md:text-[28px] lg:text-[30px] font-semibold leading-[38px] text-[#535353] wow fadeInUp"
        data-wow-delay="0.4s" data-wow-duration="1.2s">
        The Driving Force Behind Maxxify
      </p>

    </section>


    <!-- ..................................... -->

    <section class="w-full px-4 mt-10 max-w-[1440px] mx-auto font-[Manrope]">
      <!-- Section Heading -->


      <!-- Achiever Slider -->
      <div class="achiever-slider flex gap-6 ">
        <?php foreach ($allAchievers as $achiever): ?>
          <div class="!flex !flex-col !justify-center !items-center flex-shrink-0">

            <!-- Individual Card Title -->
            <h2 class="text-[#673AB7] text-[18px] md:text-xl font-bold mb-2 text-center">
              Our High Achievers
            </h2>

            <!-- Image Card -->
            <div
              class="relative border-2 rounded-xl flex justify-center items-center border-[#673AB7] w-56 h-64 bg-[#E4D5FF] overflow-hidden">
              <div class="absolute -top-3 -right-3 z-10">
                <img src="assets/Images/medal.png" alt="medal" class="w-[36px] h-[36px]">
              </div>
              <?php if (!empty($achiever['image'])): ?>
                <img src="Admin/<?= htmlspecialchars($achiever['image']) ?>" alt="Achiever"
                  class="object-contain h-full px-2">
              <?php else: ?>
                <img src="assets/Images/achiver.png" alt="Achiever" class="object-contain h-full px-2">
              <?php endif; ?>
            </div>

            <!-- Footer Info -->
            <div class="flex flex-col relative -mt-6 justify-center text-center 
            bg-gradient-to-b from-[#673AB7] to-[#2E1A51] 
            text-white rounded-xl w-44 py-1.5 px-2 
            text-xs font-semibold shadow">
              <p><?= htmlspecialchars($achiever['name']) ?></p>
              <p><?= htmlspecialchars($achiever['marks']) ?></p>
              <p><?= htmlspecialchars($achiever['category']) ?></p>
            </div>

          </div>
        <?php endforeach; ?>
      </div>
    </section>


    <!-- .................................... -->
    <section class="w-full px-4 mt-18 max-w-[1440px] mx-auto space-y-10 font-[Manrope]">

      <!-- Heading -->
      <div class="max-w-3xl mx-auto text-center wow animate__animated animate__fadeInUp" data-wow-delay="0.2s">
        <h1 class="text-[24px] md:text-[32px] lg:text-[44px] xl:text-[48px]
      font-extrabold leading-[32px] md:leading-[40px] lg:leading-[52px] xl:leading-[58px]
      bg-gradient-to-b from-[#0B50B8] to-[#052452] text-transparent bg-clip-text">
          Endless Journeys, Endless Stories
        </h1>
      </div>

      <!-- Paragraph -->
      <div class="max-w-3xl mx-auto text-left wow animate__animated animate__fadeInUp" data-wow-delay="0.4s">
        <p class="text-[14px] md:text-[18px] lg:text-[20px] xl:text-[22px]
      leading-[24px] md:leading-[30px] lg:leading-[34px] xl:leading-[36px]
      font-medium text-[#535353]">
          Still unsure? Hear it from our alumni. Maxxify wasn’t just a stepping stone –<br class="hidden md:inline">
          it was their launchpad to success. Because success isn’t a destination; it’s a journey –<br
            class="hidden md:inline">
          and we’re here to guide you every step of the way.
        </p>
      </div>

      <!-- Button -->
      <div class="max-w-3xl mx-auto text-left lg:text-center wow animate__animated animate__fadeInUp"
        data-wow-delay="0.6s">
        <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
    transition-all duration-300 ease-in-out transform 
    hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
    active:translate-y-[1px] active:shadow-inner focus:outline-none"
          style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
          Read the Stories
        </button>
      </div>

      <!-- Image -->
      

    </section>

<section class="whychoose">
  <div class="whychoose-container">
    
    <!-- Heading -->
    <div class="whychoose-heading">
     <h2>Why Choose <br class="whychoose-mobile-break"/> Maxxify Academy?</h2>
<p>
  Maxxify Academy empowers students with quality education and focused training. 
  From <strong>MCQs</strong> to <strong>FSC</strong> and <strong>MDCAT</strong> prep, 
  we make learning simple, interactive, and effective for your success.
</p>

    </div>

    <!-- Main Grid -->
  <div class="whychoose-grid">
      
  <!-- Left Side -->
  <div class="whychoose-left relative flex flex-col md:flex-row items-center justify-center gap-8 md:gap-20">
    <!-- Practice MCQs -->
    <div class="whychoose-card tall animate-float">
      <img src="assets/Images/images2/Iconly-Pc-1723800402620 1.png" alt="MCQ Practice" class="whychoose-icon">
      <div>
        <h3 class="whychoose-title">Practice MCQs</h3>
        <p class="whychoose-desc">Sharpen your skills with topic-wise MCQs for FSC & MDCAT.</p>
      </div>
   <button class="whychoose-btn">Practice</button>

    </div>

    <!-- Active Learners -->
    <div class="whychoose-card small animate-float">
      <img src="assets/Images/images2/global-search.png" alt="Learners" class="whychoose-icon-sm">
      <div>
        <p class="whychoose-stats">50K+</p>
        <p class="whychoose-sub">Active Students Learning</p>
      </div>
    </div>
  </div>

  <!-- Center Video -->
  <div class="whychoose-video animate-float">
    <!-- Circles -->
    <div class="whychoose-circle circle-lg"></div>
    <div class="whychoose-circle circle-sm"></div>

    <!-- Video Card -->
<div class="whychoose-video-card">
  <!-- Images instead of Video -->
  <div class="whychoose-images">
    <img src="assets/Images/images2/videoimage.webp" alt="Demo Image 1">

  </div>

  <button id="toggleBtn" class="whychoose-video-btn">View Demo</button>
</div>

  </div>

  <!-- Right Side -->
  <div class="whychoose-right flex flex-col items-start gap-6">
    <!-- Smart Learning -->
    <div class="whychoose-card wide animate-float">
      <img src="assets/Images/images2/Frame.png" alt="Smart Learning" class="whychoose-icon">
    <h3 class="whychoose-title">Smart Learning Tools  Practice Plans</h3>


    
    </div>

    <!-- Exam Prep -->
    <div class="whychoose-card tall animate-float">
      <img src="assets/Images/images2/Add documents 1.png" alt="Exam Preparation" class="whychoose-icon">
      <div>
        <h3 class="whychoose-title">Exam Preparation</h3>
        <p class="whychoose-desc">Prepare effectively for FSC boards & MDCAT with guided resources.</p>
      </div>
     <button class="whychoose-btn">Prepare</button>

    </div>
  </div>

</div>

  </div>
</section>

<style>
/* Section */
.whychoose {
  padding: 4rem 1rem;
  background: #fff;
  font-family: 'Manrope', sans-serif;
}
.whychoose-container {
  max-width: 72rem;
  margin: 0 auto;
  display: flex;
  flex-direction: column;
  gap: 3rem;
 
}

/* Heading */
.whychoose-heading h2 {

  font-size: 56px;
  font-weight: 500;
  line-height: 120%;
  color: #1E242C;
  max-width: 534px;
  margin: 0 auto 1rem auto;
  text-align: center;
}
.whychoose-heading p {
  font-size: 16px;
  line-height: 150%;
  color: #414D60;
  max-width: 500px;
  margin: 0 auto 3rem auto;
  text-align: center;
}
.whychoose-mobile-break { display: none; }
@media (max-width: 768px) {
  .whychoose-mobile-break { display: block; }
}

/* Grid */
.whychoose-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  align-items: start;
}
@media (min-width: 1024px) {
  .whychoose-grid {
    grid-template-columns: 1fr auto 1fr;
    gap: 5rem;
  }
}

/* Cards */
.whychoose-card {
  background: #fff;
  border: 1px solid #EDEEF0;
  border-radius: 20px;
  box-shadow: 10px 25px 100px rgba(0,43,107,0.25);
  display: flex;
  padding: 1.5rem;
}
.whychoose-card.tall { width: 260px; height: 228px; flex-direction: column; justify-content: space-between; align-items: center; text-align: center; }
.whychoose-card.small {  align-items: center; gap: 1rem; flex-direction: row; }
.whychoose-card.wide { width: 320px; height: 120px; align-items: center; gap: 1rem; flex-direction: row; }

.whychoose-icon { width: 48px; height: 48px; margin-bottom: 1rem; }
.whychoose-icon-sm { width: 40px; height: 40px; }
.whychoose-title { font-size: 20px; font-weight: 700; color: #1E242C; margin-bottom: .5rem; }
.whychoose-desc { font-size: 12px; line-height: 150%; color: #414D60; }
.whychoose-stats { font-size: 18px; font-weight: 700; color: #1E242C; }
.whychoose-sub { font-size: 12px; color: #414D60; }

/* Buttons */
.whychoose-btn {
  margin-top: .75rem;
  font-size: 12px;
  font-weight: 500;
  color: #fff;
  width: 102px;
  height: 34px;
  border-radius: 9999px;
  background: #3385FF;
  border: 1px solid #3385FF;
}

/* Video */
.whychoose-video {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}
.whychoose-circle {
  position: absolute;
  border-radius: 9999px;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
}
.whychoose-circle.circle-lg { width: 532px; height: 532px; border: 1px solid #8AB9FF; background: rgba(0,102,255,0.05); }
.whychoose-circle.circle-sm { width: 408px; height: 408px; border: 1px solid #0066FF; background: rgba(0,102,255,0.07); }
.whychoose-video-card {
  width: 349px; height: 572px;
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  z-index: 10;
  background: #fff;
  box-shadow: 0 15px 40px rgba(0,0,0,0.1);
}
.whychoose-video-card video { width: 100%; height: 100%; object-fit: cover; border-radius: 20px; }
.whychoose-video-btn {
  position: absolute;
  bottom: 12px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 24px;
  font-weight: 500;
  font-family: 'General Sans', sans-serif;
  color: #fff;
}

/* Floating Animation */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}
.animate-float {
  animation: float 3s ease-in-out infinite;
}
</style>

<script>
  const video = document.getElementById("resumeVideo");
  const toggleBtn = document.getElementById("toggleBtn");

  toggleBtn.addEventListener("click", () => {
    if (video.paused) {
      video.play();
      toggleBtn.textContent = "Video Play";
    } else {
      video.pause();
      toggleBtn.textContent = "Video Resume";
    }
  });
</script>

    <!-- ............................................ -->

    <section class="w-full px-4 py-10 max-w-[1440px] mx-auto space-y-8 font-[Manrope]">

      <!-- Heading -->
      <div class="text-center">
        <h1 class="text-[22px] md:text-[32px] lg:text-[42px] xl:text-[52px] font-bold 
               leading-[32px] md:leading-[44px] lg:leading-[56px] xl:leading-[68px]
               bg-gradient-to-b from-[#0B50B8] to-[#052452] text-transparent bg-clip-text">
          Unleash your Potential,<br>
          Redefine the Rules!
        </h1>
      </div>

      <!-- Subheading -->
      <div class="max-w-3xl mx-auto text-left text-[#535353]">
        <p class="text-[12px] md:text-[20px] lg:text-[26px] xl:text-[30px] font-semibold 
              leading-[22px] md:leading-[30px] lg:leading-[36px] xl:leading-[42px]">
          Over 100K Future Doctors Trust Maxxify. Be next.<br>
          Own your journey.
        </p>
      </div>

      <!-- Button -->
      <div class="max-w-3xl mx-auto text-left lg:text-center">
        <button class="text-white text-[14px] leading-[22px] font-semibold rounded-full px-[30px] py-[10px] 
    transition-all duration-300 ease-in-out transform 
    hover:-translate-y-[2px] hover:shadow-[0_8px_20px_rgba(255,0,0,0.4)] 
    active:translate-y-[1px] active:shadow-inner focus:outline-none"
          style="background: linear-gradient(180deg, #FF0000 0%, #990000 100%)">
          Get Start Free
        </button>
      </div>

    </section>



    <!-- ............................. -->
    <section class="w-full px-4 py-8 max-w-[1440px] mx-auto text-center space-y-4 font-[Manrope]">

      <!-- Paragraph -->
      <p class="text-[16px] md:text-[22px] lg:text-[28px] xl:text-[32px] 
            leading-[24px] md:leading-[32px] lg:leading-[38px] xl:leading-[42px]
            font-semibold text-[#535353]">
        Start Learning for Free – No Credit Card.
      </p>





  </main>

  </section>
<section class="newsletter">
  <style>
   
    .newsletter {
      font-family: 'Manrope', sans-serif;
      background: #f3f4f6; /* gray-100 */
      padding: 3rem 1rem;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .newsletter-card {
      position: relative;
      width: 100%;
      max-width: 1120px;
      border-radius: 2rem;
      padding: 3rem 1.5rem;
      background: #644EE5;
      box-shadow: 0 4px 60px rgba(0,0,0,0.1);
      overflow: hidden;
      text-align: center;
    }

    @media (min-width: 768px) {
      .newsletter-card {
        background: #fff;
        border-radius: 4rem;
        padding: 5rem 3rem;
      }
      .newsletter-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: #644EE5;
        border-radius: 4rem;
        z-index: 0;
      }
    }

    /* Images */
    .newsletter-img {
      position: absolute;
      bottom: 0;
      height: auto;
      object-fit: contain;
      z-index: 1;
    }
    .newsletter-img.left {
      left: 0;
      width: 7rem;
    }
    .newsletter-img.right {
      right: 0;
      width: 7rem;
    }
    @media (min-width: 640px) {
      .newsletter-img.left,
      .newsletter-img.right { width: 9rem; }
    }
    @media (min-width: 768px) {
      .newsletter-img.left,
      .newsletter-img.right { width: 12rem; }
    }
    @media (min-width: 1024px) {
      .newsletter-img.left,
      .newsletter-img.right { width: 16rem; }
    }

    /* Content */
    .newsletter-content {
      position: relative;
      z-index: 2;
      max-width: 640px;
      margin: 0 auto;
    }
    .newsletter-title {
      font-family: 'General Sans', sans-serif;
      font-weight: 700;
      font-size: 2rem;
      line-height: 1.3;
      color: #FEFEFF;
      margin-bottom: 1rem;
    }
    @media (min-width: 768px) {
      .newsletter-title { font-size: 2.5rem; }
    }
    @media (min-width: 1024px) {
      .newsletter-title { font-size: 3rem; }
    }

    .newsletter-text {
      font-size: 1rem;
      line-height: 1.6;
      color: #896EE7;
      margin-bottom: 2rem;
    }
    @media (min-width: 768px) {
      .newsletter-text { font-size: 1.125rem; }
    }

    /* Form */
    .newsletter-form {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }
    @media (min-width: 640px) {
      .newsletter-form {
        flex-direction: row;
      }
    }

    .newsletter-input-wrapper {
      position: relative;
      width: 100%;
      max-width: 20rem;
      flex-shrink: 0;
    }

    .newsletter-input {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 3rem;
      border-radius: 9999px;
      border: none;
      font-size: 1rem;
      color: #111;
      background: #fff;
      outline: none;
    }

    .newsletter-input:focus {
      box-shadow: 0 0 0 2px #896EE7;
    }

    /* Email Icon */
    .newsletter-input-wrapper svg {
      position: absolute;
      top: 50%;
      left: 1rem;
      width: 1.25rem;
      height: 1.25rem;
      color: #9ca3af;
      transform: translateY(-50%);
      pointer-events: none;
    }

    /* Button */
    .newsletter-button {
      width: 100%;
      margin-top: 1rem;
      padding: 0.75rem 2rem;
      background: #fff;
      color: #644EE5;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 9999px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border: none;
      cursor: pointer;
      transition: background 0.2s ease;
    }

    .newsletter-button:hover {
      background: #f3f4f6;
    }

    @media (min-width: 640px) {
      .newsletter-button {
        width: auto;
        margin-top: 0;
        margin-left: 1rem;
      }
    }
  </style>

  <div class="newsletter-card">
    <!-- Images -->
    <img src="assets/Images/images2/pngwing.com2.png" alt="Student left" class="newsletter-img left">
    <img src="assets/Images/images2/pngwing.com (1).png" alt="Student right" class="newsletter-img right">

    <!-- Content -->
   <div class="newsletter-content">
  <h2 class="newsletter-title">Get in Touch with <span style="color:#cdcbd4;">Maxxify</span></h2>
  <p class="newsletter-text" style="color: #e4dff6;">
    Have a question, suggestion, or partnership idea? <br>
    Drop us a quick message and we’ll respond as soon as possible.
  </p>

<form class="newsletter-form" id="whatsappForm">
  <!-- Message Field -->
  <div class="newsletter-input-wrapper">
    <input type="text" id="whatsappMessage" placeholder="Your Message" required class="newsletter-input">
    <svg fill="currentColor" viewBox="0 0 20 20">
      <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
      <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
    </svg>
  </div>

  <!-- Submit Button -->
  <button type="submit" class="newsletter-button">Send Message</button>
</form>

<script>
  document.getElementById("whatsappForm").addEventListener("submit", function(e) {
    e.preventDefault(); // prevent normal form submit
    
    let message = document.getElementById("whatsappMessage").value.trim();
    if (message === "") {
      alert("Please type a message before sending.");
      return;
    }

    // ✅ Put your WhatsApp number here (with country code, no + sign, no spaces)
    let phoneNumber = "+923123912686";  
    
    let url = "https://wa.me/" + phoneNumber + "?text=" + encodeURIComponent(message);
    
    window.open(url, "_blank"); // open WhatsApp chat with message
  });
</script>


</div>

  </div>
</section>



  <style>
    /* Animate underline */
    .underline-animate {
      position: relative;
      display: inline-block;
    }

    .underline-animate::after {
      content: '';
      position: absolute;
      width: 0%;
      height: 2px;
      left: 0;
      bottom: -2px;
      background-color: #e8eaec;
      transition: width 0.3s ease-in-out;
    }

    .underline-animate:hover::after {
      width: 100%;
    }

    /* Hover shift and sibling movement */
    .hover-group:hover .hover-up {
      transform: translateY(-4px);
    }

    .hover-group:hover .hover-up:not(:hover) {
      transform: translateY(4px);
    }

    /* Icon hover effect */
    .icon-hover-group:hover .icon-up {
      transform: translateY(-5px);
    }

    .icon-hover-group:hover .icon-up:not(:hover) {
      transform: translateY(4px);
    }

    .hover-up,
    .icon-up {
      transition: transform 0.3s ease;
    }
  </style>
  <!-- Student Reviews Section -->
  <!-- Student Reviews Section -->
<section class="py-16 px-4 sm:px-6 lg:px-12 bg-gray-50 relative overflow-hidden">

  <style>
    @keyframes scroll-left {
      0% { transform: translateX(0); }
      100% { transform: translateX(-50%); }
    }

    @keyframes scroll-right {
      0% { transform: translateX(-50%); }
      100% { transform: translateX(0); }
    }

    .scroll-left, .scroll-right {
      display: flex;
      width: max-content;
      animation: 22s linear infinite;
      gap: 2rem; 
      min-height: 160px; /* ensures cards stay inside section */
      align-items: flex-start; /* align cards to top */
    }

    .scroll-left { animation-name: scroll-left; }
    .scroll-right { animation-name: scroll-right; }

    .scroll-left:hover,
    .scroll-right:hover {
      animation-play-state: paused;
    }

    .shadow-card {
      box-shadow: 0 8px 18px rgba(0,0,0,0.08), 
                  0 16px 30px rgba(0,0,0,0.12);
    }

    .review-text {
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 260px;
    }
  </style>

  <!-- Heading -->
  <div class="text-center mb-12 max-w-3xl mx-auto">
    <h2 class="text-3xl sm:text-4xl font-bold text-gray-800 leading-snug">
      What Our Students Say
    </h2>
    <p class="text-gray-600 mt-2 text-base sm:text-lg">
      Thousands of students trust 
      <span class="text-red-500 font-semibold">our platform</span> for MDCAT prep. Here's what they say.
    </p>
  </div>

  <!-- Review Rows -->
  <div class="space-y-5">

    <!-- Row 1: Left Scroll -->
    <div class="overflow-hidden relative w-full pb-12">
      <div class="scroll-left">
        <?php
          $reviews = [];
          if ($pdo) {
            try {
              $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 8");
              $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {}
          }

          for ($i=0; $i<2; $i++):
            foreach ($reviews as $review): ?>
              <div class="relative flex items-start gap-6">
                
                <!-- User Image -->
                <div class="flex-shrink-0">
                  <img src="Admin/<?= !empty($review['image']) ? htmlspecialchars($review['image']) : 'reviews_images/default.jpeg' ?>"
                    class="w-16 h-16 rounded-full object-cover shadow-md border-4 border-white" 
                    alt="<?= htmlspecialchars($review['name']) ?>" />
                </div>

                <!-- Card -->
                <div class="rounded-[15px] shadow-card p-5 
                            w-[320px] min-h-[140px] flex justify-between items-start gap-4 
                            transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl cursor-pointer bg-white">
                  <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-base">
                      <?= htmlspecialchars($review['name']) ?>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1 font-medium leading-snug review-text">
                      <?= htmlspecialchars($review['review']) ?>
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-green-600 text-xl font-bold leading-tight">
                      <?= htmlspecialchars($review['number']) ?>
                    </p>
                    <p class="text-gray-500 text-xs font-medium tracking-wide">
                      <?= htmlspecialchars($review['field']) ?>
                    </p>
                  </div>
                </div>
              </div>
          <?php endforeach; endfor; ?>
      </div>
    </div>

    <!-- Row 2: Right Scroll -->
    <div class="overflow-hidden relative w-full pb-12">
      <div class="scroll-right">
        <?php
          $reviews2 = [];
          if ($pdo) {
            try {
              $stmt = $pdo->query("SELECT * FROM reviews ORDER BY id DESC LIMIT 8,8");
              $reviews2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {}
          }

          for ($i=0; $i<2; $i++):
            foreach ($reviews2 as $review): ?>
              <div class="relative flex items-start gap-6">
                
                <!-- User Image -->
                <div class="flex-shrink-0">
                  <img src="Admin/<?= !empty($review['image']) ? htmlspecialchars($review['image']) : 'reviews_images/default.jpeg' ?>"
                    class="w-16 h-16 rounded-full object-cover shadow-md border-4 border-white" 
                    alt="<?= htmlspecialchars($review['name']) ?>" />
                </div>

                <!-- Card -->
                <div class="rounded-[15px] shadow-card p-5 
                            w-[320px] min-h-[140px] flex justify-between items-start gap-4 
                            transition-all duration-300 transform hover:-translate-y-2 hover:shadow-2xl cursor-pointer bg-white">
                  <div class="flex-1">
                    <h3 class="font-bold text-gray-800 text-base">
                      <?= htmlspecialchars($review['name']) ?>
                    </h3>
                    <p class="text-sm text-gray-600 mt-1 font-medium leading-snug review-text">
                      <?= htmlspecialchars($review['review']) ?>
                    </p>
                  </div>
                  <div class="text-right">
                    <p class="text-green-600 text-xl font-bold leading-tight">
                      <?= htmlspecialchars($review['number']) ?>
                    </p>
                    <p class="text-gray-500 text-xs font-medium tracking-wide">
                      <?= htmlspecialchars($review['field']) ?>
                    </p>
                  </div>
                </div>
              </div>
          <?php endforeach; endfor; ?>
      </div>
    </div>

  </div>
</section>

 <!-- Footer -->
  <footer class="   bg-[#f9fafb] border-t border-gray-200 text-center sm:text-left"
    style="background: linear-gradient(135deg, #073271 ); color: white;">
    <div class=" mx-auto px-6 py-12 sm:py-14 lg:py-16">
      <!-- Top Section -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        <!-- Brand Description -->
        <div class="wow animate__animated animate__fadeInUp" data-wow-delay=".2s">
          <h3 class="text-2xl font-bold text-[#094297] mb-4 flex items-center gap-2">
         <img src="assets/Images/logo 4.png"
     alt="Maxify Logo"
     class="block w-[120px] h-[100px] object-contain mx-auto lg:mx-0">


          </h3>
          <p class="text-base leading-relaxed max-w-sm text-white">
            Maxify is your complete solution for MDCAT and other medical entry test preparation. Access over 130,000
            questions with detailed explanations to master every concept.
          </p>
        </div>

        <!-- Quick Links -->
        <div class="hover-group wow animate__animated animate__fadeInUp" data-wow-delay=".4s">
          <h4 class="text-lg font-semibold  mb-4 text-white">Quick Links</h4>
          <ul class="space-y-2  text-white">
            <li class="hover-up"><a href="#" class="underline-animate transition">Home</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">About Us</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">Features</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">Contact</a></li>
          </ul>
        </div>

        <!-- Support -->
        <div class="hover-group wow animate__animated animate__fadeInUp" data-wow-delay=".6s">
          <h4 class="text-lg font-semibold  mb-4 text-white">Support</h4>
          <ul class="space-y-2 text-white">
            <li class="hover-up"><a href="#" class="underline-animate transition">Help Center</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">Terms of Service</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">Privacy Policy</a></li>
            <li class="hover-up"><a href="#" class="underline-animate transition">Report Issue</a></li>
          </ul>
        </div>

        <!-- Social Links -->
        <div class="icon-hover-group wow animate__animated animate__fadeInUp" data-wow-delay=".8s">
          <h4 class="text-lg font-semibold  mb-4 text-white">Follow Us</h4>
          <div class="flex gap-4 justify-center sm:justify-start  text-xl text-white">
            <a href="https://www.facebook.com/share/195irFADCL/" class="icon-up hover:text-white-800 transition"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="icon-up hover:text-white-800 transition"><i class="fab fa-twitter"></i></a>
            <a href="#" class="icon-up hover:text-white-800 transition"><i class="fab fa-instagram"></i></a>
            <a href="#" class="icon-up hover:text-white-800 transition"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>

      </div>
    </div>

<div class="text-white mt-10 border-top border-gray-200 wow animate__animated animate__fadeIn py-3 px-4"
     data-wow-delay="1s"
     style="display:flex; justify-content:center; align-items:center; gap:15px; flex-wrap:wrap; white-space:nowrap;">

  <!-- Left Side: Copyright -->
  <div style="font-size:15px; font-weight:500; margin-right:auto;">
    &copy; 2025 Maxxify. All rights reserved.
  </div>

  <!-- Center + Right Side Together -->
  <div style="display:flex; align-items:center; gap:12px; flex-wrap:nowrap;">
    <span style="font-weight:600; font-size:16px; letter-spacing:0.5px;">Powered by</span>
    <i class="fas fa-bolt text-warning" style="font-size:18px;"></i>
    <a href="https://hiskytechs.com/" target="_blank">
      <img src="assets/Images/Untitled-1.png" alt="Logo" 
           style="width:150px; height:auto; object-fit:contain; cursor:pointer;">
    </a>

    <!-- Visit Us (Now beside logo) -->
    <a href="https://hiskytechs.com/" target="_blank" 
       style="color:#ffcc00; font-weight:600; font-size:15px; text-decoration:none; margin-left:10px;">
       Visit Us →
    </a>
  </div>

</div>




  </footer>

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
  <!-- Slick Carousel JS -->
  <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
  <script src="assets/js/index.js"></script>
  <script src="assets/js/loader.js"></script>
  <script src="assets/js/mouse.js"></script>
  <script src="assets/js/wow.min.js"></script>

  <?php if (isset($_SESSION['subscription_success'])): ?>
    <div id="subscription-toast"
      style="position:fixed;top:30px;right:30px;z-index:9999;background:#673AB7;color:#fff;padding:16px 32px;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,0.15);font-size:18px;width:320px;text-align:center;">
      Subscription requested submitted successfully
    </div>
    <script>
      setTimeout(function() {
        var toast = document.getElementById('subscription-toast');
        if (toast) toast.style.display = 'none';
      }, 3000);
    </script>
    <?php unset($_SESSION['subscription_success']); ?>
  <?php endif; ?>
  <?php
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $isLoggedIn = isset($_SESSION['user_id']);
  ?>
  <script>
    function checkLoginAndRedirect(page) {
      <?php if ($isLoggedIn): ?>
        window.location.href = page;
      <?php else: ?>
        alert('Please login first to access this feature!');
      <?php endif; ?>
    }
  </script>




</body>

</html>