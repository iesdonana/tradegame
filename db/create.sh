#!/bin/sh

if [ "$1" = "travis" ]
then
    psql -U postgres -c "CREATE DATABASE tradegame_test;"
    psql -U postgres -c "CREATE USER tradegame PASSWORD 'tradegame' SUPERUSER;"
else
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists tradegame
    [ "$1" != "test" ] && sudo -u postgres dropdb --if-exists tradegame_test
    [ "$1" != "test" ] && sudo -u postgres dropuser --if-exists tradegame
    sudo -u postgres psql -c "CREATE USER tradegame PASSWORD 'tradegame' SUPERUSER;"
    [ "$1" != "test" ] && sudo -u postgres createdb -O tradegame tradegame
    sudo -u postgres createdb -O tradegame tradegame_test
    LINE="localhost:5432:*:tradegame:tradegame"
    FILE=~/.pgpass
    if [ ! -f $FILE ]
    then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE
    then
        echo "$LINE" >> $FILE
    fi
fi
