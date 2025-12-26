<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/Expense.php';
require_once __DIR__ . '/../app/Models/Income.php';

use app\Core\Database;

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /smart-wallet-oop/public/login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];
$email = $_SESSION['email'];

$db = Database::getInstance()->getConnection();
$expensesObj = new Expense($user_id);
$incomesObj = new Income($user_id);
$totalExpenses = $expensesObj->totalExpenses();
$totalIncome = $incomesObj->totalIncomes();
$balance = $totalIncome - $totalExpenses;

$weekly = $expensesObj->totalExspensePerDay();

$maxVal = max($weekly);
if ($maxVal <= 0) $maxVal = 1;

function money($n)
{
    return number_format((float) $n, 2, '.', ' ');
}

function barHeight($value, $maxVal)
{
    $h = (int) round(((float) $value / (float) $maxVal) * 60);
    if ($h < 6) $h = 6;
    return $h;
}
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
                    <svg class="w-full h-full text-primary" fill="none" viewBox="0 0 48 48"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M44 4H30.6666V17.3334H17.3334V30.6666H4V44H44V4Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold leading-tight tracking-[-0.015em]">Smart Wallet</h2>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-2">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu</p>

            <a class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl transition-colors"
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

            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
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
                <a href="/smart-wallet-oop/public/logout.php"
                    class="text-gray-400 hover:text-red-500 transition-colors">
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
                <h1 class="text-2xl font-bold text-[#111318] dark:text-white hidden sm:block">Dashboard</h1>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
            <div class="max-w-7xl mx-auto space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Balance</p>
                        <h3 class="text-2xl font-bold text-[#111318] dark:text-white tracking-tight mb-1">
                            <?= money($balance) ?>
                        </h3>
                        <p class="text-xs text-gray-400">Income - Expenses</p>
                    </div>

                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="p-2 bg-green-100 dark:bg-green-500/10 rounded-lg text-green-600 dark:text-green-400">
                                <span class="material-symbols-outlined">trending_up</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400">more_horiz</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Income</p>
                        <h3 class="text-2xl font-bold text-[#111318] dark:text-white tracking-tight mb-1">
                            <?= money($totalIncome) ?>
                        </h3>
                        <p class="text-xs text-gray-400">All time</p>
                    </div>

                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-2 bg-red-100 dark:bg-red-500/10 rounded-lg text-red-600 dark:text-red-400">
                                <span class="material-symbols-outlined">trending_down</span>
                            </div>
                            <span class="material-symbols-outlined text-gray-400">more_horiz</span>
                        </div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Expenses</p>
                        <h3 class="text-2xl font-bold text-[#111318] dark:text-white tracking-tight mb-1">
                            <?= money($totalExpenses) ?>
                        </h3>
                        <p class="text-xs text-gray-400">All time</p>
                    </div>

                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2 flex flex-col gap-6">
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Spending Analytics (This
                                    Week)</h3>
                                <div class="flex gap-2">
                                    <button
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-primary text-white">Weekly</button>
                                </div>
                            </div>

                            <div class="h-64 w-full flex items-end justify-between gap-2 sm:gap-4 px-2">
                                <?php foreach ($weekly as $day => $value): ?>
                                    <?php $h = barHeight($value, $maxVal); ?>
                                    <div class="flex flex-col items-center gap-2 group w-full">
                                        <div
                                            class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative chart-bar h-<?= $h ?> group-hover:bg-primary/40">
                                            <div
                                                class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                                <?= money($value) ?>
                                            </div>
                                        </div>
                                        <span class="text-xs text-gray-400 font-medium"><?= $day ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        </div>
                    </div>

                    <div class="flex flex-col gap-6">
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-2">Quick Info</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                This chart shows your expenses for the current week (Mon-Sun).
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>

</html>
