image_name := "xtm-connect-sdk"

alias test := phpunit

_default:
  @just --list

build-image tag="latest" target="build":
  @docker image build . \
    --target {{ target }} \
    --tag {{ image_name }}:{{ tag }}

composer *subcommand:
  docker container run --rm -it \
    -v $(pwd):/app \
    --entrypoint composer \
    {{ image_name }} {{ subcommand }}

phpunit *subcommand:
  docker container run --rm -it \
    -v $(pwd):/app \
    --entrypoint phpunit \
    {{ image_name }} {{ subcommand }}
