COVERAGE_MIN=80

install:
	composer install

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src bin tests

test:
	composer exec --verbose phpunit tests

test-coverage:
	@coverage=$$(XDEBUG_MODE=coverage composer exec phpunit tests -- --coverage-text --coverage-clover coverage.xml | grep 'Lines:' | sed -E 's/.*Lines:[[:space:]]*([0-9.]+)%.*/\1/'); \
	echo "Coverage: $$coverage%"; \
	echo "Minimum required: $(COVERAGE_MIN)%"; \
	if [ "$$(echo "$$coverage < $(COVERAGE_MIN)" | bc -l)" -eq 1 ]; then \
		echo "Coverage is too low!"; \
		exit 1; \
	fi