const path = require('path');
const fs = require('fs');

const DATA = path.join(__dirname, '../data/products.json');

function loadProducts() {
  return JSON.parse(fs.readFileSync(DATA, 'utf8'));
}

function saveProducts(products) {
  fs.writeFileSync(DATA, JSON.stringify(products, null, 2));
}

function getAllProducts(req, res) {
  const products = loadProducts();
  return res.status(200).json({ data: products, message: 'success' });
}

function getProductById(req, res) {
  const products = loadProducts();
  const id = parseInt(req.params.id, 10);
  const product = products.find((item) => item.id === id);

  if (!product) {
    return res.status(404).json({
      error: 'PRODUCT_NOT_FOUND',
      message: `Product with id ${req.params.id} does not exist`
    });
  }

  return res.status(200).json({ data: product, message: 'success' });
}

function placeOrder(req, res) {
  const { product_id, quantity } = req.body;

  if (product_id === undefined || product_id === null) {
    return res.status(400).json({
      error: 'VALIDATION_ERROR',
      message: 'The product_id field is required.'
    });
  }

  if (quantity === undefined || quantity === null) {
    return res.status(400).json({
      error: 'VALIDATION_ERROR',
      message: 'The quantity field is required.'
    });
  }

  if (quantity === 0) {
    return res.status(400).json({
      error: 'INVALID_QUANTITY',
      message: 'Quantity must be greater than zero.'
    });
  }

  if (quantity < 0) {
    return res.status(400).json({
      error: 'INVALID_QUANTITY',
      message: 'Quantity cannot be negative.'
    });
  }

  if (!Number.isInteger(quantity)) {
    return res.status(400).json({
      error: 'INVALID_QUANTITY',
      message: 'Quantity must be a whole number.'
    });
  }

  const products = loadProducts();
  const product = products.find((item) => item.id === parseInt(product_id, 10));

  if (!product) {
    return res.status(404).json({
      error: 'PRODUCT_NOT_FOUND',
      message: `Product with id ${product_id} does not exist.`
    });
  }

  if (product.stock === 0) {
    return res.status(400).json({
      error: 'OUT_OF_STOCK',
      message: 'Product is out of stock.'
    });
  }

  if (quantity > product.stock) {
    return res.status(400).json({
      error: 'STOCK_EXCEEDED',
      message: `Requested quantity (${quantity}) exceeds available stock (${product.stock}).`
    });
  }

  product.stock -= quantity;
  saveProducts(products);

  const total_price = product.price * quantity;
  const remaining_stock = product.stock;

  return res.status(201).json({
    message: 'Order successful',
    product: product.name,
    quantity,
    total_price,
    remaining_stock
  });
}

module.exports = {
  getAllProducts,
  getProductById,
  placeOrder
};