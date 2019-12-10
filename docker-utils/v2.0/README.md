# Docker instructions and guides v2.0 (single image)

## You need to install docker. !!! ATTENTION DOCKER RUN ON HYPER-V ==> VIRTUALBOX WILL STOP WORKING !!!

## Running

0. First it is recommended to pull the image `docker pull groupj/digital_student_record:r1`
1. Run in terminal `docker run --hostname docker-desktop --rm -p 80:80 groupj/digital_student_record`
1. Cross your fingers
1. Go to [localhost](http://localhost)

## Building

For building the image you need the [Dockerfile](Dockerfile)

#### Instructions

1. Place all the content of this directory at the same level of the github repository directory.
2. Open terminal and go to directory containing repo, Dockerfile and other files.
3. Run `docker build -t groupj/digital_student_record:rXXX` where `XXX` is the TAG (release number)
4. Take a coffee

## Notes

- main.cf is the postfix configuration file
- You can find more docker utility commands [HERE](../v1.0/README.md)
- You can take a tea or whatever you want if you don't like coffee...
