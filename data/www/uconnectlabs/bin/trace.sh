#!/bin/sh
cd ./../../../../
docker-compose exec -T --user devilbox php /shared/httpd/uconnectlabs/htdocs/wp-content/bin/start_trace -l
