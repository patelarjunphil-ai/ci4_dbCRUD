<?php

namespace App\Libraries;

use App\Models\ProductModel;

/**
 * Class ProductLibrary
 *
 * This library encapsulates the business logic for managing products.
 * It interacts with the ProductModel to perform CRUD operations.
 */
class ProductLibrary
{
    /**
     * @var ProductModel
     */
    protected $productModel;

    /**
     * ProductLibrary constructor.
     */
    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Get all products.
     *
     * @return array An array of all products.
     */
    public function getProducts()
    {
        return $this->productModel->findAll();
    }

    /**
     * Get a product by its ID.
     *
     * @param int $id The product ID.
     * @return object|null The product object or null if not found.
     */
    public function getProductById($id)
    {
        return $this->productModel->find($id);
    }

    /**
     * Create a new product.
     * The settings array is JSON-encoded before insertion.
     *
     * @param array $data The product data.
     * @return int|string The ID of the new product.
     */
    public function createProduct($data)
    {
        if (isset($data['settings'])) {
            $data['settings'] = json_encode($data['settings']);
        }
        return $this->productModel->insert($data);
    }

    /**
     * Update a product.
     * The settings array is JSON-encoded before updating.
     *
     * @param int $id The product ID.
     * @param array $data The product data.
     * @return bool True if the update was successful, false otherwise.
     */
    public function updateProduct($id, $data)
    {
        if (isset($data['settings'])) {
            $data['settings'] = json_encode($data['settings']);
        }
        return $this->productModel->update($id, $data);
    }

    /**
     * Delete a product.
     *
     * @param int $id The product ID.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function deleteProduct($id)
    {
        return $this->productModel->delete($id);
    }
}
