#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

step() {
	echo
	echo "[lab3] $1"
}

step "Preparing Lab 3 server"
cd "${ROOT}/lab3/server"
if [[ -d node_modules ]]; then
	echo "[lab3] Node dependencies already installed, skipping npm install."
else
	echo "[lab3] Installing Node dependencies... this may take a minute on first run."
	npm install
fi
[[ -f .env ]] || cp .env.example .env
step "Resetting products.json from seed baseline"
cp data/products.seed.json data/products.json

step "Setup complete"
echo "[lab3] Start API with: bash scripts/lab3/serve.sh"
