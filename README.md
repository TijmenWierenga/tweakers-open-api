# Tweaker OpenAPI Demo API
Dit project bevat alle code om lokaal een API te starten met een OpenAPI specificatie als basis.

## Vereisten
Om alle software lokaal te kunnen draaien is het nodig om `docker` en `docker-compose` geïnstalleerd te hebben.

Wanneer je `docker` nog niet hebt geïnstalleerd kun je [hier](https://docs.docker.com/get-docker/) de stappen volgen om `docker` te installeren op jouw besturingssysteem.

Wanneer je `docker-compose` nog niet hebt geïnstalleerd kun je [hier](https://docs.docker.com/compose/install/) de stappen volgen om `docker-compose` te installeren op jouw besturingssysteem.

Om te verifiëren dat `docker` succesvol geïnstalleerd is dient het volgende commando uitgevoerd te worden op de command-line:

```bash
docker run hello-world
```

Wanneer de output overeenkomt met onderstaand voorbeeld is `docker` succesvol geïnstalleerd:
```bash
Hello from Docker!
This message shows that your installation appears to be working correctly.

To generate this message, Docker took the following steps:
 1. The Docker client contacted the Docker daemon.
 2. The Docker daemon pulled the "hello-world" image from the Docker Hub.
    (amd64)
 3. The Docker daemon created a new container from that image which runs the
    executable that produces the output you are currently reading.
 4. The Docker daemon streamed that output to the Docker client, which sent it
    to your terminal.

To try something more ambitious, you can run an Ubuntu container with:
 $ docker run -it ubuntu bash

Share images, automate workflows, and more with a free Docker ID:
 https://hub.docker.com/

For more examples and ideas, visit:
 https://docs.docker.com/get-started/

```

Om te verifiëren dat `docker-compose` succesvol is geïnstalleerd dient het volgende commando gedraaid te worden: 
```bash
docker-compose -v
```

Als de installatie geslaagd is de output van dit commando ongeveer gelijk aan dit:
```bash
docker-compose version 1.25.4, build 8d51620a
```

## Setup

Download de applicatie code door deze git-repository te clonen of download de code rechtstreeks als een ZIP-bestand.
Voer alle hierna genoemde commando's uit in de base directory van dit project.  

Alle applicaties kunnen worden gestart met één enkel commando:

```bash
COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose up -d 
```

Docker zal nu alle applicaties bouwen en starten.
Dit kan de eerste keer enkele minuten duren.

Wanneer het commando klaar is kunnen we verifiëren of alles succesvol gestart is met het volgende commando:
```bash
docker-compose ps
```

`docker-compose` toont vervolgens een lijst met services die gestart zijn met de bijbehorende status:

```bash
       Name                      Command               State                 Ports              
------------------------------------------------------------------------------------------------
demo-api_api_1        docker-php-entrypoint php  ...   Up      0.0.0.0:80->80/tcp               
demo-api_database_1   docker-entrypoint.sh mysqld      Up      0.0.0.0:3306->3306/tcp, 33060/tcp
demo-api_docs_1       /docker-entrypoint.sh sh / ...   Up      0.0.0.0:8080->80/tcp             
demo-api_mock_1       node dist/index.js mock -h ...   Up      0.0.0.0:3100->3100/tcp, 4010/tcp 

```

Als alle services de status `Up` hebben is de setup geslaagd.

Vanwege de port mapping die door Docker wordt geregeld zijn de services nu beschikbaar op localhost:

service | adres |
-----|----
API | [http://localhost](http://localhost) 
API docs | [http://localhost:8080](http://localhost:8080) 
API mock server | [http://localhost:3100](http://localhost:3100)

## Afsluiten

Alle services zijn te stoppen met het volgende commando:

```bash
docker-compose down
```

Indien je ook alle data en gebouwde images wil verwijderen kun je het volgende commando gebruiken:

```bash
docker-compose down --rmi=all -v --remove-orphans
```
