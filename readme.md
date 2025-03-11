# rate my car

The objective of this project is to create a simple internal search interface for estimating the average market value for a year/make/model.

## Documents

[Design Document](./docs/design.md)

## Features and Work in Progress

- [x] Interface nice and clean
- [x] Search Page
- [x] Results page
- [x] Database
- [x] Search algorithm
- [x] Predict price by mileage.

## Development

This project was built using Docker containers to run a development server. I decided to use this strategy to avoid issues with configurations from Windows/WSL/CentOS 7. Also, the target server is an out-of-service CentOS 7 server with lack of updates and unsupported software, so Docker containers give me the chance to use more recent versions of PHP/Apache and easily move to a more secure and updated server if necessary.

```bash
# The first time build the server
docker build -t php-server .

# Then start your development container
docker run -p 80:80 --env-file ./.env -v $(pwd)/src:/var/www/html/ php-server

# And if you need to enter the container
docker exec -it <container_id> bash
```

Then you can open in your browser `http://localhost:8080`.

## Deployment

```bash
docker compose up -d --build
```

## Journal

### March 3, 2025

This project has a target server with CentOS 7 that reaches end-of-life on June 30th. This means it's impossible to update or install new software using the default sources because their repositories are offline. One solution is to use the CentOS vault or a mirror. In this case, I used a backup from AlmaLinux, a reliable project that provides a community-supported spiritual successor to CentOS compatible with RHEL, backed by several enterprises like AWS, Microsoft, and others.

To install software, we need to change the sources. I did this based on this tutorial:
https://almalinux.org/blog/2024-07-09-centos7-updates/

With this, you're able to update to the latest release for CentOS 7 and install new software, but the packages are limited to security updates only.

I feel more secure working with containers with more updated versions of the needed programs. It's also easier to move the entire project to a more secure and updated server. I assume this server is the only thing we have for now. I'm used to working with limited resources from my university days and first job, but the work needs to be done.

I installed Docker on the server, and I think it's ready to receive the code.

### March 4, 2025

I haven't touched PHP since my thesis project at university, but I think it's a good starting point. In my opinion, I prefer a strongly typed language over Python (though type hinting is possible but not mandatory in Python). Also, creating AI-assisted code with Python is easy, so I wanted a challenge (probably not the best idea for a house assessment).

Today I started creating the webpage and the first design. I kept it simple with a nice, dark style to avoid having a plain form with a white background burning your eyes.

After figuring out how to enable the PostgreSQL driver in my Docker PHP container, I set up my PostgreSQL container and started to plan how to import the data.

Finally, I tested if everything works on the remote server, and fortunately, it does! So I assume we can continue with development.

### March 5, 2025

When I tried to run PostgreSQL, I encountered a couple of blockers in the connection between the database and PHP. Fortunately, after a couple of hours of research and correctly configuring my Docker setup, I got it running. Now I have the database running on the server with PHP. The next step is to upload the almost 2GB file of information.

### March 6, 2025

Now that the environment is ready, I can focus on the data and calculations needed to achieve the task. The first step is to define the database structure based on the file. To accomplish this, I used Python/Pandas to analyze the data.

The data is contained in one large file, which I believe should be optimized in a relational style.

I must confess that I created the first Python script using Copilot and then modified it for my needs (I've never used Pandas before 🥲).

It's interesting how many attacks I received attempting to access the database and webpage.

Some key points:

VIN number: This is like a car's fingerprint, as no two vehicles in operation have the same number. It consists of 17 characters (digits and capital letters).

The CSV file has 4,713,914 records.

With that in mind, the following columns would be better identified as their own elements:

[columnName(unique_registry)]

Dealer information:
- dealer_name (58,073)
- dealer_street (58,072) (are there two dealers with the same address?)
- dealer_city (6,821)
- dealer_state (66)
- dealer_zip (15,665)
- seller_website (58,163)

Vehicle information:
- vin: unique identifier
- make (1,707)
- model (264,103): each model is associated with a maker
- trim (204,430)
- listing_price
- listing_mileage (number)
- used (bool)
- certified (bool)
- style (47,264) (string): looks like everyone has different ways to describe it
- driven_wheels (4,856) (string): similar to style
- engine (42,165) (string): non-standard
- fuel_type (1,617) (string): 'Essence' - is this real?
- exterior_color (44,303) (string)
- interior_color (37,803) (string)
- first_seen_date (624) date
- last_seen_date (5) date
- dealer_vdp_last_seen_date (456) date
- listing_status: [nan 'in_transit']

### March 9, 2025

After analyzing the data, the way to predict the price should be with linear regression. Using PostgreSQL functions to calculate the slope between the data points and the intersection with the Y-axis, it's possible to apply equations to predict the price.

If we add a mileage parameter, we search for all cars with values near the user's car mileage, between 80% to 120% of the price.

If the car is new (mileage 0), we take the average price from all the new cars in the list.

But if we don't specify mileage, we take all cars with similar characteristics.

We could also use other correlations to check the price, like location, because different zones could have different maintenance costs or more challenging road conditions.

I uploaded the data using Python, though I think I could use cleaner techniques as the data is extremely dirty.

Another technique would be to separate the database between entities like vehicles, sellers, locations, and with a lot of work, the status of the vehicle (colors, wheels, etc.).

It would be interesting to calculate correlations between car colors - maybe white cars have lower prices than black cars.

Finally, I added some quality-of-life characteristics like alerts, messages, context notes, and sanitization of inputs to prevent SQL injection.