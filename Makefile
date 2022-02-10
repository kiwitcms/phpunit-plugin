default: help

.PHONY: cs
cs:
	@vendor/bin/phpcs

.PHONY: stan
stan:
	@vendor/bin/phpstan analyse


.PHONY: phpunit
phpunit:
	@vendor/bin/phpunit --coverage-clover coverage.xml --whitelist src/


.PHONY: help
help:
	@echo 'Usage: make [command]'
	@echo ''
	@echo 'Available commands:'
	@echo ''
	@echo '  cs           - Run code sniffer'
	@echo '  phpunit      - Run test suite and report to Kiwi TCMS'
	@echo '  stan         - Run static code analyser'
