## Usage

```bash
make
make start

curl -X PUT -d'related_user_id=kuba' localhost:8002/relations/lukasz
["lukasz"]

curl -X GET localhost:8002/relations/lukasz
{"id":"lukasz","data":{"name":"lach"},"hits":3}[
```

## Dependencies

* https://symfony.com/doc/current/components/http_foundation.html
* https://github.com/nikic/FastRoute
