for dir in web payment cinema users centrifugo
do
  echo "Goto project $dir"
  cd $dir
  rm -rf ./rr
  cp ../docker/roadrunner/rr .
  chmod +x ./rr
  rm -rf ./runtime/*
  cd ../
done