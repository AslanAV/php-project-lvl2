### Hexlet tests and linter status:
[![Actions Status](https://github.com/AslanAV/php-project-lvl2/workflows/hexlet-check/badge.svg)](https://github.com/AslanAV/php-project-lvl2/actions)
[![PHP Composer](https://github.com/AslanAV/php-project-lvl2/actions/workflows/php.yml/badge.svg)](https://github.com/AslanAV/php-project-lvl2/actions/workflows/php.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/dd2d343814360801a8f6/maintainability)](https://codeclimate.com/github/AslanAV/php-project-lvl2/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/dd2d343814360801a8f6/test_coverage)](https://codeclimate.com/github/AslanAV/php-project-lvl2/test_coverage)

## Setup
```sh
$ git clone https://github.com/hexlet-boilerplates/php-package.git

$ make install
```

## Run tests & linter
```sh
$ make test

$ make lint

$ make lint-fix
```

## Test Coverage
```sh
$ make test-coverage
```

### Help
```shell
$ ./bin/gendiff -h
```

### Use gendiff for two json files
```shell
$ ./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json
```
[![asciicast](https://asciinema.org/a/NneWoJZQtvCTFEZJHokQa7sHX.svg)](https://asciinema.org/a/NneWoJZQtvCTFEZJHokQa7sHX)


### Use gendiff for two yml files
```shell
$ ./bin/gendiff tests/fixtures/filepath1.yml tests/fixtures/filepath2.yml
```
[![asciicast](https://asciinema.org/a/tawspNzEHTf8TFa9fMRUjm170.svg)](https://asciinema.org/a/tawspNzEHTf8TFa9fMRUjm170)


### Use gendiff for two recursive yaml and json files
```shell
./bin/gendiff tests/fixtures/filepath1.json tests/fixtures/filepath2.json
```
```shell
./bin/gendiff tests/fixtures/fileRecursive1.yaml tests/fixtures/fileRecursive2.yaml

```
[![asciicast](https://asciinema.org/a/J70nBBzByMpHP5rC83s3UKmKf.svg)](https://asciinema.org/a/J70nBBzByMpHP5rC83s3UKmKf)


### Use gendiff with format plain for two recursive yaml and json files
```shell
./bin/gendiff --format plain tests/fixtures/filepath1.json tests/fixtures/filepath2.json
```
```shell
./bin/gendiff --format plain tests/fixtures/fileRecursive1.yaml tests/fixtures/fileRecursive2.yaml

```
[![asciicast](https://asciinema.org/a/nlUvkqNojK33A2lepylluOBGb.svg)](https://asciinema.org/a/nlUvkqNojK33A2lepylluOBGb)