#!/usr/bin/env ash

WATCH_DIR="/etc/nginx/sites-enabled"

if ! command -v inotifywait >/dev/null 2>&1; then
  echo "Please install inotify-tools first (e.g. apt install inotify-tools)"
  exit 1
fi

echo "Watching ${WATCH_DIR} for changes..."

inotifywait -m -e close_write,create,move "${WATCH_DIR}" --format '%w%f' | while read -r FILE; do
  # Only handle regular files
  [[ -f "$FILE" ]] || continue

  echo "Detected change in $FILE"

  # Skip if file already contains "ssl"
  if grep -qi "ssl" "$FILE"; then
    echo "SSL detected in $FILE — skipping."
    continue
  fi

  # Extract first server_name line and get names
  SERVER_NAME=$(grep -m1 "server_name" "$FILE" | sed -E 's/.*server_name\s+([^;]+);.*/\1/' | awk '{print $1}')
  if [[ -z "$SERVER_NAME" ]]; then
    echo "No server_name found in $FILE — skipping."
    continue
  fi

  echo "Restarting nginx..."
  /etc/init.d/nginx restart

  echo "Running certbot for $SERVER_NAME..."
  certbot --nginx -d "$SERVER_NAME"

  echo "Done processing $FILE"
done
