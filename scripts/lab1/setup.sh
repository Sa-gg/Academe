#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

TOTAL_STEPS=4
CURRENT_STEP=0

step() {
  CURRENT_STEP=$((CURRENT_STEP + 1))
  echo
  echo "[lab1][${CURRENT_STEP}/${TOTAL_STEPS}] $1"
}

note() {
  echo "[lab1] $1"
}

run_composer() {
  if command -v composer >/dev/null 2>&1; then
    composer "$@"
  else
    cmd.exe //C composer "$@"
  fi
}

setup_service() {
  local service_path="$1"
  local db_file="$2"
  local migrate_cmd="$3"

  step "Preparing ${service_path}"
  cd "${ROOT}/${service_path}"
  if [[ -d vendor ]]; then
    note "PHP dependencies already installed, skipping composer install."
  else
    note "Installing PHP dependencies... this may take a minute on first run."
    run_composer install --no-interaction --prefer-dist
  fi
  [[ -f .env ]] || cp .env.example .env
  php artisan key:generate --force >/dev/null
  mkdir -p database
  [[ -f "${db_file}" ]] || touch "${db_file}"
  note "Resetting database with fresh migrations."
  eval "${migrate_cmd}"
}

setup_service "lab1/microservices/student-service" "database/students.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab1/microservices/course-service" "database/courses.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab1/microservices/enrollment-service" "database/enrollments.sqlite" "php artisan migrate:fresh --force"

step "Preparing academe frontend"
cd "${ROOT}/lab1/academe"
if [[ -d vendor ]]; then
  note "PHP dependencies already installed, skipping composer install."
else
  note "Installing PHP dependencies..."
  run_composer install --no-interaction --prefer-dist
fi
if [[ -d node_modules ]]; then
  note "Node dependencies already installed, skipping npm install."
else
  note "Installing frontend dependencies... this may take a minute on first run."
  npm install
fi
if [[ -f public/build/manifest.json ]]; then
  note "Frontend assets already built, skipping npm run build."
else
  note "Building frontend assets..."
  npm run build
fi
[[ -f .env ]] || cp .env.example .env
php artisan key:generate --force >/dev/null
mkdir -p database
[[ -f database/database.sqlite ]] || touch database/database.sqlite
note "Resetting academe database with fresh migrations and seed data."
php artisan migrate:fresh --seed --force
php artisan config:clear

step "Setup complete"
note "Start services with:"
note "  bash scripts/lab1/serve-microservice.sh student"
note "  bash scripts/lab1/serve-microservice.sh course"
note "  bash scripts/lab1/serve-microservice.sh enrollment"
note "  bash scripts/lab1/serve-academe.sh"
