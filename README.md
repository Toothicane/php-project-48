### Hexlet tests and linter status:
[![Actions Status](https://github.com/Toothicane/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/Toothicane/php-project-48/actions)

### Tests and Sonar status:
[![check](https://github.com/Toothicane/php-project-48/actions/workflows/check.yml/badge.svg)](https://github.com/Toothicane/php-project-48/actions/workflows/check.yml)

### Code coverage status:
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=Toothicane_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=Toothicane_php-project-48)

# Diff Checker 
Diff Checker is a CLI tool for finding differences between two serialized data files. Supported file formats are JSON and YAML.
The tool supports three output formats: *stylish* for a structured key-value diff representation, *plain* for a plain-text explanation of the differences, and *json* for JSON-formatted output.

## Examples 
Examples of using the tool are shown below.

### Flat JSON comparison demo:
https://asciinema.org/a/dCHXQS7u5SecXxQb

### Flat YAML comparison demo:
https://asciinema.org/a/SNVhYVNOeLJDyd4f

### Nested structures comparison with stylish formatter demo:
https://asciinema.org/a/O9Xs7yqHNRfSLSUN

### Nested structures comparison with plain formatter demo:
https://asciinema.org/a/2yJraSl7TYs0DxAC

### Nested structures comparison with json formatter demo:
https://asciinema.org/a/xjEcLkwgs9yinxBH

## Installation
After you clone the repository, run `make install` or `composer install` in the root directory. You will need **PHP 8.3** or higher and **Composer** installed on your machine.

## Usage
The commands are run from the root directory.
To see the differences between the files, run
`./bin/gendiff [--format <fmt>] <firstFile> <secondFile>`.
The `--format` flag is optional. If it is omitted, the tool uses *stylish* output by default. It can also be explicitly specified with `--format stylish`. 
To use the other formats, specify `--format plain` or `--format json`.
The `<firstFile>` and `<secondFile>` arguments specify the files to be compared. The second file is treated as the newer version of the first file. The changes are described from the perspective of the first file.
For help, run `./bin/gendiff -h` or `./bin/gendiff --help`.