#echo "Compile proto"
#cd shared/
#./rr compile
#cd ../

for dir in web payment cinema users centrifugo
do
  echo "Update project $dir"
  cd $dir
  rm -rf ./vendor/spiral/shared
  composer update
  # ./vendor/bin/rr get-binary -s beta
  echo "Clear cache"
  rm -rf ./runtime/*
  cp ../docker/roadrunner/rr .
  cd ../
done

docker compose up