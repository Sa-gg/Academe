# Lab 3 — curl Test Suite
## BIST 3B | System Architecture and Integration 2
## Section: BIST 3B

> Prerequisites: run `cd server && node server.js` so the API is running on port 3000

-------------------------------------
HAPPY PATH
-------------------------------------

## Step 1 — GET all products (200)
```bash
curl -i http://localhost:3000/api/products
```

Expected: HTTP 200, all 5 products with current stock

## Step 2 — GET single product (200)
```bash
curl -i http://localhost:3000/api/products/1
```

Expected: HTTP 200, Laptop data

## Step 3 — POST valid order (201)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":2}'
```

Expected: HTTP 201
```json
{
  "message": "Order successful",
  "product": "Laptop",
  "quantity": 2,
  "total_price": 91998,
  "remaining_stock": 8
}
```

## Step 4 — GET product after order — stock reduced (200)
```bash
curl -i http://localhost:3000/api/products/1
```

Expected: HTTP 200, Laptop stock now 8 (was 10)

-------------------------------------
EDGE CASES
-------------------------------------

## Step 5 — Invalid product ID (404)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":99,"quantity":1}'
```

Expected: HTTP 404 PRODUCT_NOT_FOUND

## Step 6 — Quantity = 0 (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":0}'
```

Expected: HTTP 400 INVALID_QUANTITY

## Step 7 — Negative quantity (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":-5}'
```

Expected: HTTP 400 INVALID_QUANTITY

## Step 8 — Stock exceeded (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":1,"quantity":9999}'
```

Expected: HTTP 400 STOCK_EXCEEDED

## Step 9 — Missing product_id (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"quantity":1}'
```

Expected: HTTP 400 VALIDATION_ERROR

## Step 10 — Missing quantity (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":1}'
```

Expected: HTTP 400 VALIDATION_ERROR

## Step 11 — Empty body (400)
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{}'
```

Expected: HTTP 400 VALIDATION_ERROR

## Step 12 — Out of stock (400)
# Drain Monitor (product 4, stock=8) to zero first:
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":4,"quantity":8}'
```

# Then attempt to order from empty stock:
```bash
curl -i -X POST http://localhost:3000/api/orders \
  -H "Content-Type: application/json" \
  -d '{"product_id":4,"quantity":1}'
```

Expected: HTTP 400 OUT_OF_STOCK

## Step 13 — Unknown route (404)
```bash
curl -i http://localhost:3000/api/nonexistent
```

Expected: HTTP 404 NOT_FOUND