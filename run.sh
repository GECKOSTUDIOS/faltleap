#!/usr/bin/env sh

LOCAL_USERNAME=$(whoami)
CONTAINER_NAME="faltleap_serve_$LOCAL_USERNAME"

# Clean previous builds
docker image rm faltleap:latest 2>/dev/null || true
docker build --no-cache . -t faltleap:latest

# Run container in detached mode
docker run -d  \
    -p 8090:80 \
    -v "$(pwd)":/var/www/localhost/htdocs \
    -v "$(pwd)/container/default.conf":/etc/nginx/http.d/default.conf \
    --name "$CONTAINER_NAME" \
    faltleap:latest

echo "==========================================="
echo "Faltleap is now serving your files at: http://localhost:8090"
echo "Press Ctrl+C to stop viewing logs (the server will keep running)"
echo "Run this script again or manually stop the container later."
echo "==========================================="

# Disable 'exit on error' just for docker logs
docker exec -it "$CONTAINER_NAME" ash -c tail -f /var/log/nginx/error.log
# After exiting logs, ask user whether to stop container
echo ""
read -p "Do you want to stop and remove the container? (y/N): " ans
if [ "$ans" = "y" ] || [ "$ans" = "Y" ]; then
    echo "Stopping container..."
    docker stop "$CONTAINER_NAME"
    docker rm "$CONTAINER_NAME"
else
    echo "Container '$CONTAINER_NAME' is still running."
fi
