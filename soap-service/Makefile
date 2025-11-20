#Sail
sail := ./vendor/bin/sail

# Initial Config
setup: up composerinstall migrate seed slink

# Build container
build:
	$(sail) build

# Up containers
up:
	$(sail) up -d

# Run migrations
migrate:
	$(sail) artisan migrate

# Run migrations and seed
seed:
	$(sail) artisan db:seed

# Refresh database and migrations
db_fresh:
	$(sail) artisan migrate:fresh --seed --force

# Rollback migrations
rollback:
	$(sail) artisan migrate:rollback

# Composer install
composerinstall:
	$(sail) composer install

# Stop containers
stop:
	$(sail) down

# Composer commands
composer:
	$(sail) composer $(filter-out $@,$(MAKECMDGOALS))

# Symbolic Link Reset
slink:
	$(sail) artisan storage:unlink
	$(sail) artisan storage:link

# Composer Auto Load
auto-load:
	$(sail) composer dump-autoload

# Run tests
test:
	$(sail) artisan test --parallel

#Remove containers
remove:
	$(sail) down --rmi all --volumes

# artisan queue:work
queue-work:
	$(sail) artisan queue:work
