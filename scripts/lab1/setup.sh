#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"

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

  echo "[lab1] Setting up ${service_path}..."
  cd "${ROOT}/${service_path}"
  run_composer install --no-interaction --prefer-dist
  [[ -f .env ]] || cp .env.example .env
  php artisan key:generate --force >/dev/null
  mkdir -p database
  [[ -f "${db_file}" ]] || touch "${db_file}"
  eval "${migrate_cmd}"
}

setup_service "lab1/microservices/student-service" "database/students.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab1/microservices/course-service" "database/courses.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab1/microservices/enrollment-service" "database/enrollments.sqlite" "php artisan migrate:fresh --force"

echo "[lab1] Setting up academe frontend..."
cd "${ROOT}/lab1/academe"
run_composer install --no-interaction --prefer-dist
npm install
npm run build
[[ -f .env ]] || cp .env.example .env
php artisan key:generate --force >/dev/null
mkdir -p database
[[ -f database/database.sqlite ]] || touch database/database.sqlite
php artisan migrate:fresh --seed --force
php artisan config:clear

echo "[lab1] Setup complete."
echo "Start services with:"
echo "  bash scripts/lab1/serve-microservice.sh student"
echo "  bash scripts/lab1/serve-microservice.sh course"
echo "  bash scripts/lab1/serve-microservice.sh enrollment"
echo "  bash scripts/lab1/serve-academe.sh"
