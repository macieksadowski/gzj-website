#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
ANGULAR_DIR="$ROOT_DIR/angular-src"
OUTPUT_DIR="$ROOT_DIR/public_html/angular-assets"
PROD_ENV_FILE="$ANGULAR_DIR/src/environments/environment.prod.ts"

if [[ ! -d "$ANGULAR_DIR" ]]; then
  echo "[ERROR] Angular directory not found: $ANGULAR_DIR"
  exit 1
fi

if ! command -v npx >/dev/null 2>&1; then
  echo "[ERROR] npx not found. Install Node.js and npm first."
  exit 1
fi

echo "[INFO] Root: $ROOT_DIR"
echo "[INFO] Angular: $ANGULAR_DIR"
echo "[INFO] Output: $OUTPUT_DIR"

if [[ ! -f "$PROD_ENV_FILE" ]]; then
  echo "[ERROR] Missing Angular prod env file: $PROD_ENV_FILE"
  echo "[HINT] Create it first and set apiUrl/apiToken for production deployment."
  exit 1
fi

echo "[STEP] Using existing environment.prod.ts from repository..."

echo "[STEP] Cleaning previous build output..."
mkdir -p "$OUTPUT_DIR"
find "$OUTPUT_DIR" -mindepth 1 -maxdepth 1 -exec rm -rf {} +

echo "[STEP] Build Angular (production)..."
pushd "$ANGULAR_DIR" >/dev/null
npx ng build --configuration production
popd >/dev/null

if [[ ! -f "$OUTPUT_DIR/index.html" ]]; then
  echo "[ERROR] Build finished without index.html in $OUTPUT_DIR"
  exit 1
fi

echo "[OK] Angular dashboard build is ready for Laravel endpoint /dashboard"
