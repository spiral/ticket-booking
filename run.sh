#!/bin/bash

echo "Compile proto"
cd shared/
./rr compile
cd ../

for dir in web payment cinema users
do
  echo "Update project $dir"
  cd $dir
  rm -rf ./vendor/spiral/shared
  composer update
  echo "Clear cache"
  rm -rf ./runtime/*
  cd ../
done

docker compose up