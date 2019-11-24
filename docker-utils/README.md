# Docker instructions and guides

## You need to install docker. !!! ATTENTION DOCKER RUN ON HYPER-V ==> VIRTUALBOX WILL STOP WORKING !!!

## Dockerfile
In this first versions we use the dockerfile for building images. We need to build php an mysql image for this moment.
Built images will be uploaded to docker hub.

### Notes
- When i tried docker I built one image at a time. (comment or cut temporary lines after ```FROM```)
- ```COPY src/ /var/www/html ```  src is the directory where we have all the code of git repo
- ``` ADD dbsetup.sql /docker-entrypoint-initdb.d ``` we need to have the dbsetup.sql file in the same directory of dockerfile this will initialize the image with the DB. Done at build time for persistency.

### Build example of php image

```
docker build -t php_img . 
```
#### Where 
- t is for tagging the image to be built
- . is the directory of docker file in this case the current directory

## Docker-compose
Here we use docker compose to make containers running from built images that will be pulled up from docker hub. in the future we can try to build images from composer...

## Login
setup your username and password before doing pull push operations

## Pull and run from docker-hub step by step
### These command are from docker CLI (needed a bash or cmd/powershell)

#### Pull images from repository
```
docker pull -a <username>/<repository>
```
- -a for pulling al tagged images

#### Run containers

First you need the docker-compose.yml file for running containers. It need to be in the directory where you're launching command. 

```
docker-compose up
```

## Push images to docker-hub

### Tag images
```
docker tag image_id yourhubusername/repository:tag
```
#### Example
```
docker tag bb38976d03cf sordinho/digital_student_record:mysql
docker tag 34d1d5d2bbbc sordinho/digital_student_record:php
```

### Push images
```
docker push yourhubusername/repository
```
#### Example
```
docker push sordinho/digital_student_record
```