for dir in web payment cinema users centrifugo monitor-server
do
  echo "Goto project $dir"
  cd $dir
  rm -rf ./rr
  rm -rf ./runtime/*
  cp ../docker/roadrunner/rr .
  cd ../
done