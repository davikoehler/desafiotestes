.PHONY: up
up:
	docker compose up -d

.PHONY: test
test:
	./shell/composer test

.PHONY: coverage
coverage:
	./shell/composer coverage
