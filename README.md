# Auth SSO

Unified system for authentication and authorization.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/848b8935a7d64916a012c69b2e523656)](https://www.codacy.com/app/mohr-evandro/sso?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=EvandroMohr/sso&amp;utm_campaign=Badge_Grade)
[![Build Status](https://travis-ci.org/mrprompt/sso.svg?branch=master)](https://travis-ci.org/mrprompt/sso)


## Environment details

- PHP+PDO 5.5+ 

### Database details

- PostgreSQL ^9.1 

Database/schema:

- db_sso/adm_sso


## GETTING START


Put the files into php webserver and make adjusts in the first section of config file:
`./system/config.php`

Also, you should make an alias for your server and point the root directory to public_html (for security reasons)

## Tests

Run inside project `phpunit`

## Changelog

### 0.1.0 - 16th Feb 2016

- GIT versioning

### 0.1.1 - 26th Oct 2016

- Travis adjusts
- Refactoring tests
- Define minimum PHP version

