<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Maxxify Academy Login</title>

    <link href="../../dist/output.css" rel="stylesheet">

    <link href="../../dist/input.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700&display=swap" rel="stylesheet">


</head>

<body class="min-h-screen flex items-center justify-center bg-white">


    <div class="flex flex-col lg:flex-row w-full max-w-6xl shadow-lg rounded-lg overflow-hidden min-h-[500px]">

        <!-- Left Section (Illustration + Text) -->
        <div class="lg:w-1/2 w-full bg-[#673AB7] text-white p-10 flex flex-col justify-center items-center">
            <h2 class="text-3xl lg:text-4xl font-bold mb-4 text-center">
                Admin
            </h2>
        </div>

        <!-- Right Section (Form) -->
        <!-- Right Side Login Form -->
        <div class="lg:w-1/2 w-full bg-white px-6 py-10 flex flex-col justify-center items-center">

            <h2 class="text-[22px] leading-[100%] font-[700] font-[Manrope] text-[#2E1A51] text-center mb-6">
                Sign In to Admin
            </h2>

            <form action="login.php" method="POST" class="space-y-5 w-full max-w-[403px]">


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
                </div>


                <!-- Sign In Button -->
                <div class="pt-3 flex justify-center">
                    <button type="submit" class="w-[170px] h-[48px] bg-[#673AB7] text-white font-[Manrope] text-[16px] font-bold leading-[100%] 
           rounded-[14px] shadow-[0px_6px_12px_0px_#673AB7] 
           flex items-center justify-center gap-2 hover:bg-[#5e35b1] transition">
                        <span>Sign In</span>
                    </button>
                </div>

            </form>
        </div>


    </div>

    <!-- FontAwesome for eye icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>

</html>