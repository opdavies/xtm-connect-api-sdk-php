image_name := "xtm-connect-sdk"

_default:
  @just --list

build-image tag="latest":
  docker image build . \
    --tag {{ image_name }}:{{ tag }}

composer *subcommand:
  docker container run --rm -it \
    -v $(pwd):/app \
    --entrypoint composer \
    {{ image_name }} {{ subcommand }}
