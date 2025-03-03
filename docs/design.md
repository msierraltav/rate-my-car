# Design Document

Objectives

- Nice a simple UI
- Search page ( main page )
- results page

### Search Page

- input 1 ( required ) : Year + Make + Model ( example : 2015 Toyota Corolla)

- input 2 ( optional) : Mileage ( example : 150.000 miles)

### Results Page

- Display estimated market price computed based on listing for similar year + make + model vehicles
- Display a list of up 100 sample listing that were used to compute the market value

|-----------------------|---------|---------------|------------|
| Vehicle               | Price   | Mileage       | Location   |
|-----------------------|---------|---------------|------------|
| 2015 Toyota Camry CE  | $12.500 | 131.400 miles | Seatle, WA |
|-----------------------|---------|---------------|------------|
| 2015 Toyota Camry CE  | $11.700 | 173.389 miles | Dallas, TX |
|-----------------------|---------|---------------|------------|
| 2015 Toyota Camrt LE  | $11.100 | 131.839 miles | Newark, NJ |
|-----------------------|---------|---------------|------------|
| ...                   |         |               |            |
|-----------------------|---------|---------------|------------|

##  Search and Data

