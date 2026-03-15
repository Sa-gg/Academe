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

  echo "[lab2] Setting up ${service_path}..."
  cd "${ROOT}/${service_path}"
  run_composer install --no-interaction --prefer-dist
  [[ -f .env ]] || cp .env.example .env
  php artisan key:generate --force >/dev/null
  mkdir -p database
  [[ -f "${db_file}" ]] || touch "${db_file}"
  eval "${migrate_cmd}"
}

setup_service "lab2/services/student-service" "database/students.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab2/services/course-service" "database/courses.sqlite" "php artisan migrate:fresh --seed --force"
setup_service "lab2/services/enrollment-service" "database/enrollments.sqlite" "php artisan migrate:fresh --force"

echo "[lab2] Setup complete."
echo "Start services with:"
echo "  bash scripts/lab2/serve.sh student"
echo "  bash scripts/lab2/serve.sh course"
echo "  bash scripts/lab2/serve.sh enrollment"
