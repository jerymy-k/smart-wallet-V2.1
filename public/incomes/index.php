<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../app/Core/Database.php';
require_once __DIR__ . '/../../app/Models/Category.php';
require_once __DIR__ . '/../../app/Models/Income.php';

session_start();
if (!isset($_SESSION['user_id'])) {
  header('Location: /smart-wallet-oop/public/login.php');
  exit;
}

$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];
$email = $_SESSION['email'];

$categorieObj = new Category();
$incomesObj = new Income($user_id);

$incomesCategories = $categorieObj->getAllCategoriesByUser('income', $user_id);

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['income_amount'])) {
  $incomeAmount = $_POST['income_amount'];
  $incomeCategorieId = $_POST['income_categorie'];
  $incomeDate = $_POST['income_date'];
  $incomeDescription = $_POST['income_description'];

  $isSucces = $incomesObj->createOperation($user_id, $incomeCategorieId, $incomeAmount, $incomeDescription, $incomeDate, 'incomes');
  if (!$isSucces) {
    $error = "The operation was not successful.";
  } else {
    $success = "The operation was successful.";
  }
  unset($_POST);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['income_amount_edit'])) {
  $incomeIdEdit = $_POST['income_id_edit'];
  $incomeAmountEdit = $_POST['income_amount_edit'];
  $incomeCategorieEdit = $_POST['income_category_edit'];
  $incomeDateEdit = $_POST['income_date_edit'];
  $incomeDescEdit = $_POST['income_description_edit'];
  $incomeNoteEdit = $_POST['income_note_edit'];

  $updated = $incomesObj->editOperation($incomeIdEdit, $user_id, $incomeCategorieEdit, $incomeAmountEdit, $incomeDescEdit, $incomeDateEdit, 'incomes');
  if (!$updated) {
    $error = "The operation was not successful.";
  } else {
    $success = "The operation was successful.";
  }

  unset($_POST);
}

if (isset($_GET['deletId'])) {
  $incomeId = (int) $_GET['deletId'];
  $deleted = $incomesObj->deleteOperation($incomeId, $user_id, 'incomes');
  if (!$deleted) {
    $error = "The operation was not successful.";
  } else {
    $success = "The operation was successful.";
    header('Location: /smart-wallet-oop/public/incomes/index.php');
    exit;
  }
}

$allIncomes = $incomesObj->getAllOperationPerUser($user_id, 'incomes');
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>Dashboard | Smart Wallet</title>

  <link href="https://fonts.googleapis.com" rel="preconnect" />
  <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <script>
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            primary: "#135bec",
            "background-light": "#f6f6f8",
            "background-dark": "#101622",
            "surface-dark": "#1c1f27",
            "border-dark": "#282e39",
          },
          fontFamily: { display: ["Manrope", "sans-serif"] },
          borderRadius: { DEFAULT: "0.25rem", lg: "0.5rem", xl: "0.75rem", "2xl": "1rem", full: "9999px" },
        },
      },
    }
  </script>

  <style>
    ::-webkit-scrollbar {
      width: 6px;
      height: 6px;
    }

    ::-webkit-scrollbar-track {
      background: transparent;
    }

    ::-webkit-scrollbar-thumb {
      background: #282e39;
      border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
      background: #3b4354;
    }

    .chart-bar {
      transition: height 0.3s ease, background-color 0.2s ease;
    }

    .chart-bar:hover {
      opacity: 0.8;
    }
  </style>
</head>

<body
  class="bg-background-light dark:bg-background-dark text-[#111318] dark:text-white font-display overflow-hidden antialiased h-screen flex">

  <aside
    class="w-64 bg-white dark:bg-surface-dark border-r border-gray-200 dark:border-border-dark hidden lg:flex flex-col flex-shrink-0 z-20">
    <div class="h-20 flex items-center px-8 border-b border-gray-100 dark:border-border-dark">
      <div class="flex items-center gap-3">
        <div class="size-8 text-primary bg-primary/20 rounded-lg flex items-center justify-center p-1.5">
          <svg class="w-full h-full text-primary" fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
            <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
          </svg>
        </div>
        <h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">Smart Wallet</h2>
      </div>
    </div>

    <nav class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-2">
      <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu</p>
      <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
        href="/smart-wallet-oop/public/dashboard.php">
        <span class="material-symbols-outlined">dashboard</span>
        <span class="font-semibold text-sm">Dashboard</span>
      </a>
      <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
        href="/smart-wallet-oop/public/categories/index.php">
        <span class="material-symbols-outlined group-hover:text-primary transition-colors">category</span>
        <span
          class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Categories</span>
      </a>
      <a class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl transition-colors"
        href="/smart-wallet-oop/public/incomes/index.php">
        <span class="material-symbols-outlined group-hover:text-primary transition-colors">trending_up</span>
        <span
          class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Incomes</span>
      </a>

      <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
        href="/smart-wallet-oop/public/expenses/index.php">
        <span class="material-symbols-outlined group-hover:text-primary transition-colors">trending_down</span>
        <span
          class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Expenses</span>
      </a>
    </nav>

    <div class="p-4 border-t border-gray-100 dark:border-border-dark">
      <div class="flex items-center gap-3 px-2 py-2">
        <div
          class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary to-purple-500 flex items-center justify-center text-white font-bold shadow-lg shadow-primary/20">
          <?= $fullName[0] ?>
        </div>
        <div class="flex-1 min-w-0">
          <p class="text-sm font-bold text-[#111318] dark:text-white truncate"><?= $fullName ?></p>
          <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= $email ?></p>
        </div>
        <a href="/smart-wallet-oop/public/logout.php" class="text-gray-400 hover:text-red-500 transition-colors">
          <span class="material-symbols-outlined text-[20px]">logout</span>
        </a>
      </div>
    </div>
  </aside>


  <main class="flex-1 flex flex-col min-w-0 overflow-hidden bg-background-light dark:bg-background-dark relative">

    <header
      class="h-20 bg-white/80 dark:bg-background-dark/80 backdrop-blur-md border-b border-gray-200 dark:border-border-dark flex items-center justify-between px-6 z-10 sticky top-0">
      <div class="flex items-center gap-4">
        <button class="lg:hidden text-gray-500 dark:text-white p-2">
          <span class="material-symbols-outlined">menu</span>
        </button>
        <div>
          <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Incomes</h1>
          <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Manage your income operations</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden md:flex relative">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
          </div>
          <input
            class="block w-72 pl-10 pr-3 py-2 border border-gray-200 dark:border-border-dark rounded-lg leading-5 bg-gray-50 dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition-all"
            placeholder="Search income..." type="text" />
        </div>

        <button id="openIncomeModalBtn"
          class="flex items-center gap-2 bg-primary hover:bg-primary/90 transition px-4 py-2 rounded-lg font-semibold text-white shadow-lg shadow-primary/20">
          <span class="material-symbols-outlined text-[20px]">add</span>
          Add Income
        </button>
      </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
      <div class="max-w-7xl mx-auto space-y-6">

        <?php if (!empty($error)): ?>
          <div class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-2xl px-5 py-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-red-400">error</span>
            <div class="flex-1">
              <p class="font-semibold">Error</p>
              <p class="text-sm"><?= htmlspecialchars($error) ?></p>
            </div>
          </div>
        <?php endif; ?>
        <?php $error = ""; ?>

        <?php if (!empty($success)): ?>
          <div
            class="bg-green-500/10 border border-green-500/30 text-green-400 rounded-2xl px-5 py-4 flex items-start gap-3">
            <span class="material-symbols-outlined text-green-400">check_circle</span>
            <div class="flex-1">
              <p class="font-semibold">Success</p>
              <p class="text-sm"><?= htmlspecialchars($success) ?></p>
            </div>
          </div>
        <?php endif; ?>
        <?php $success = ""; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div
            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Incomes</p>
            <div class="flex items-end justify-between">
              <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">
                <?= is_array($allIncomes) ? count($allIncomes) : 0 ?>
              </h3>
              <span class="text-xs text-gray-400">All time</span>
            </div>
          </div>

          <div
            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">This Month</p>
            <div class="flex items-end justify-between">
              <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">-</h3>
              <span class="text-xs text-green-400 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">trending_up</span> healthy
              </span>
            </div>
          </div>

          <div
            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Top Category</p>
            <div class="flex items-end justify-between">
              <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">-</h3>
              <span class="text-xs text-gray-400">sample</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
          <div class="xl:col-span-1 flex flex-col gap-6">
            <div
              class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Incomes by Category</h3>
                <span class="material-symbols-outlined text-gray-400">more_horiz</span>
              </div>

              <div class="flex items-center gap-5">
                <div class="relative h-28 w-28 rounded-full"
                  style="background: conic-gradient(#22c55e 0 48%, #135bec 48% 78%, #f59e0b 78% 100%);">
                  <div class="absolute inset-3 bg-white dark:bg-surface-dark rounded-full"></div>
                  <div class="absolute inset-0 grid place-items-center">
                    <div class="text-center">
                      <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                      <p class="text-xl font-bold"><?= is_array($allIncomes) ? count($allIncomes) : 0 ?></p>
                    </div>
                  </div>
                </div>

                <div class="space-y-3 text-sm">
                  <div class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                      <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                      <span class="text-gray-600 dark:text-gray-300">Salary</span>
                    </div>
                    <span class="font-semibold text-[#111318] dark:text-white">-</span>
                  </div>
                  <div class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                      <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                      <span class="text-gray-600 dark:text-gray-300">Business</span>
                    </div>
                    <span class="font-semibold text-[#111318] dark:text-white">-</span>
                  </div>
                  <div class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-2">
                      <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                      <span class="text-gray-600 dark:text-gray-300">Other</span>
                    </div>
                    <span class="font-semibold text-[#111318] dark:text-white">-</span>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
              <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Top Incomes</h3>
                <span class="text-xs text-gray-400">sample</span>
              </div>

              <div class="space-y-3">
                <div>
                  <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500 dark:text-gray-400">Salary</span>
                    <span class="font-semibold">50%</span>
                  </div>
                  <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                    <div class="h-2 rounded-full bg-green-500" style="width:50%"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500 dark:text-gray-400">Business</span>
                    <span class="font-semibold">33%</span>
                  </div>
                  <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                    <div class="h-2 rounded-full bg-primary" style="width:33%"></div>
                  </div>
                </div>

                <div>
                  <div class="flex justify-between text-xs mb-1">
                    <span class="text-gray-500 dark:text-gray-400">Other</span>
                    <span class="font-semibold">17%</span>
                  </div>
                  <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                    <div class="h-2 rounded-full bg-amber-500" style="width:17%"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div
            class="xl:col-span-2 bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
              <h3 class="text-lg font-bold text-[#111318] dark:text-white">All Incomes</h3>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left">
                <thead class="bg-white/5 text-sm uppercase text-gray-400">
                  <tr>
                    <th class="px-6 py-4">Title</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Date</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                  </tr>
                </thead>

                <tbody>
                  <?php if (!empty($allIncomes) && is_array($allIncomes)): ?>
                    <?php foreach ($allIncomes as $ai): ?>
                      <tr class="border-t border-border-dark/60 hover:bg-white/5 transition">
                        <td class="px-6 py-4 font-medium"><?= htmlspecialchars($ai['description']) ?></td>
                        <td class="px-6 py-4">
                          <span
                            class="px-3 py-1 text-xs rounded-full bg-green-500/20 text-green-400"><?= htmlspecialchars($ai['category_name']) ?></span>
                        </td>
                        <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($ai['amount']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-400"><?= htmlspecialchars($ai['operation_date']) ?></td>
                        <td class="px-6 py-4 text-right">
                          <div class="inline-flex gap-2">
                            <button data-id="<?= $ai['id'] ?>"
                              data-desc="<?= htmlspecialchars($ai['description'], ENT_QUOTES) ?>"
                              data-amount="<?= htmlspecialchars($ai['amount'], ENT_QUOTES) ?>"
                              data-date="<?= htmlspecialchars($ai['operation_date'], ENT_QUOTES) ?>"
                              data-cat="<?= htmlspecialchars($ai['category_id'] ?? '', ENT_QUOTES) ?>"
                              class="openIncomeModalBtnEdit p-2 rounded-lg hover:bg-white/10">
                              <span class="material-symbols-outlined text-blue-400">edit</span>
                            </button>

                            <a href="/smart-wallet-oop/public/incomes/index.php?deletId=<?= $ai['id'] ?>"
                              class="p-2 rounded-lg hover:bg-white/10"
                              onclick="return confirm('Are you sure you want to delete this income?');">
                              <span class="material-symbols-outlined text-red-400">delete</span>
                            </a>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="5" class="px-6 py-6 text-center text-gray-400">No incomes found</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>

      </div>
    </div>
  </main>

  <div id="incomeModal" class="fixed inset-0 z-50 hidden">
    <div id="incomeModalOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative min-h-full flex items-center justify-center p-4">
      <div
        class="w-full max-w-lg bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-primary/15 text-primary flex items-center justify-center">
              <span class="material-symbols-outlined">add</span>
            </div>
            <div>
              <h3 class="text-lg font-bold text-[#111318] dark:text-white">Add Income</h3>
              <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Create a new income operation</p>
            </div>
          </div>

          <button id="closeIncomeModalBtn" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
            <span class="material-symbols-outlined text-gray-500 dark:text-gray-300">close</span>
          </button>
        </div>

        <form class="p-6 space-y-4" method="POST">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Amount</label>
              <input type="number" step="0.01" placeholder="0.00" required name="income_amount"
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Date</label>
              <input type="date" required name="income_date"
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Category</label>
              <select name="income_categorie" required
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                <option value="" selected disabled>Select a Categorie</option>
                <?php foreach ($incomesCategories as $ic): ?>
                  <option value="<?= $ic['id'] ?>"><?= $ic['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>

          <div>
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Note</label>
            <textarea rows="3" placeholder="Optional note..." name="income_description"
              class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" id="incomeCancelBtn"
              class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-white/5 transition font-semibold text-sm">
              Cancel
            </button>
            <button type="submit"
              class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 transition font-semibold text-sm text-white shadow-lg shadow-primary/20">
              Save Income
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div id="incomeModalEdit" class="fixed inset-0 z-50 hidden">
    <div id="incomeModalOverlayEdit" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

    <div class="relative min-h-full flex items-center justify-center p-4">
      <div
        class="w-full max-w-lg bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-5 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-primary/15 text-primary flex items-center justify-center">
              <span class="material-symbols-outlined">edit</span>
            </div>
            <div>
              <h3 class="text-lg font-bold text-[#111318] dark:text-white">Edit Income</h3>
              <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Update your income operation</p>
            </div>
          </div>

          <button id="closeIncomeModalBtnEdit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
            <span class="material-symbols-outlined text-gray-500 dark:text-gray-300">close</span>
          </button>
        </div>

        <form class="p-6 space-y-4" method="POST">
          <input type="text" name="income_id_edit" id="income_edit_id" hidden>

          <div>
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Title</label>
            <input name="income_description_edit" id="income_edit_title" type="text" placeholder="e.g. Salary..."
              required
              class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Amount</label>
              <input name="income_amount_edit" id="income_edit_amount" type="number" step="0.01" placeholder="0.00"
                required
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
            </div>
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Date</label>
              <input name="income_date_edit" id="income_edit_date" type="date" required
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Category</label>
              <select name="income_category_edit" id="income_edit_category"
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                <option value="" disabled>Select a Categorie</option>
                <?php foreach ($incomesCategories as $ic): ?>
                  <option value="<?= $ic['id'] ?>"><?= $ic['name'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div>
              <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Payment method</label>
              <select id="income_edit_method"
                class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                <option>Cash</option>
                <option>Card</option>
                <option>Bank Transfer</option>
              </select>
            </div>
          </div>

          <div>
            <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Note</label>
            <textarea name="income_note_edit" id="income_edit_note" rows="3" placeholder="Optional note..."
              class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary"></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-2">
            <button type="button" id="incomeCancelBtnEdit"
              class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-white/5 transition font-semibold text-sm">
              Cancel
            </button>
            <button type="submit"
              class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 transition font-semibold text-sm text-white shadow-lg shadow-primary/20">
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    const incomeModal = document.getElementById('incomeModal');
    const openIncomeModalBtn = document.getElementById('openIncomeModalBtn');
    const closeIncomeModalBtn = document.getElementById('closeIncomeModalBtn');
    const incomeCancelBtn = document.getElementById('incomeCancelBtn');
    const incomeModalOverlay = document.getElementById('incomeModalOverlay');

    function openIncomeModal() {
      incomeModal.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    }
    function closeIncomeModal() {
      incomeModal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }

    openIncomeModalBtn.addEventListener('click', openIncomeModal);
    closeIncomeModalBtn.addEventListener('click', closeIncomeModal);
    incomeCancelBtn.addEventListener('click', closeIncomeModal);
    incomeModalOverlay.addEventListener('click', closeIncomeModal);

    const incomeModalEdit = document.getElementById('incomeModalEdit');
    const closeIncomeModalBtnEdit = document.getElementById('closeIncomeModalBtnEdit');
    const incomeCancelBtnEdit = document.getElementById('incomeCancelBtnEdit');
    const incomeModalOverlayEdit = document.getElementById('incomeModalOverlayEdit');

    const income_edit_id = document.getElementById('income_edit_id');
    const income_edit_title = document.getElementById('income_edit_title');
    const income_edit_amount = document.getElementById('income_edit_amount');
    const income_edit_date = document.getElementById('income_edit_date');
    const income_edit_category = document.getElementById('income_edit_category');
    const income_edit_note = document.getElementById('income_edit_note');

    function openIncomeEditModal() {
      incomeModalEdit.classList.remove('hidden');
      document.body.classList.add('overflow-hidden');
    }
    function closeIncomeEditModal() {
      incomeModalEdit.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
    }

    document.querySelectorAll('.openIncomeModalBtnEdit').forEach(btn => {
      btn.addEventListener('click', () => {
        openIncomeEditModal();
        income_edit_id.value = btn.dataset.id || '';
        income_edit_title.value = btn.dataset.desc || '';
        income_edit_amount.value = btn.dataset.amount || '';
        income_edit_date.value = btn.dataset.date || '';
        if (btn.dataset.cat) income_edit_category.value = btn.dataset.cat;
        income_edit_note.value = btn.dataset.note || '';
      });
    });

    closeIncomeModalBtnEdit.addEventListener('click', closeIncomeEditModal);
    incomeCancelBtnEdit.addEventListener('click', closeIncomeEditModal);
    incomeModalOverlayEdit.addEventListener('click', closeIncomeEditModal);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        closeIncomeModal();
        closeIncomeEditModal();
      }
    });
  </script>
</body>

</html>