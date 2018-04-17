## Usage

```bash
make
make start

curl -X PUT -d'name=Lukasz&password=abcd' localhost/user/lukasz
{"id":"lukasz"}

curl -X POST -d'username=lukasz&password=abcd' localhost/auth/login
true
```

## Dependencies

```bash
docker run --rm --volume $PWD:/app composer require symfony/http-foundation
docker run --rm --volume $PWD:/app composer require nikic/fast-route
```

* https://symfony.com/doc/current/components/http_foundation.html
* https://github.com/nikic/FastRoute
