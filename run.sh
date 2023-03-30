echo "##############Cleaning the cache##############"
for dir in web payment cinema users centrifugo
do
  cd $dir
  rm -rf ./runtime/*
  cd ../
done

echo "##############Docker pull##############"
docker-compose -f docker-compose.yaml pull

echo "##############Docker build##############"
docker-compose -f docker-compose.yaml build

echo "##############Copy vendor packages##############"
docker-compose -f docker-compose.yaml up -d
docker cp centrifugo-rpc:/var/www/vendor ./centrifugo/vendor
docker cp web:/var/www/vendor ./web/vendor
docker cp users:/var/www/vendor ./users/vendor
docker cp payment:/var/www/vendor ./payment/vendor
docker cp cinema:/var/www/vendor ./cinema/vendor

echo "##############Docker Up##############"
docker-compose -f docker-compose.yaml stop
docker-compose -f docker-compose.yaml -f docker-compose-local.yaml up -d
