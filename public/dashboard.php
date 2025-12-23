<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}
$fullName = $_SESSION['full_name'];
$email = $_SESSION['email'];
?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Finance Tracker Dashboard</title>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "surface-dark": "#1c1f27",
                        "border-dark": "#282e39",
                    },
                    fontFamily: {
                        "display": ["Manrope", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "2xl": "1rem", "full": "9999px" },
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
                href="#">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="#">
                <span
                    class="material-symbols-outlined group-hover:text-primary transition-colors">account_balance_wallet</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">My
                    Wallet</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="#">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">receipt_long</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Transactions</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="#">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">analytics</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Analytics</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="#">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">credit_card</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Cards</span>
            </a>
            <div class="mt-8">
                <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Settings</p>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                    href="#">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                    <span
                        class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Preferences</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                    href="#">
                    <span class="material-symbols-outlined group-hover:text-primary transition-colors">help</span>
                    <span
                        class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Help
                        Center</span>
                </a>
            </div>
        </nav>
        <div class="p-4 border-t border-gray-100 dark:border-border-dark">
            <div class="flex items-center gap-3 px-2 py-2">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-tr from-primary to-purple-500 flex items-center justify-center text-white font-bold shadow-lg shadow-primary/20">
                    <?= $fullName[0]?>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-[#111318] dark:text-white truncate"><?= $fullName ?></p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= $email ?></p>
                </div>
                <a href="logout.php" class="text-gray-400 hover:text-red-500 transition-colors">
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
            <div class="flex items-center gap-4">
                <div class="hidden md:flex relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
                    </div>
                    <input
                        class="block w-64 pl-10 pr-3 py-2 border border-gray-200 dark:border-border-dark rounded-lg leading-5 bg-gray-50 dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition-all"
                        placeholder="Search transactions..." type="text" />
                </div>
                <button
                    class="relative p-2 text-gray-400 hover:text-primary transition-colors rounded-lg hover:bg-gray-100 dark:hover:bg-surface-dark">
                    <span class="material-symbols-outlined">notifications</span>
                    <span
                        class="absolute top-2 right-2 h-2 w-2 rounded-full bg-red-500 border-2 border-white dark:border-background-dark"></span>
                </button>
                <div
                    class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark rounded-lg text-sm font-medium text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                    <span>Oct 2023</span>
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
            <div class="max-w-7xl mx-auto space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <span class="material-symbols-outlined text-8xl text-primary">account_balance_wallet</span>
                        </div>
                        <div class="relative z-10">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Balance</p>
                            <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight mb-2">$24,592.00
                            </h3>
                            <div class="flex items-center gap-2">
                                <span
                                    class="bg-green-100 dark:bg-green-500/10 text-green-600 dark:text-green-400 text-xs font-bold px-2 py-1 rounded-full flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">arrow_upward</span> 12.5%
                                </span>
                                <span class="text-xs text-gray-400">vs last month</span>
                            </div>
                        </div>
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
                        <h3 class="text-2xl font-bold text-[#111318] dark:text-white tracking-tight mb-1">$4,250.00</h3>
                        <p class="text-xs text-gray-400">+5% from last month</p>
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
                        <h3 class="text-2xl font-bold text-[#111318] dark:text-white tracking-tight mb-1">$1,890.00</h3>
                        <p class="text-xs text-gray-400">-2% from last month</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    <div class="xl:col-span-2 flex flex-col gap-6">
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Spending Analytics</h3>
                                <div class="flex gap-2">
                                    <button
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-primary text-white">Weekly</button>
                                    <button
                                        class="px-3 py-1 text-xs font-semibold rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-border-dark">Monthly</button>
                                </div>
                            </div>
                            <div class="h-64 w-full flex items-end justify-between gap-2 sm:gap-4 px-2">
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-32 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $320</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Mon</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-48 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $480</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Tue</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary rounded-t-lg relative h-60 chart-bar shadow-lg shadow-primary/20">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $600</div>
                                    </div>
                                    <span class="text-xs text-[#111318] dark:text-white font-bold">Wed</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-24 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $240</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Thu</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-40 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $400</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Fri</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-52 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $520</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Sat</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 group w-full">
                                    <div
                                        class="w-full bg-primary/20 dark:bg-primary/20 rounded-t-lg relative h-36 chart-bar group-hover:bg-primary/40">
                                        <div
                                            class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                            $360</div>
                                    </div>
                                    <span class="text-xs text-gray-400 font-medium">Sun</span>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm flex flex-col">
                            <div
                                class="p-6 border-b border-gray-100 dark:border-border-dark flex justify-between items-center">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Recent Transactions</h3>
                                <a class="text-sm text-primary font-semibold hover:text-primary/80" href="#">View
                                    All</a>
                            </div>
                            <div class="p-4">
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-colors cursor-pointer group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center text-red-600 dark:text-red-400">
                                            <span class="material-symbols-outlined">movie</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#111318] dark:text-white">Netflix Subscription</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Entertainment • Oct 24,
                                                2023</p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-red-500">-$15.00</span>
                                </div>
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-colors cursor-pointer group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center text-green-600 dark:text-green-400">
                                            <span class="material-symbols-outlined">payments</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#111318] dark:text-white">Salary - October</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Income • Oct 23, 2023
                                            </p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-green-500">+$3,200.00</span>
                                </div>
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-colors cursor-pointer group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-full bg-orange-100 dark:bg-orange-500/20 flex items-center justify-center text-orange-600 dark:text-orange-400">
                                            <span class="material-symbols-outlined">shopping_cart</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#111318] dark:text-white">Whole Foods Market</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Groceries • Oct 21, 2023
                                            </p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-[#111318] dark:text-white">-$84.50</span>
                                </div>
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-white/5 rounded-xl transition-colors cursor-pointer group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                            <span class="material-symbols-outlined">bolt</span>
                                        </div>
                                        <div>
                                            <p class="font-bold text-[#111318] dark:text-white">Electric Bill</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Utilities • Oct 20, 2023
                                            </p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-[#111318] dark:text-white">-$120.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6">
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">My Card</h3>
                                <button class="text-primary hover:text-primary/80">
                                    <span class="material-symbols-outlined">add</span>
                                </button>
                            </div>
                            <div
                                class="w-full aspect-[1.586] bg-gradient-to-br from-primary via-[#4176e3] to-[#7198eb] rounded-xl p-6 text-white shadow-xl shadow-primary/30 flex flex-col justify-between relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl -ml-5 -mb-5">
                                </div>
                                <div class="flex justify-between items-start z-10">
                                    <div class="flex flex-col">
                                        <span class="text-xs opacity-80 font-light">Current Balance</span>
                                        <span class="text-2xl font-bold tracking-tight">$24,592.00</span>
                                    </div>
                                    <svg class="h-8 w-auto fill-white opacity-80" viewBox="0 0 48 48"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M32 10a14 14 0 1 0 0 28 14 14 0 0 0 0-28Zm-16 0a14 14 0 1 0 0 28 14 14 0 0 0 0-28Z"
                                            fill-opacity="0.5"></path>
                                        <path d="M24 38a14 14 0 0 1 0-28 14 14 0 0 1 0 28Z"></path>
                                    </svg>
                                </div>
                                <div class="z-10">
                                    <div class="flex justify-between items-end">
                                        <div class="flex gap-4">
                                            <span class="text-lg tracking-widest font-mono">****</span>
                                            <span class="text-lg tracking-widest font-mono">****</span>
                                            <span class="text-lg tracking-widest font-mono">****</span>
                                            <span class="text-lg tracking-widest font-mono">4289</span>
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-end mt-4">
                                        <div class="flex flex-col">
                                            <span class="text-[10px] opacity-70 uppercase">Card Holder</span>
                                            <span class="text-sm font-medium tracking-wide">John Doe</span>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-[10px] opacity-70 uppercase">Expires</span>
                                            <span class="text-sm font-medium tracking-wide">12/25</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Quick Transfer</h3>
                            <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-none">
                                <div class="flex flex-col items-center gap-2 cursor-pointer group">
                                    <div
                                        class="h-14 w-14 rounded-full border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center text-gray-400 group-hover:border-primary group-hover:text-primary transition-colors">
                                        <span class="material-symbols-outlined">add</span>
                                    </div>
                                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Add New</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer">
                                    <div
                                        class="h-14 w-14 rounded-full bg-blue-100 flex items-center justify-center relative">
                                        <span class="font-bold text-blue-600">AS</span>
                                    </div>
                                    <span class="text-xs font-medium text-[#111318] dark:text-white">Alex S.</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer">
                                    <div class="h-14 w-14 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="font-bold text-purple-600">MR</span>
                                    </div>
                                    <span class="text-xs font-medium text-[#111318] dark:text-white">Maria R.</span>
                                </div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer">
                                    <div class="h-14 w-14 rounded-full bg-yellow-100 flex items-center justify-center">
                                        <span class="font-bold text-yellow-600">DK</span>
                                    </div>
                                    <span class="text-xs font-medium text-[#111318] dark:text-white">David K.</span>
                                </div>
                            </div>
                            <div class="flex gap-2 mt-4">
                                <input
                                    class="flex-1 bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary dark:text-white"
                                    placeholder="Amount" type="text" />
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white rounded-lg px-4 py-2 text-sm font-semibold transition-colors">Send</button>
                            </div>
                        </div>
                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white mb-4">Spending by Category</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-300 font-medium">Housing</span>
                                        <span class="font-bold text-[#111318] dark:text-white">65%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-background-dark rounded-full h-2">
                                        <div class="bg-primary h-2 rounded-full" style="width: 65%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-300 font-medium">Food &amp;
                                            Dining</span>
                                        <span class="font-bold text-[#111318] dark:text-white">20%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-background-dark rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: 20%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-300 font-medium">Entertainment</span>
                                        <span class="font-bold text-[#111318] dark:text-white">15%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-background-dark rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: 15%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>