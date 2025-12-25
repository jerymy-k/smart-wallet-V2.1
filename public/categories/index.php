<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../app/Models/Category.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /smart-wallet-oop/public/login.php');
    exit;
}
$user_id = $_SESSION['user_id'];
$fullName = $_SESSION['full_name'];
$email = $_SESSION['email'];
$error = "";
$success = "";
$categorie = new Category();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categorie_name'])) {
    $categorieName = trim($_POST['categorie_name']);
    $categorieType = $_POST['categorie_type'];
    if (strlen($categorieName) < 4) {
        $error = "name you saisi is invalid.";

    } else {
        $created = $categorie->create($user_id, $categorieName, $categorieType);
        if (!$created) {
            $error = "The operation was not successful.";
        } else {
            $success = "The operation was successful.";
        }
    }
    unset($_POST);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categorie_name_edit'])) {
    $categorieNameEdit = trim($_POST['categorie_name_edit']);
    $categorieTypeEdit = $_POST['categorie_type_edit'];
    $categorieId = $_POST['id_categorie_edit'];
    if (strlen($categorieNameEdit) < 4) {
        $error = "name you saisi is invalid.";

    } else {
        $updated = $categorie->updateCategorie($user_id, $categorieId, $categorieNameEdit, $categorieTypeEdit);
        if (!$updated) {
            $error = "The operation was not successful.";
        } else {
            $success = "The operation was successful.";

        }
    }
}
if (isset($_GET['deletId'])) {
    $categorieId = $_GET['deletId'];
    $deleted = $categorie->deleteCategorie($categorieId, $user_id);
    if (!$deleted) {
        $error = "The operation was not successful.";
    } else {
        $success = "The operation was successful.";
        header('Location: /smart-wallet-oop/public/categories/index.php');
        exit;
    }
}
$totalCategories = $categorie->someCategories(null, $user_id);
$totalCategoriesIncomes = $categorie->someCategories('income', $user_id);
$totalCategoriesExpenses = $categorie->someCategories('expense', $user_id);
$allCategories = $categorie->getAllCategoriesByUser($user_id);

?>
<!DOCTYPE html>
<html class="dark" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Categories | Smart Wallet</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        "2xl": "1rem",
                        full: "9999px",
                    },
                },
            },
        };
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

            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="/smart-wallet-oop/public/dashboard.php">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">dashboard</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Dashboard</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 bg-primary/10 text-primary rounded-xl transition-colors"
                href="/smart-wallet-oop/public/pages/categories/index.php">
                <span class="material-symbols-outlined">category</span>
                <span class="font-semibold text-sm">Categories</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="/smart-wallet-oop/public/pages/transactions/index.php">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">receipt_long</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Transactions</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="/smart-wallet-oop/public/pages/analytics/index.php">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">analytics</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Analytics</span>
            </a>

            <a class="flex items-center gap-3 px-4 py-3 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-[#282e39] rounded-xl transition-colors group"
                href="/smart-wallet-oop/public/pages/settings/index.php">
                <span class="material-symbols-outlined group-hover:text-primary transition-colors">settings</span>
                <span
                    class="font-medium text-sm group-hover:text-[#111318] dark:group-hover:text-white transition-colors">Preferences</span>
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
                <div>
                    <h1 class="text-2xl font-bold text-[#111318] dark:text-white">Categories</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Manage your income & expense categories
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden md:flex relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-outlined text-gray-400 text-[20px]">search</span>
                    </div>
                    <input
                        class="block w-72 pl-10 pr-3 py-2 border border-gray-200 dark:border-border-dark rounded-lg leading-5 bg-gray-50 dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition-all"
                        placeholder="Search category..." type="text" />
                </div>

                <button id="openModalBtn"
                    class="flex items-center gap-2 bg-primary hover:bg-primary/90 transition px-4 py-2 rounded-lg font-semibold text-white shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Add Category
                </button>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 scroll-smooth">
            <div class="max-w-7xl mx-auto space-y-6">
                <?php if (!empty($error)): ?>
                    <div
                        class="bg-red-500/10 border border-red-500/30 text-red-400 rounded-2xl px-5 py-4 flex items-start gap-3">
                        <span class="material-symbols-outlined text-red-400">error</span>
                        <div class="flex-1">
                            <p class="font-semibold">Error</p>
                            <p class="text-sm"><?= htmlspecialchars($error);
                            ?></p>
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
                            <p class="text-sm"><?= htmlspecialchars($success);
                            ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                <?php $success = ""; ?>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Categories</p>
                        <div class="flex items-end justify-between">
                            <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">
                                <?= $totalCategories; ?>
                            </h3>
                            <span class="text-xs text-gray-400">All types</span>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Income Categories</p>
                        <div class="flex items-end justify-between">
                            <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">
                                <?= $totalCategoriesIncomes ?>
                            </h3>
                            <span class="text-xs text-green-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">trending_up</span> healthy
                            </span>
                        </div>
                    </div>
                    <div
                        class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Expense Categories</p>
                        <div class="flex items-end justify-between">
                            <h3 class="text-3xl font-bold text-[#111318] dark:text-white tracking-tight">
                                <?= $totalCategoriesExpenses ?>
                            </h3>
                            <span class="text-xs text-red-400 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px]">trending_down</span> track
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                    <div class="xl:col-span-1 flex flex-col gap-6">

                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Categories by Type</h3>
                                <span class="material-symbols-outlined text-gray-400">more_horiz</span>
                            </div>

                            <div class="flex items-center gap-5">
                                <div class="relative h-28 w-28 rounded-full"
                                    style="background: conic-gradient(#135bec 0 33%, #22c55e 33% 52%, #ef4444 52% 100%);">
                                    <div class="absolute inset-3 bg-white dark:bg-surface-dark rounded-full"></div>
                                    <div class="absolute inset-0 grid place-items-center">
                                        <div class="text-center">
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Total</p>
                                            <p class="text-xl font-bold">12</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3 text-sm">
                                    <div class="flex items-center justify-between gap-6">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                                            <span class="text-gray-600 dark:text-gray-300">Both</span>
                                        </div>
                                        <span class="font-semibold text-[#111318] dark:text-white">4</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-6">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                                            <span class="text-gray-600 dark:text-gray-300">Income</span>
                                        </div>
                                        <span class="font-semibold text-[#111318] dark:text-white">2</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-6">
                                        <div class="flex items-center gap-2">
                                            <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                                            <span class="text-gray-600 dark:text-gray-300">Expense</span>
                                        </div>
                                        <span class="font-semibold text-[#111318] dark:text-white">6</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white dark:bg-surface-dark p-6 rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm">
                            <div class="flex items-center justify-between mb-5">
                                <h3 class="text-lg font-bold text-[#111318] dark:text-white">Top Categories</h3>
                                <span class="text-xs text-gray-400">sample</span>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-500 dark:text-gray-400">Housing</span>
                                        <span class="font-semibold">65%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                                        <div class="h-2 rounded-full bg-primary" style="width:65%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-500 dark:text-gray-400">Food</span>
                                        <span class="font-semibold">35%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                                        <div class="h-2 rounded-full bg-green-500" style="width:35%"></div>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-500 dark:text-gray-400">Entertainment</span>
                                        <span class="font-semibold">20%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-gray-100 dark:bg-background-dark">
                                        <div class="h-2 rounded-full bg-red-500" style="width:20%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div
                        class="xl:col-span-2 bg-white dark:bg-surface-dark rounded-2xl border border-gray-200 dark:border-border-dark shadow-sm overflow-hidden">
                        <div
                            class="p-6 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white">All Categories</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-white/5 text-sm uppercase text-gray-400">
                                    <tr>
                                        <th class="px-6 py-4">Name</th>
                                        <th class="px-6 py-4">Type</th>
                                        <th class="px-6 py-4">Created</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (!empty($allCategories)): ?>
                                        <?php foreach ($allCategories as $ac): ?>
                                            <tr class="border-t border-border-dark/60 hover:bg-white/5 transition">
                                                <td class="px-6 py-4 font-medium"><?= $ac['name'] ?></td>
                                                <td class="px-6 py-4">
                                                    <span class="px-3 py-1 text-xs rounded-full <?=
                                                        $ac['type'] === 'income'
                                                        ? 'bg-green-500/20 text-green-400'
                                                        : ($ac['type'] === 'both'
                                                            ? 'bg-gray-500/20 text-gray-400'
                                                            : 'bg-red-500/20 text-red-400')
                                                        ?>"><?= $ac['type']; ?></span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-gray-400"><?= $ac['created_at']; ?></td>
                                                <td class="px-6 py-4 text-right">
                                                    <div class="inline-flex gap-2">
                                                        <a data-id="<?= $ac['id'] ?>"
                                                            class="openModalBtnEdit p-2 rounded-lg hover:bg-white/10">
                                                            <span class="material-symbols-outlined text-blue-400">edit</span>
                                                        </a>
                                                        <a href="/smart-wallet-oop/public/categories/index.php?deletId=<?= $ac['id'] ?>"
                                                            class="p-2 rounded-lg hover:bg-white/10"
                                                            onclick="return confirm('Are you sure you want to delete <?= $ac['name']; ?>?');">
                                                            
                                                            <span class="material-symbols-outlined text-red-400">delete</span>
                                                        </a>

                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="px-6 py-6 text-center text-gray-400">
                                                No categories found
                                            </td>
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

    <div id="categoryModal" class="fixed inset-0 z-50 hidden">
        <div id="modalOverlay" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative min-h-full flex items-center justify-center p-4">
            <div
                class="w-full max-w-lg bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-primary/15 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">add</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white">Add Category</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Create a new category for your
                                wallet</p>
                        </div>
                    </div>

                    <button id="closeModalBtn" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-300">close</span>
                    </button>
                </div>

                <form class="p-6 space-y-4" method="POST">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Category name</label>
                        <input name="categorie_name" type="text" placeholder="e.g. Food, Salary..." required
                            class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Type</label>
                        <select name="categorie_type"
                            class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                            <option value="both">Both</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" id="cancelBtn"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-white/5 transition font-semibold text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 transition font-semibold text-sm text-white shadow-lg shadow-primary/20">
                            Save Category
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Tip: names must be unique per user.
                    </p>
                </form>
            </div>
        </div>
    </div>
    <div id="categoryModalEdit" class="fixed inset-0 z-50 hidden">
        <div id="modalOverlayEdit" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="relative min-h-full flex items-center justify-center p-4">
            <div
                class="w-full max-w-lg bg-white dark:bg-surface-dark border border-gray-200 dark:border-border-dark rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-100 dark:border-border-dark flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-primary/15 text-primary flex items-center justify-center">
                            <span class="material-symbols-outlined">edit</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-[#111318] dark:text-white">Edit Category</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 -mt-0.5">Edit your category for your
                                wallet</p>
                        </div>
                    </div>

                    <button id="closeModalBtnEdit" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5">
                        <span class="material-symbols-outlined text-gray-500 dark:text-gray-300">close</span>
                    </button>
                </div>

                <form class="p-6 space-y-4" method="POST">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Category name</label>
                        <input name="categorie_name_edit" type="text" placeholder="e.g. Food, Salary..." required
                            class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary" />
                        <input type="text" name="id_categorie_edit" hidden>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-200">Type</label>
                        <select name="categorie_type_edit"
                            class="mt-2 w-full rounded-xl bg-gray-50 dark:bg-background-dark border border-gray-200 dark:border-border-dark px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40 focus:border-primary">
                            <option value="both">Both</option>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <button type="button" id="cancelBtnEdit"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 dark:border-border-dark bg-white dark:bg-surface-dark hover:bg-gray-50 dark:hover:bg-white/5 transition font-semibold text-sm">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 transition font-semibold text-sm text-white shadow-lg shadow-primary/20">
                            Save Category
                        </button>
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Tip: names must be unique per user.
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('categoryModal');
        const openBtn = document.getElementById('openModalBtn');
        const closeBtn = document.getElementById('closeModalBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const overlay = document.getElementById('modalOverlay');

        function openModal() {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', closeModal);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
        const modalEdit = document.getElementById('categoryModalEdit');
        const closeBtnEdit = document.getElementById('closeModalBtnEdit');
        const cancelBtnEdit = document.getElementById('cancelBtnEdit');
        const overlayEdit = document.getElementById('modalOverlayEdit');
        const inputId = document.querySelector('input[name="id_categorie_edit"]');
        function openModalEdit() {
            modalEdit.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModalEdit() {
            modalEdit.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        document.querySelectorAll('.openModalBtnEdit').forEach(btn => {
            btn.addEventListener('click', () => {
                openModalEdit();
                inputId.value = btn.dataset.id
                alert(btn.dataset.id);
            });
        });

        closeBtnEdit.addEventListener('click', closeModalEdit);
        cancelBtnEdit.addEventListener('click', closeModalEdit);
        overlayEdit.addEventListener('click', closeModalEdit);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModalEdit();
        });
    </script>

    </script>
</body>

</html>