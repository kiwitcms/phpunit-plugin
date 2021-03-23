default: help

.PHONY: cs
cs:
	@vendor/bin/phpcs

.PHONY: stan
stan:
	@vendor/bin/phpstan analyse


.PHONY: help
help:
	@echo 'Usage: make [command]'
	@echo ''
	@echo 'Available commands:'
	@echo ''
	@echo '  cs           - Run code sniffer'
	@echo '  stan         - Run static code analyser'
