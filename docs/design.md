# Design Document

Objectives

- Nice and simple UI
- Search page (main page)
- Results page

### Search Page

- Input 1 (required): Year + Make + Model (example: 2015 Toyota Corolla)

- Input 2 (optional): Mileage (example: 150,000 miles)


#### Actions

- The user enters the year and make.
- The user enters year, make and model.
- The user enters vehicle data and mileage.

### Results Page

- Display estimated market price computed based on listings for similar year + make + model vehicles
- Display a list of up to 100 sample listings that were used to compute the market value

| Vehicle               | Price   | Mileage       | Location   |
| --------------------- | ------- | ------------- | ---------- |
| 2015 Toyota Camry CE  | $12,500 | 131,400 miles | Seattle, WA |
| 2015 Toyota Camry CE  | $11,700 | 173,389 miles | Dallas, TX |
| 2015 Toyota Camry LE  | $11,100 | 131,839 miles | Newark, NJ |
| ...                   |         |               |            |

- Notes: Depending on the context, the user will receive a message. For example, if the car is new, the system will show the estimated price (the ideal new price for that kind of car) and additionally the average price for all the cars in the system with the same characteristics.

- Additionally, in notes, if the user enters the mileage, the system will search for similar cars within a window of 80% to 120% of the input mileage.
This will be reflected as a message with the upper and lower mileage range for the current set of cars with the same characteristics.

## System

- Running in Docker containers to easily transfer to more secure servers
- Using PHP and Apache for backend
- Simple frontend using HTML, CSS, and JavaScript
- Database using PostgreSQL running in a Docker container

The entire system can be easily transferred to different servers or to the cloud.

##  Search and Data

Using linear regression we need to predict the price of the vehicle based on the mileage and price of previous cars in our registry.

- The calculations are executed using the database management system PostgreSQL. This is hard to debug but faster (based on this [article](https://www.linkedin.com/pulse/pure-sql-10x-faster-than-php-use-queries-much-you-can-hadi-mirzaie-gwl6f/))
- Also PostgreSQL has built-in methods to calculate the slope and the intersection, making the work easier.
- Using intersection and slope, we could calculate the tangent line to calculate the estimated price of a vehicle.
- The group used for calculation includes all vehicles with the same characteristics of 'Year', 'Make' and 'Model' if they exist, because it doesn't make much sense to calculate with data from other cars and compare prices between, for example, a Toyota and a Lamborghini.

## Security

- The system was exposed to the internet from its launch and received numerous attacks by automated bots attempting to gain permissions. Therefore, it is important to set strong passwords and prevent SQL injection attacks. Several of these recommendations were implemented on the site to avoid security issues.

## Backend
- Developed using PHP 8 running in a docker container to have access to updates, isolation and security.

## Database

- I decided to use a modern implementation of a relational database, PostgreSQL. This helps us perform calculations, provides great performance for large datasets, and offers better security than MySQL.

| column          |  info               | 
| ---             |  ---                |
| id              |  PK (int) not null  |
| vin             | varchar(50)         |
| model_year      | integer             |
| manufacturer    | varchar(100)        |
| vehicle_model   | varcahr(255)        |
| vehicle_trim    | varchar(255)        | 
| listing_price   | numeric(15,2) null  | 
| listing_mileage | numeric(15,2) null  |
| is_certified    | bool                |
| is_used         | bool                |
| body_style      | varchar(100)        |
| driven_wheels   | varchar(50)         |
| engine          | varcahr(100)        |
| fuel_type       | varchar (50)        |
| exterior_color  | varchar (100)       |
| interior_color  | varchar(100)        |
| firt_seen_date  | varchar(50)         |
| last_seen_date  | varchar(50)         |
| listing_status  | varcahr(50) - null  |

- For the moment the DB only have one table with all the information, but for the next iteration, the idea is to split the information into entities like Vehicles, sellers, cities, car colors, wheel configurations, etc. As I mentioned above, this will make it easier to obtain new correlations to check variance in price, like price vs. color, price vs. city, or which companies have lower prices, if certified vehicles have better sell price... etc.

## Frontend
- For the frontend, it was written using simple HTML, CSS and JavaScript, with a minimalist and clean dark design.

## Data Flow

```
+-------+                 +----------+                  +----------+
| User  |                 | Backend  |                  | Database |
+-------+                 +----------+                  +----------+
  |                          |                             |
  | 1. Enter car info        |                             |
  |      & mileage           |                             |
  |------------------------->|                             |
  |                          |                             |
  |                          | 2. Clean data               |
  |                          |------------------------     |
  |                          |                       |     |
  |                          |<-----------------------     |
  |                          |                             |
  |                          | 3. Query for slope          |
  |                          |    & intersection           |
  |                          |---------------------------->|
  |                          |                             |
  |                          |  4. Return slope            |
  |                          |     & intersection          |
  |                          |<----------------------------|
  |                          |                             |
  |                          | 5. Calculate                |
  |                          |    estimated price          |
  |                          |------------------------     |
  |                          |                       |     |
  |                          |<-----------------------     |
  |                          |                             |
  |                          | 6. Query for                |
  |                          |    similar vehicles         |
  |                          |---------------------------->|
  |                          |                             |
  |                          | 7. Return matching          |
  |                          |    vehicles                 |
  |                          |<----------------------------|
  |                          |                             |
  |                          | 8. Format results           |
  |                          |------------------------     |
  |                          |                       |     |
  |                          |<-----------------------     |
  |                          |                             |
  |  9. Display price        |                             |
  |     estimate &           |                             |
  |     vehicle list         |                             |
  |<-------------------------|                             |
  |                          |                             |
```
