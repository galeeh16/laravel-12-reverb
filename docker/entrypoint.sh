#!/bin/sh
set -e

# Deteksi jumlah CPU core
# CPU_CORES=$(nproc)
CPU_CORES=4

# Hitung jumlah worker (2 per core, tapi dibatasi agar tidak melebihi RAM)
# Misal 4 core → 8 worker
WORKERS=$((CPU_CORES * 2))

# Batasi jika terlalu banyak (misal >16 worker)
if [ "$WORKERS" -gt 16 ]; then
  WORKERS=16
fi

# Gunakan default jika CPU_CORES tidak bisa dideteksi
if [ -z "$WORKERS" ] || [ "$WORKERS" -lt 1 ]; then
  WORKERS=4
fi

# Gunakan default max requests (bisa override via ENV)
MAX_REQUESTS=${MAX_REQUESTS:-10000}

echo "🔧 Starting Laravel Octane (FrankenPHP) with:"
echo "   CPU Cores   : $CPU_CORES"
echo "   Workers     : $WORKERS"
echo "   Max Requests: $MAX_REQUESTS"

# Pastikan preload Laravel tersedia (agar tidak error opcache.preload)
if [ ! -f /app/bootstrap/cache/preload.php ]; then
  echo "⚙️  Generating Laravel preload file..."
  php artisan optimize || true
fi

# Jalankan Octane FrankenPHP
exec php artisan octane:frankenphp \
  --workers=$WORKERS \
  --host=0.0.0.0 \
  --port=80 \
  --max-requests=$MAX_REQUESTS
