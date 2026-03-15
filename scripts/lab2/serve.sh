#!/usr/bin/env bash
set -euo pipefail

if [[ $# -ne 1 ]]; then
  echo "Usage: bash scripts/lab2/serve.sh <student|course|enrollment>"
  exit 1
fi

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
SERVICE="$1"

case "${SERVICE}" in
  student)
    cd "${ROOT}/lab2/services/student-service"
    php artisan serve --port=8001
    ;;
  course)
    cd "${ROOT}/lab2/services/course-service"
    php artisan serve --port=8002
    ;;
  enrollment)
    cd "${ROOT}/lab2/services/enrollment-service"
    php artisan serve --port=8003
    ;;
  *)
    echo "Invalid service: ${SERVICE}"
    echo "Use one of: student, course, enrollment"
    exit 1
    ;;
esac
