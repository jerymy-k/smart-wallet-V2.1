<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../app/Models/User.php";

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $fullName = trim($_POST['full_name']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm = $_POST['confirm_password'];
  if(strlen($fullName) < 4){
    $error = "Your full name is invalid please insert more than 4 character";
  }
  if($password !== $confirm) {
    $error = "passwords are not match";
  }else{
    $user = new User($fullName , $email , $password);
    $IsRegister = $user->register();
    if(!$IsRegister){
      $error = "Your email isn't valid or there is another user with this email";
    }else{
      $success = "Your account has been successfully created";
    }
  }
  
}
?>
<!DOCTYPE html>
<html class="dark" lang="en">
<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Smart Wallet — Register</title>

  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "primary": "#135bec",
            "background-light": "#f6f6f8",
            "background-dark": "#101622",
          },
          fontFamily: { "display": ["Manrope", "sans-serif"] },
          borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
        },
      },
    }
  </script>

  <style>
    ::-webkit-scrollbar{width:8px}
    ::-webkit-scrollbar-track{background:#101622}
    ::-webkit-scrollbar-thumb{background:#282e39;border-radius:4px}
    ::-webkit-scrollbar-thumb:hover{background:#3b4354}
  </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white font-display overflow-x-hidden antialiased h-screen flex flex-col">
  <div class="flex min-h-full flex-1">

    <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-background-light dark:bg-background-dark w-full lg:w-[45%] border-r border-transparent dark:border-[#282e39]/50 overflow-y-auto">
      <div class="mx-auto w-full max-w-sm lg:w-96">

        <div class="flex items-center gap-3 mb-8">
          <div class="size-10 text-primary bg-primary/20 rounded-lg flex items-center justify-center p-2">
            <svg class="w-full h-full text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
              <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
            </svg>
          </div>
          <h2 class="text-2xl font-bold leading-tight tracking-[-0.015em]">Smart Wallet</h2>
        </div>

        <div class="mb-8">
          <h2 class="text-4xl font-black leading-tight tracking-[-0.033em] mb-3">Create Account</h2>
          <p class="text-[#637588] dark:text-[#9da6b9] text-base font-normal leading-normal">
            Join us to manage your finances intelligently.
          </p>
        </div>

        <?php if ($error): ?>
          <div class="mb-5 rounded-lg border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-200 flex gap-2">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span><?php echo htmlspecialchars($error); ?></span>
          </div>
        <?php endif; ?>

        <?php if ($success): ?>
          <div class="mb-5 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-200 flex gap-2">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span>
              <?php echo htmlspecialchars($success); ?>
              <a class="font-bold text-primary hover:text-primary/80 transition-colors" href="login.php">Log In</a>
            </span>
          </div>
        <?php endif; ?>

        <form method="POST" class="flex flex-col gap-5">
          <label class="flex flex-col w-full">
            <p class="text-[#111318] dark:text-white text-base font-medium leading-normal pb-2">Full Name</p>
            <div class="flex w-full items-stretch rounded-lg shadow-sm">
              <input
                name="full_name"
                class="form-input flex-1 w-full min-w-0 resize-none overflow-hidden rounded-l-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-[#3b4354] bg-white dark:bg-[#1c1f27] focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-[#9da6b9] p-[15px] border-r-0 text-base font-normal leading-normal transition-all"
                placeholder="John Doe"
                type="text"
                value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                required
              />
              <div class="text-gray-400 dark:text-[#9da6b9] flex border border-gray-300 dark:border-[#3b4354] bg-gray-50 dark:bg-[#1c1f27] items-center justify-center px-4 rounded-r-lg border-l-0">
                <span class="material-symbols-outlined">person</span>
              </div>
            </div>
          </label>

          <label class="flex flex-col w-full">
            <p class="text-[#111318] dark:text-white text-base font-medium leading-normal pb-2">Email Address</p>
            <div class="flex w-full items-stretch rounded-lg shadow-sm">
              <input
                name="email"
                class="form-input flex-1 w-full min-w-0 resize-none overflow-hidden rounded-l-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-[#3b4354] bg-white dark:bg-[#1c1f27] focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-[#9da6b9] p-[15px] border-r-0 text-base font-normal leading-normal transition-all"
                placeholder="name@example.com"
                type="email"
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                required
              />
              <div class="text-gray-400 dark:text-[#9da6b9] flex border border-gray-300 dark:border-[#3b4354] bg-gray-50 dark:bg-[#1c1f27] items-center justify-center px-4 rounded-r-lg border-l-0">
                <span class="material-symbols-outlined">mail</span>
              </div>
            </div>
          </label>

          <label class="flex flex-col w-full">
            <p class="text-[#111318] dark:text-white text-base font-medium leading-normal pb-2">Password</p>
            <div class="flex w-full items-stretch rounded-lg shadow-sm group">
              <input
                id="password"
                name="password"
                class="form-input flex-1 w-full min-w-0 resize-none overflow-hidden rounded-l-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-[#3b4354] bg-white dark:bg-[#1c1f27] focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-[#9da6b9] p-[15px] border-r-0 text-base font-normal leading-normal transition-all"
                placeholder="Create a password"
                type="password"
                required
                minlength="6"
              />
              <button type="button" data-toggle="password"
                class="text-gray-400 dark:text-[#9da6b9] flex border border-gray-300 dark:border-[#3b4354] bg-gray-50 dark:bg-[#1c1f27] items-center justify-center px-4 rounded-r-lg border-l-0 cursor-pointer hover:text-primary transition-colors">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>

          <label class="flex flex-col w-full">
            <p class="text-[#111318] dark:text-white text-base font-medium leading-normal pb-2">Confirm Password</p>
            <div class="flex w-full items-stretch rounded-lg shadow-sm group">
              <input
                id="confirm_password"
                name="confirm_password"
                class="form-input flex-1 w-full min-w-0 resize-none overflow-hidden rounded-l-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary/50 border border-gray-300 dark:border-[#3b4354] bg-white dark:bg-[#1c1f27] focus:border-primary h-14 placeholder:text-gray-400 dark:placeholder:text-[#9da6b9] p-[15px] border-r-0 text-base font-normal leading-normal transition-all"
                placeholder="Confirm your password"
                type="password"
                required
                minlength="6"
              />
              <button type="button" data-toggle="confirm_password"
                class="text-gray-400 dark:text-[#9da6b9] flex border border-gray-300 dark:border-[#3b4354] bg-gray-50 dark:bg-[#1c1f27] items-center justify-center px-4 rounded-r-lg border-l-0 cursor-pointer hover:text-primary transition-colors">
                <span class="material-symbols-outlined">visibility</span>
              </button>
            </div>
          </label>

          <button type="submit"
            class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-12 px-4 bg-primary hover:bg-primary/90 transition-colors text-white text-base font-bold leading-normal tracking-[0.015em] shadow-lg shadow-primary/20 mt-2">
            <span class="truncate">Sign Up</span>
          </button>

          <p class="text-center text-sm text-gray-500 dark:text-[#9da6b9] mt-2">
            Already have an account?
            <a class="font-bold text-primary hover:text-primary/80 transition-colors" href="login.php">Log In</a>
          </p>
        </form>

        <p class="text-center text-xs text-[#637588] dark:text-[#9da6b9] mt-6">
          Tip: choose a strong password (min 6 chars).
        </p>
      </div>
    </div>

    <div class="hidden lg:flex flex-1 relative w-0">
      <div class="absolute inset-0 h-full w-full bg-[#101622]"
        style='background-image:url("https://lh3.googleusercontent.com/aida-public/AB6AXuBdPshj7M67dLxf0jzcQOOcyrX6pOzgkO-MnBP5LsKclOZIP0p49L1CXEoy3G0Kj0qczMHsJvcULmC35oRqVq1iHTcEMdPYkqpEJUzuO1YvDX6rA9fXRMtLTOxXmEbTdDO44MWO3Dh-0Wqey6ckXjE8XaRtHb3Hx2aowL79GZ8FeShSjheXNk1E9c2AVdeX7rvf38gs2pTJSuFKJsbYiRnHPGpVZWPcMse_GId33T1fUa2H5NWNc_EkJpL3LDQ_rJCIk99JcErjoUj4");background-size:cover;background-position:center;'>
        <div class="absolute inset-0 bg-gradient-to-t from-background-dark via-background-dark/40 to-transparent opacity-90"></div>
        <div class="absolute bottom-0 left-0 right-0 p-16 flex flex-col justify-end h-full max-w-2xl">
          <div class="flex flex-col gap-4">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/20 border border-primary/30 w-fit backdrop-blur-sm">
              <span class="material-symbols-outlined text-primary text-sm">verified_user</span>
              <span class="text-xs font-bold text-primary uppercase tracking-wider">Secure by Design</span>
            </div>
            <h1 class="text-5xl font-bold text-white leading-tight tracking-tight">
              Create your space and track your <span class="text-primary">money</span>.
            </h1>
            <p class="text-lg text-gray-300 max-w-lg">
              Start with categories, add incomes and expenses, then view a clean dashboard.
            </p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    document.querySelectorAll("button[data-toggle]").forEach((btn) => {
      btn.addEventListener("click", () => {
        const id = btn.getAttribute("data-toggle");
        const input = document.getElementById(id);
        if (!input) return;

        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";
        btn.innerHTML = `<span class="material-symbols-outlined">${isPassword ? "visibility_off" : "visibility"}</span>`;
      });
    });
  </script>
</body>
</html>
