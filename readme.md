# rate my car

The objetive of this project is create a simple internal search interface for estimating the average market value for a year/make model.

## Documents

[Design Document](./docs/design.md)

## features and work in progress.

- [x] Interface nice and clean
- [] Search Page
- [] Results page
- [] db
- [] the search algorithm

## development

This project was built using docker containers to run a development server. I decide to use this strategy to avoid issues with the configurations from Windows/WSL/Centos7 , also the target server is a out of service Centos7 server with lack of updates and unsoported, so I decide to use docker containers to give me the chance to use more recent versions of PHP/Apache, and easily could be moved to a more secure and updated server if is neccesary.

```bash
# The first time build the server
docker build -t php-server .

# then start your development container.
docker run -p 8080:80 -d -v $(pwd)/src:/var/www/html/ php-server

# and if you need enter to the container.
docker exec -it 'id ' bash
```

then you could open in your browser `http://localhost:8080`.

## deployment

```bash

docker compose up -d --build
```

##  Journal

### 03 march 2025

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

so then I install docker in the server and I think is ready to receive the code.

### 04 March 2025

I not was touched php from my Thesis project in the university but i think is a good start point.
in my opinion i prefer to have a strong typed language that a python code (yes is posible to use type but not mandatory) also create IA-assisted-code with python is easy so ... I want a challenge ( bad idea because this is a house assetsment)

This day I starting to create the webpage and the first 'design', I just keep simple but have a nice and dark style to not have an alone form with a white background burning your eyes.

after figure it out how to enable postgreSQL driver into my docker php container I proceed to set up my postgreSQL container and starting to see how to import the data.

Finally I test if all of this works in the remote server, and fortunelly it works!

so I assume we could continue with the development

### 05 March 2025

When I tried to run PostgreSQL, I encountered a couple of blockers in the connection between the database and PHP.
Fortunately, after a couple of hours of research and correctly configuring my Docker setup, I got it running. Now I have the database running on the server with PHP. The next step is to upload the almost 2GB file of information.

### 06 March 2025

Now I have the environment ready, and I could keep my mind with the data and how to do the calculations to achieve the task.
so the first step is define the data for the db based on the file, to achieve this I use python/pandas to analyze the data.

The data is all contained in one big file, so I believe the intention is spread out and optimize in relational style to 

I may confess that I create the first script of python using copilot, and then I modified it by my own needs.
( I never was use pandas before 🥲)

Is interesting how many attacks I received to get access to the db and the webpage.

some key points : 

VIN number : is like a car fingerprint  as not two vehicles in operation with the same number, is composed of 17 characters (digits and capital letters)

the csv file have 4713914

so with that in mind, for the following columns is better to identify it in their own elements.

[columnName(unique_registry)]

dealers_name(58073) : we could assume that we have differents dealers with the following data

- dealer_name(58073)
- dealer_street(58072) (exists two dealers with the same address?)
- dealer_city(6821)
- dealer_state(66)
- dealer_zip (15665)


- seller_website(58163)

following with the vehicle information we have

- vin : unique identifier
- make (1707) : 
- model (264103) : each model is associated to a maker
- trim (204430): 
- listing_price : 
- listing_mileage (number)
- used (bool)
- certified (bool)
- style (47264) (string) :
    looks like everyone have different ways to decribe it.
- driven_wheels (4856)(string):  
    pretty the same as style
- engine (42165) string: 
    non standart
- fuel_type(1617) (string)
     'Essence' this is real?
- exterior_color (44303) (string)
- interior_color (37803) (string)

- first_seen_date(624) date
- last_seen_date(5) date
- dealer_vdp_last_seen_date (456) date
- listing_status : [nan 'in_transit']

