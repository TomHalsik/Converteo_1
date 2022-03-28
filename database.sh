#!/bin/sh
docker exec -i converteo_1_db_1 mysql -u root -ptest myDb  < dump/dump.sql;