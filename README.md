# Library

[![Build Status](https://github.com/Innmind/Library/workflows/CI/badge.svg)](https://github.com/Innmind/Library/actions?query=workflow%3ACI)
[![codecov](https://codecov.io/gh/Innmind/Library/branch/develop/graph/badge.svg)](https://codecov.io/gh/Innmind/Library)
[![Type Coverage](https://shepherd.dev/github/Innmind/Library/coverage.svg)](https://shepherd.dev/github/Innmind/Library)

Store indexed http resources in a [graph](https://neo4j.com) database.

By defaut it stores very little information for a resource, however extra information can be stored for images and html pages. More importantly it stores the relations (referer, canonical, alt) between the resources

## Installation

```sh
composer create-project innmind/library
```

Copy the file `config/.env.dist` to `config/.env` and adapt the variables to your environment (they can also be defined as env vars in the web server).

Then:

```sh
docker-compose up -d
```

## Usage

The following call will give you links to the definitions of the available resources:

```
OPTIONS /*
Accept: application/json
Authorization: Bearer api_key_defined_in_env_var
```

For every link provided in the response you can emit an `OPTIONS` request to it that will provided the properties you're allowed to provide when creating/updating the resource and the ones provided when you query a resource.
