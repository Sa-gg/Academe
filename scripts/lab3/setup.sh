#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

cd "${ROOT}/lab3/server"
npm install
[[ -f .env ]] || cp .env.example .env
cp data/products.seed.json data/products.json

echo "[lab3] Setup complete."
echo "Start API with: bash scripts/lab3/serve.sh"
