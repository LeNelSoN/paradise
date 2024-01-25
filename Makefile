# Variables
DOCKER_COMPOSE = docker compose

help:
	@echo "Available targets:"
	@echo "  make up             - Start Docker containers"
	@echo "  make down           - Stop and remove Docker containers"

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down