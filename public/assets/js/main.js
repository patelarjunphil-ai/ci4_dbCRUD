document.addEventListener('DOMContentLoaded', () => {
    // --- API BASE URL ---
    const API_BASE_URL = '/api';

    // --- STATE ---
    let products = [];
    const categories = ['Apparel', 'Accessories', 'Electronics', 'Home Goods', 'Footwear'];

    // --- SELECTORS ---
    const addProductBtn = document.getElementById('addProductBtn');
    const testNotifyBtn = document.getElementById('testNotifyBtn');
    const productTableBody = document.getElementById('product-table-body');
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notification-message');

    // Product Modal
    const productModal = document.getElementById('productModal');
    const modalTitle = document.getElementById('modalTitle');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const saveBtn = document.getElementById('saveBtn');
    const productForm = document.getElementById('productForm');

    // Form Fields
    const productIdInput = document.getElementById('productId');
    const productNameInput = document.getElementById('productName');
    const productCategoryInput = document.getElementById('productCategory');
    const productStatusInput = document.getElementById('productStatus');
    const productDescriptionInput = document.getElementById('productDescription');
    const productImageInput = document.getElementById('productImage');
    const imagePreview = document.getElementById('imagePreview');
    const imageUrlStorage = document.getElementById('imageUrlStorage');
    const settingFeatured = document.getElementById('settingFeatured');
    const settingOnSale = document.getElementById('settingOnSale');
    const imageWidthInput = document.getElementById('imageWidth');
    const imageHeightInput = document.getElementById('imageHeight');

    // View Modal
    const viewModal = document.getElementById('viewModal');
    const viewCloseBtn = document.getElementById('viewCloseBtn');
    const viewModalTitle = document.getElementById('viewModalTitle');
    const viewImage = document.getElementById('viewImage');
    const viewName = document.getElementById('viewName');
    const viewCategory = document.getElementById('viewCategory');
    const viewStatus = document.getElementById('viewStatus');
    const viewDescription = document.getElementById('viewDescription');
    const viewSettings = document.getElementById('viewSettings');
    const viewImageReq = document.getElementById('viewImageReq');

    // Delete Modal
    const deleteModal = document.getElementById('deleteModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteProductIdInput = document.getElementById('deleteProductId');

    let notificationTimer;

    // --- FUNCTIONS ---

    /**
     * Shows a notification toast.
     * @param {string} message - The message to display.
     * @param {string} type - 'success' (default) or 'error'.
     */
    function showNotification(message, type = 'success') {
        clearTimeout(notificationTimer);
        notificationMessage.textContent = message;

        if (type === 'success') {
            notification.classList.remove('bg-red-500');
            notification.classList.add('bg-green-500');
        } else {
            notification.classList.remove('bg-green-500');
            notification.classList.add('bg-red-500');
        }

        notification.classList.remove('notification-hidden');
        notification.classList.add('notification-visible');

        notificationTimer = setTimeout(() => {
            notification.classList.add('notification-hidden');
            notification.classList.remove('notification-visible');
        }, 3000);
    }

    /**
     * Renders the product table from the 'products' array.
     */
    function renderTable() {
        productTableBody.innerHTML = '';
        if (products.length === 0) {
            productTableBody.innerHTML = `
                <tr>
                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                        No products found. Add a new product to get started!
                    </td>
                </tr>
            `;
            return;
        }

        products.forEach(product => {
            const statusClass = product.status === 'Active'
                ? 'bg-green-100 text-green-800'
                : 'bg-red-100 text-red-800';

            const row = `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full object-cover" src="${product.imageUrl}" alt="${product.name}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900">${product.name}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">${product.category}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                            ${product.status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                        <button data-id="${product.id}" class="view-btn text-blue-600 hover:text-blue-900" title="View">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                        <button data-id="${product.id}" class="edit-btn text-yellow-600 hover:text-yellow-900" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        <button data-id="${product.id}" class="delete-btn text-red-600 hover:text-red-900" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
            productTableBody.innerHTML += row;
        });
    }

    /**
     * Fetches products from the API and renders the table.
     */
    async function fetchAndRenderProducts() {
        try {
            const response = await fetch(`${API_BASE_URL}/products`);
            if (!response.ok) throw new Error('Failed to fetch products');
            products = await response.json();
            renderTable();
        } catch (error) {
            showNotification(error.message, 'error');
        }
    }

    /**
     * Opens the Add/Edit modal and resets the form.
     */
    function openProductModal() {
        productForm.reset();
        productIdInput.value = '';
        imagePreview.src = 'https://placehold.co/100x100/e2e8f0/9ca3af?text=Preview';
        imageUrlStorage.value = '';
        settingFeatured.checked = false;
        settingOnSale.checked = false;
        modalTitle.textContent = 'Add New Product';
        productModal.classList.remove('hidden');
    }

    /**
     * Closes the Add/Edit modal.
     */
    function closeProductModal() {
        productModal.classList.add('hidden');
    }

    /**
     * Handles the image file input change event.
     */
    function handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const newImageUrl = e.target.result;
                imagePreview.src = newImageUrl;
                imageUrlStorage.value = newImageUrl;
            };
            reader.readAsDataURL(file);
        }
    }

    /**
     * Handles form submission for both Add and Edit.
     */
    async function handleFormSubmit(event) {
        event.preventDefault();

        const id = productIdInput.value;
        const settings = [];
        if (settingFeatured.checked) settings.push('Featured');
        if (settingOnSale.checked) settings.push('On Sale');

        const productData = {
            name: productNameInput.value,
            category: productCategoryInput.value,
            description: productDescriptionInput.value,
            imageUrl: imageUrlStorage.value,
            status: productStatusInput.value,
            settings: settings,
            imgWidth: parseInt(imageWidthInput.value) || null,
            imgHeight: parseInt(imageHeightInput.value) || null,
        };

        try {
            let response;
            if (id) {
                // --- Edit Mode ---
                response = await fetch(`${API_BASE_URL}/products/${id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(productData),
                });
                if (!response.ok) throw new Error('Failed to update product');
                showNotification('Product updated successfully!', 'success');
            } else {
                // --- Add Mode ---
                if (!productData.imageUrl) {
                    productData.imageUrl = `https://placehold.co/400x400/e2e8f0/333?text=${productData.name.substring(0, 10)}`;
                }
                response = await fetch(`${API_BASE_URL}/products`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(productData),
                });
                if (!response.ok) throw new Error('Failed to add product');
                showNotification('Product added successfully!', 'success');
            }

            closeProductModal();
            fetchAndRenderProducts();
        } catch (error) {
            showNotification(error.message, 'error');
        }
    }

    /**
     * Opens the View modal and populates it with product data.
     */
    async function openViewModal(id) {
        try {
            const response = await fetch(`${API_BASE_URL}/products/${id}`);
            if (!response.ok) throw new Error('Failed to fetch product details');
            const product = await response.json();

            viewModalTitle.textContent = product.name;
            viewImage.src = product.imageUrl;
            viewImage.alt = product.name;
            viewName.textContent = product.name;
            viewCategory.textContent = product.category;
            viewDescription.textContent = product.description || 'N/A';

            viewStatus.textContent = product.status;
            if (product.status === 'Active') {
                viewStatus.className = 'px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
            } else {
                viewStatus.className = 'px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
            }

            viewSettings.textContent = product.settings.length > 0 ? product.settings.join(', ') : 'None';

            let reqText = 'N/A';
            if (product.imgWidth && product.imgHeight) {
                reqText = `Min. ${product.imgWidth}px (W) x ${product.imgHeight}px (H)`;
            }
            viewImageReq.textContent = reqText;

            viewModal.classList.remove('hidden');
        } catch (error) {
            showNotification(error.message, 'error');
        }
    }

    /**
     * Opens the Edit modal and populates the form.
     */
    async function openEditModal(id) {
        try {
            const response = await fetch(`${API_BASE_URL}/products/${id}`);
            if (!response.ok) throw new Error('Failed to fetch product details');
            const product = await response.json();

            productForm.reset();

            productIdInput.value = product.id;
            productNameInput.value = product.name;
            productCategoryInput.value = product.category;
            productStatusInput.value = product.status;
            productDescriptionInput.value = product.description;
            imagePreview.src = product.imageUrl;
            imageUrlStorage.value = product.imageUrl;
            imageWidthInput.value = product.imgWidth || '';
            imageHeightInput.value = product.imgHeight || '';

            settingFeatured.checked = product.settings.includes('Featured');
            settingOnSale.checked = product.settings.includes('On Sale');

            modalTitle.textContent = 'Edit Product';
            productModal.classList.remove('hidden');
        } catch (error) {
            showNotification(error.message, 'error');
        }
    }

    /**
     * Opens the Delete confirmation modal.
     */
    function openDeleteModal(id) {
        deleteProductIdInput.value = id;
        deleteModal.classList.remove('hidden');
    }

    /**
     * Handles the table click event for actions.
     */
    function handleTableClick(event) {
        const button = event.target.closest('button');
        if (!button) return;

        const id = button.dataset.id;
        if (!id) return;

        if (button.classList.contains('view-btn')) {
            openViewModal(id);
        } else if (button.classList.contains('edit-btn')) {
            openEditModal(id);
        } else if (button.classList.contains('delete-btn')) {
            openDeleteModal(id);
        }
    }

    /**
     * Confirms and executes product deletion.
     */
    async function confirmDelete() {
        const id = deleteProductIdInput.value;
        if (!id) return;

        try {
            const response = await fetch(`${API_BASE_URL}/products/${id}`, { method: 'DELETE' });
            if (!response.ok) throw new Error('Failed to delete product');

            deleteModal.classList.add('hidden');
            fetchAndRenderProducts();
            showNotification('Product deleted successfully.', 'error');
        } catch (error) {
            showNotification(error.message, 'error');
        }
    }

    /**
     * Populates the category dropdown in the product modal.
     */
    function populateCategoryDropdown() {
        productCategoryInput.innerHTML = '<option value="">Select a category</option>';
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = category;
            productCategoryInput.appendChild(option);
        });
    }

    // --- EVENT LISTENERS ---

    addProductBtn.addEventListener('click', openProductModal);
    testNotifyBtn.addEventListener('click', () => showNotification('This is a test notification!', 'success'));

    productForm.addEventListener('submit', handleFormSubmit);
    productImageInput.addEventListener('change', handleImageUpload);
    closeModalBtn.addEventListener('click', closeProductModal);
    cancelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        closeProductModal();
    });

    viewCloseBtn.addEventListener('click', () => viewModal.classList.add('hidden'));

    cancelDeleteBtn.addEventListener('click', () => deleteModal.classList.add('hidden'));
    confirmDeleteBtn.addEventListener('click', confirmDelete);

    productTableBody.addEventListener('click', handleTableClick);

    // --- INITIALIZATION ---
    populateCategoryDropdown();
    fetchAndRenderProducts();
});
