# rate my car

The objetive of this project is create a simple internal search interface for estimating the average market value for a year/make model.

## Documents

[Design Document](./docs/design)

## features

[ ] Interface nice and clean.
[ ] Search Page
[ ] Results page


## development

This project was built using docker containers to run a development server. I decide to use this strategy to avoid issues with the configurations from Windows/WSL/Centos7 , also the target server is a out of service Centos7 server with lack of updates and unsoported, so I decide to use docker containers to give me the chance to use more recent versions of PHP/Apache, and easily could be moved to a more secure and updated server if is neccesary.



```bash
# The first time build the server
docker build -t php-server

# then start your development container.
docker run -p 8080:80 -d -v $(pwd)/src:/var/www/html/ php-server


# and if you need enter to the container.
docker exec -it 'id ' bash
```

then you could open in your browser `http://localhost:8080`.

## deployment

```bash

docker run -p 80:80 -d --restart unless-stopped -v $(pwd)/src:/var/www/html/ php-server
```

##  Journal

This project have a target server with Centos7 that is end of life on June 30th.
this means that is imposible to update it or install new software using the defaults sources because their repositories are offline,
one solution is use the Centos vault or a mirror , in this case I use a backup from AlmaLinux a raliable project that provide a community supported spiritual sucessor to CentOS compatible with RHEL, backed by several enterprices like AWS, Microsoft and others.

to be able to install software we need to change the sources. I did it based in this tutorial.

https://almalinux.org/blog/2024-07-09-centos7-updates/

With this your are be able to update to the latests release for Centos 7 and install new software, but the packages are stalled to only security updates.

I fell more secure working with containers with more updated versions of the needed programs, also is easy to 
move all the project to a more secure and updated server.
I assume that server is the only thing that we could for now.
I used to work with limited ressources in the university and in my first job, but the works needs to be done.

so then I install docker in the server.


