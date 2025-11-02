<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Use Inter font family -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for modal */
        .modal-content::-webkit-scrollbar {
            width: 8px;
        }
        .modal-content::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
        .modal-content::-webkit-scrollbar-track {
            background-color: #f1f5f9;
        }
        /* Simple transition for the notification */
        #notification {
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
        }
        .notification-hidden {
            transform: translateX(100%);
            opacity: 0;
        }
        .notification-visible {
            transform: translateX(0);
            opacity: 1;
        }
    </style>
    <!-- Preconnect for Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Main Container -->
    <div class="container mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Product Management</h1>

        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
            <button id="addProductBtn" class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 transition duration-200">
                Add New Product
            </button>
            <button id="testNotifyBtn" class="w-full sm:w-auto bg-green-500 text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 transition duration-200">
                Test Notification
            </button>
        </div>

        <!-- Product Table -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <!-- Add overflow-x-auto for responsiveness on small screens -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Product rows will be injected by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="notification" class="notification-hidden fixed top-5 right-5 w-auto max-w-sm p-4 rounded-lg shadow-xl z-50">
        <p id="notification-message" class="text-white">Success!</p>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-60 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col">
            <!-- Modal Header -->
            <div class="flex justify-between items-center p-5 border-b border-gray-200">
                <h2 id="modalTitle" class="text-2xl font-semibold text-gray-800">Add New Product</h2>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600 transition">
                    <!-- Close Icon SVG -->
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Modal Body with Form -->
            <div class="p-6 overflow-y-auto modal-content">
                <form id="productForm">
                    <!-- Hidden input to store product ID for editing -->
                    <input type="hidden" id="productId">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                            <input type="text" id="productName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition" required>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="productCategory" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <!-- Changed from input to select -->
                            <select id="productCategory" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white" required>
                                <!-- Options will be populated by JavaScript -->
                                <option value="">Select a category</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="productStatus" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select id="productStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition bg-white">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="productDescription" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="productDescription" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                            <div class="flex items-center gap-4">
                                <img id="imagePreview" src="https://placehold.co/100x100/e2e8f0/9ca3af?text=Preview" alt="Image Preview" class="w-24 h-24 object-cover rounded-lg border border-gray-300">
                                <input type="file" id="productImage" class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100 transition">
                            </div>
                            <input type="hidden" id="imageUrlStorage"> <!-- To store existing image URL -->
                        </div>

                        <!-- Settings -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Settings</label>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center">
                                    <input id="settingFeatured" type="checkbox" value="Featured" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="settingFeatured" class="ml-2 block text-sm text-gray-900">Featured Product</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="settingOnSale" type="checkbox" value="On Sale" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label for="settingOnSale" class="ml-2 block text-sm text-gray-900">On Sale</label>
                                </div>
                            </div>
                        </div>

                        <!-- Image Size Requirement -->
                        <div class="md:col-span-2 border-t border-gray-200 pt-4 mt-2">
                            <p class="block text-sm font-medium text-gray-700 mb-2">Required Image Size (Optional)</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="imageWidth" class="block text-xs text-gray-600 mb-1">Min. Width (px)</label>
                                    <input type="number" id="imageWidth" placeholder="e.g., 800" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                                <div>
                                    <label for="imageHeight" class="block text-xs text-gray-600 mb-1">Min. Height (px)</label>
                                    <input type="number" id="imageHeight" placeholder="e.g., 600" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end items-center gap-4 p-5 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                <button id="cancelBtn" class="bg-gray-200 text-gray-700 px-5 py-2.5 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Cancel
                </button>
                <button id="saveBtn" type="submit" form="productForm" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow-md hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Save Product
                </button>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div id="viewModal" class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-60 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] flex flex-col">
            <!-- View Modal Header -->
            <div class="flex justify-between items-center p-5 border-b border-gray-200">
                <h2 id="viewModalTitle" class="text-2xl font-semibold text-gray-800">Product Details</h2>
                <button id="viewCloseBtn" class="text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <!-- View Modal Body -->
            <div id="viewModalContent" class="p-6 overflow-y-auto modal-content space-y-4">
                <img id="viewImage" src="" alt="Product Image" class="w-full h-64 object-cover rounded-lg border border-gray-200 mb-4">
                <div>
                    <strong class="text-gray-600">Name:</strong>
                    <p id="viewName" class="text-lg text-gray-900"></p>
                </div>
                <div>
                    <strong class="text-gray-600">Category:</strong>
                    <p id="viewCategory" class="text-gray-900"></p>
                </div>
                <div>
                    <strong class="text-gray-600">Status:</strong>
                    <span id="viewStatus" class="px-3 py-1 rounded-full text-sm font-medium"></span>
                </div>
                <div>
                    <strong class="text-gray-600">Description:</strong>
                    <p id="viewDescription" class="text-gray-900"></p>
                </div>
                <div>
                    <strong class="text-gray-600">Settings:</strong>
                    <p id="viewSettings" class="text-gray-900"></p>
                </div>
                <div>
                    <strong class="text-gray-600">Image Requirements:</strong>
                    <p id="viewImageReq" class="text-gray-900"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 p-4 hidden">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
            <div class="p-6 text-center">
                <!-- Warning Icon -->
                <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-2xl font-semibold text-gray-800 mb-2">Confirm Deletion</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete this product? This action cannot be undone.</p>
                <!-- Hidden input to store ID of product to delete -->
                <input type="hidden" id="deleteProductId">
                <div class="flex justify-center gap-4">
                    <button id="cancelDeleteBtn" class="bg-gray-200 text-gray-700 px-6 py-2.5 rounded-lg hover:bg-gray-300 transition focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancel
                    </button>
                    <button id="confirmDeleteBtn" class="bg-red-600 text-white px-6 py-2.5 rounded-lg shadow-md hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/js/main.js"></script>
</body>
</html>
