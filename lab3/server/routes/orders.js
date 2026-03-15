const router = require('express').Router();
const ctrl = require('../controllers/orderController');

router.get('/products', ctrl.getAllProducts);
router.get('/products/:id', ctrl.getProductById);
router.post('/orders', ctrl.placeOrder);

module.exports = router;