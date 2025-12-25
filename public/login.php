<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . "/../app/Models/User.php";

$error = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $email = $_POST['email'];
    $password = $_POST['password'];
    $user = new User();
    $IsLog = $user->login($email , $password);
    if($IsLog){
      $_SESSION['user_id'] = $IsLog['id'];
      $_SESSION['full_name'] = $IsLog['full_name'];
      $_SESSION['email'] = $IsLog['email'];
      header('Location: dashboard.php');
      exit;
    }else{
      $error = "Email ou mot  de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html class="dark" lang="fr">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title>Smart Wallet — Connexion</title>

  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet"/>

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#3B82F6",
            indigoGlow: "#6366F1",
            bgDark: "#0B1220",
            panel: "rgba(255,255,255,.06)",
            stroke: "rgba(255,255,255,.12)",
          },
          fontFamily: { display: ["Manrope", "sans-serif"] },
          boxShadow: {
            soft: "0 20px 60px rgba(0,0,0,.45)",
            glow: "0 0 0 1px rgba(255,255,255,.10), 0 20px 80px rgba(59,130,246,.18)"
          }
        }
      }
    }
  </script>

  <style>
    ::-webkit-scrollbar{width:8px}
    ::-webkit-scrollbar-track{background:#0B1220}
    ::-webkit-scrollbar-thumb{background:#2b3342;border-radius:4px}
    ::-webkit-scrollbar-thumb:hover{background:#3a4558}
  </style>
</head>

<body class="bg-[#0B1220] text-white font-display antialiased min-h-screen">
  <!-- Background accents -->
  <div class="pointer-events-none fixed inset-0">
    <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-indigoGlow/20 blur-3xl"></div>
    <div class="absolute top-12 right-0 h-[28rem] w-[28rem] rounded-full bg-primary/15 blur-3xl"></div>
    <div class="absolute bottom-0 left-1/3 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl"></div>
    <div class="absolute inset-0 opacity-[0.06]"
         style="background-image:linear-gradient(rgba(255,255,255,.7) 1px, transparent 1px),linear-gradient(90deg, rgba(255,255,255,.7) 1px, transparent 1px);background-size:56px 56px;">
    </div>
  </div>

  <div class="relative flex min-h-screen">
    <!-- LEFT: Form -->
    <div class="flex w-full lg:w-[44%] items-center justify-center px-4 py-12 sm:px-6 lg:px-16">
      <div class="w-full max-w-md">
        <!-- Brand -->
        <div class="flex items-center gap-3">
          <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-indigoGlow shadow-glow flex items-center justify-center">
            <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none">
              <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" opacity=".9"/>
              <path d="M7 10h10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
              <path d="M7 14h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <div>
            <div class="text-lg font-extrabold tracking-tight">Smart Wallet</div>
            <div class="text-xs text-white/60">Secure finance tracker • OOPB</div>
          </div>
        </div>

        <!-- Heading -->
        <div class="mt-10">
          <h1 class="text-3xl sm:text-4xl font-black tracking-tight">Connexion</h1>
          <p class="mt-2 text-white/60 leading-relaxed">
            Accède à ton dashboard pour gérer catégories, revenus et dépenses.
          </p>
        </div>

        <!-- Error -->
        <?php if ($error): ?>
          <div class="mt-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-100 flex gap-3 items-start">
            <span class="material-symbols-outlined text-red-200 text-[20px]">error</span>
            <div><?php echo htmlspecialchars($error); ?></div>
          </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="mt-6 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-xl shadow-soft p-6">
          <form method="POST" autocomplete="off" class="space-y-5">
            <!-- Email -->
            <div>
              <label class="block text-sm font-semibold text-white/80 mb-2">Email</label>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-white/50">person</span>
                <input
                  name="email"
                  type="email"
                  required
                  value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                  placeholder="name@example.com"
                  class="w-full rounded-xl bg-white/5 border border-white/10 pl-11 pr-4 py-3 text-white placeholder:text-white/40
                         focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/40 transition"
                />
              </div>
            </div>

            <!-- Password -->
            <div>
              <div class="flex items-center justify-between mb-2">
                <label class="block text-sm font-semibold text-white/80">Mot de passe</label>
                <a href="#" class="text-sm font-semibold text-primary hover:text-primary/80 transition">Mot de passe oublié ?</a>
              </div>
              <div class="relative">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-white/50">lock</span>
                <input
                  id="password"
                  name="password"
                  type="password"
                  required
                  placeholder="••••••••"
                  class="w-full rounded-xl bg-white/5 border border-white/10 pl-11 pr-11 py-3 text-white placeholder:text-white/40
                         focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary/40 transition"
                />
                <button
                  type="button"
                  id="togglePassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-white/50 hover:text-white/80 transition"
                  aria-label="Toggle password visibility"
                >
                  <span class="material-symbols-outlined">visibility</span>
                </button>
              </div>
            </div>

            <!-- Button -->
            <button
              type="submit"
              class="w-full rounded-xl py-3 font-bold tracking-tight text-white
                     bg-gradient-to-r from-primary to-indigoGlow
                     hover:from-primary/90 hover:to-indigoGlow/90
                     shadow-glow transition"
            >
              Se connecter
            </button>

            <!-- Footer -->
            <p class="text-center text-sm text-white/60">
              Pas de compte ?
              <a class="font-bold text-primary hover:text-primary/80 transition" href="register.php">Créer un compte</a>
            </p>
          </form>
        </div>

        <p class="mt-6 text-xs text-white/45">
          © <?php echo date("Y"); ?> Smart Wallet. All rights reserved.
        </p>
      </div>
    </div>

    <!-- RIGHT: Image / promo -->
    <div class="hidden lg:flex flex-1 relative">
      <div class="absolute inset-0 bg-cover bg-center"
           style='background-image:url("https://lh3.googleusercontent.com/aida-public/AB6AXuBdPshj7M67dLxf0jzcQOOcyrX6pOzgkO-MnBP5LsKclOZIP0p49L1CXEoy3G0Kj0qczMHsJvcULmC35oRqVq1iHTcEMdPYkqpEJUzuO1YvDX6rA9fXRMtLTOxXmEbTdDO44MWO3Dh-0Wqey6ckXjE8XaRtHb3Hx2aowL79GZ8FeShSjheXNk1E9c2AVdeX7rvf38gs2pTJSuFKJsbYiRnHPGpVZWPcMse_GId33T1fUa2H5NWNc_EkJpL3LDQ_rJCIk99JcErjoUj4");'>
      </div>

      <div class="absolute inset-0 bg-gradient-to-t from-[#0B1220] via-[#0B1220]/55 to-transparent"></div>
      <div class="absolute inset-0 bg-gradient-to-r from-[#0B1220]/70 via-transparent to-transparent"></div>

      <div class="relative w-full flex items-end p-16">
        <div class="max-w-xl">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 backdrop-blur">
            <span class="material-symbols-outlined text-primary text-[18px]">trending_up</span>
            <span class="text-xs font-bold text-white/80 uppercase tracking-wider">Financial Overview</span>
          </div>

          <h2 class="mt-5 text-5xl font-black leading-tight tracking-tight">
            Clean insights for your <span class="text-primary">budget</span>.
          </h2>
          <p class="mt-4 text-lg text-white/70 leading-relaxed">
            Visualise tes totaux, ton solde, et tes catégories — avec une interface moderne et sécurisée.
          </p>

          <div class="mt-8 flex gap-4">
            <div class="w-44 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl p-4">
              <div class="text-xs text-white/60">Balance</div>
              <div class="mt-1 text-2xl font-extrabold">$24,592.00</div>
              <div class="mt-2 text-xs text-emerald-300 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">arrow_upward</span>
                +12.5% this month
              </div>
            </div>

            <div class="w-44 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl p-4">
              <div class="text-xs text-white/60">Saving</div>
              <div class="mt-1 text-2xl font-extrabold">$1,250.00</div>
              <div class="mt-2 text-xs text-primary/90 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">savings</span>
                On track
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script>
    const toggleBtn = document.getElementById("togglePassword");
    const passInput = document.getElementById("password");

    if (toggleBtn && passInput) {
      toggleBtn.addEventListener("click", () => {
        const isPass = passInput.type === "password";
        passInput.type = isPass ? "text" : "password";
        toggleBtn.innerHTML = `<span class="material-symbols-outlined">${isPass ? "visibility_off" : "visibility"}</span>`;
      });
    }
  </script>
</body>
</html>
