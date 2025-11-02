<?php

namespace App\Controllers;

use App\Libraries\ProductLibrary;
use CodeIgniter\API\ResponseTrait;

/**
 * Class Products
 *
 * This controller handles all API requests for products.
 * It uses the ProductLibrary to interact with the database.
 */
class Products extends BaseController
{
    use ResponseTrait;

    /**
     * @var ProductLibrary
     */
    protected $productLibrary;

    /**
     * Products constructor.
     */
    public function __construct()
    {
        $this->productLibrary = new ProductLibrary();
    }

    /**
     * Displays the main product management page.
     *
     * @return \CodeIgniter\HTTP\ResponseInterface|string
     */
    public function index()
    {
        return view('products');
    }

    /**
     * Returns a list of all products.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function list()
    {
        $products = $this->productLibrary->getProducts();
        return $this->respond($products);
    }

    /**
     * Returns a single product.
     *
     * @param int|null $id The product ID.
     * @return \CodeIgniter\HTTP\Response
     */
    public function show($id = null)
    {
        $product = $this->productLibrary->getProductById($id);
        if ($product) {
            // Decode the settings if it's a JSON string
            if (is_string($product['settings'])) {
                $product['settings'] = json_decode($product['settings'], true);
            }
            return $this->respond($product);
        }
        return $this->failNotFound('Product not found');
    }

    /**
     * Creates a new product.
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        $id = $this->productLibrary->createProduct($data);
        if ($id) {
            $data['id'] = $id;
            return $this->respondCreated($data);
        }
        return $this->fail('Failed to create product');
    }

    /**
     * Updates an existing product.
     *
     * @param int|null $id The product ID.
     * @return \CodeIgniter\HTTP\Response
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        $updated = $this->productLibrary->updateProduct($id, $data);
        if ($updated) {
            return $this->respond($data);
        }
        return $this->fail('Failed to update product');
    }

    /**
     * Deletes a product.
     *
     * @param int|null $id The product ID.
     * @return \CodeIgniter\HTTP\Response
     */
    public function delete($id = null)
    {
        $deleted = $this->productLibrary->deleteProduct($id);
        if ($deleted) {
            return $this->respondDeleted(['id' => $id]);
        }
        return $this->fail('Failed to delete product');
    }
}
