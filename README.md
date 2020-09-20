<html>

<a href="https://codeclimate.com/github/IgBuS/php-project-lvl2/maintainability"><img src="https://api.codeclimate.com/v1/badges/b3ef8370a5f3e5057fdf/maintainability" /></a>

<a href="https://codeclimate.com/github/IgBuS/php-project-lvl2/test_coverage"><img src="https://api.codeclimate.com/v1/badges/b3ef8370a5f3e5057fdf/test_coverage" /></a>

![CI](https://github.com/IgBuS/php-project-lvl2/workflows/CI/badge.svg)
</html>

[![asciicast](https://asciinema.org/a/IiLEqlQJW9nYDbq53hmPDAaby.svg)](https://asciinema.org/a/IiLEqlQJW9nYDbq53hmPDAaby) 
## GenDiff
This app was created to help you find out the difference between two versions of file or two same type files. You are able to use it with .json or .yaml files. Also you can choose format of difference presentation. 
For example:

# basic ('pretty') format


{
  - follow: false
    host: hexlet.io
  - proxy: 123.234.53.22
  - timeout: 50
  + timeout: 20
  + verbose: true
}

# plain format

Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to [complex value]
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]

# json format

[{"type":"parent","key":"common","children":[{"type":"unchanged","key":"setting1","value":"Value 1"},{"type":"deleted","key":"setting2","value":200},{"type":"changed","key":"setting3","oldValue":true,"newValue":{"key":"value"}},{"type":"parent","key":"setting6","children":[{"type":"unchanged","key":"key","value":"value"},{"type":"parent","key":"doge","children":[{"type":"changed","key":"wow","oldValue":"too much","newValue":"so much"}]},{"type":"added","key":"ops","value":"vops"}]},{"type":"added","key":"follow","value":false},{"type":"added","key":"setting4","value":"blah blah"},{"type":"added","key":"setting5","value":{"key5":"value5"}}]},{"type":"parent","key":"group1","children":[{"type":"changed","key":"baz","oldValue":"bas","newValue":"bars"},{"type":"unchanged","key":"foo","value":"bar"},{"type":"changed","key":"nest","oldValue":{"key":"value"},"newValue":"str"}]},{"type":"deleted","key":"group2","value":{"abc":12345,"deep":{"id":45}}},{"type":"added","key":"group3","value":{"fee":100500,"deep":{"id":{"number":45}}}}]

Gendiff app is avaliable fo nested structures.

## Setup

```sh
$ git clone https://github.com/IgBuS/php-project-lvl2.git

$ make install
```
If you use Composer, for package install use:
```sh
$ composer require biserg/gendiff
```

If you want app to be available globally, use global installation:
```sh
$ composer global require biserg/gendiff
```

## How to run

Local installation:
(you have to be in package bin directory)

```sh
$ php gendiff
```

Global installation:

```sh
$ gendiff
```


Result video:

