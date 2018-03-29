#!/bin/sh

BASE_DIR=$(dirname $(readlink -f "$0"))
if [ "$1" != "test" ]
then
    psql -h localhost -U tradegame -d tradegame < $BASE_DIR/tradegame.sql
    psql -h localhost -U tradegame -d tradegame < $BASE_DIR/tradegame_inserciones.sql
fi
psql -h localhost -U tradegame -d tradegame_test < $BASE_DIR/tradegame.sql
psql -h localhost -U tradegame -d tradegame_test < $BASE_DIR/tradegame_inserciones.sql
