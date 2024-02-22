## Push to docker registry

## Build the image
`docker build -t ekobills` .

## Login
`docker login --username username`

## Create tag for the image.
`docker tag ekobills username/my-repo:tag`

## Push the image to docker registry.
`docker push username/ekobills`
