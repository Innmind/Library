# Library

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Library/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Library/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/Library/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Library/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Library/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Library/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/Library/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Library/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/Library/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/Library/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/Library/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/Library/build-status/develop) |

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
