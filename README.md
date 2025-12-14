# ReviewProjct

The project requests the current weather once an hour using coordinates from the coordinates table via external services:
    https://www.meteomatics.com/en/api/getting-started/
    https://www.weatherapi.com

# Run:
1. cd backend
2. make get-env
3. sudo make up
4. sudo make first-run

# Tests
1. sudo make test

# Inside php container
1. sudo docker exec -ti backend-service-php-1 /bin/bash

# Swagger
    ./vendor/bin/openapi app -o openapi.yaml
