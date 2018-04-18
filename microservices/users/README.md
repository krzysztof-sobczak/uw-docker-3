## Usage

```bash
make
make start

curl -X PUT -d'name=Lukasz&password=abcd' localhost:8001/user/lukasz
{"id":"lukasz"}

curl -X POST -d'username=lukasz&password=abcd' localhost:8001/auth/login
true
```

## Dependencies

* https://symfony.com/doc/current/components/http_foundation.html
* https://github.com/nikic/FastRoute
