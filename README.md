# Converteo Challenge 1

Hi! Here is my own implementation of a minimalist API for the Converteo Challenge 1. This is a simple Php application without CMS, only docker compose was used for the LAMP stack.

## Installation & Configuration

**[1]** First, clone the project
> git clone https://github.com/TomHalsik/Converteo_1.git

**[2]** If you have a LAMP stack, make sure your services are stopped.
> sudo service apache2 stop
> sudo service mysql stop

**[3]** Then build containers
> docker-compose build

**[4]** Setup database
Create Tables
> docker exec -i converteo_1_db_1 mysql -u root -ptest myDb  < app/data/sql/create_table.sql;

Populate Table
> docker exec -ti converteo_1_app_1 php scripts/populate.php

**[5]** Finally run docker containers
> docker-compose up



## Usage

[localhost:8001](localhost:8001) to access the web application
[localhost:8000](localhost:8000) to access PhPMyAdmin

## Api

### Endpoints
- [localhost:8001/survey_1](localhost:8001/survey_1)
- [localhost:8001/survey_2](localhost:8001/survey_2)
- [localhost:8001/survey_3](localhost:8001/survey_3)

### Filters

|Filter          |Usage                          |Example                      |
|----------------|-------------------------------|-----------------------------|
|Select          |`fields=[field,field]`         |`fields=id,Timestamp`        |
|Where           |`[field]=[value]`              |`Industry=Finance`           |
|Sort            |`sort=[field,field]`           |`sort=Timestamp,id`             |

### Operator

|Operator        |Usage                          |
|----------------|-------------------------------|
|\>=             |[gte]                          |
|<=              |[lte]                          |
|!>              |[ngt]                          |
|!<              |[nlt]                          |



## Tables

For more informations check tables in PhpMyAdmin ([localhost:8000](localhost:8000))

### survey_1

- id
- Timestamp
- Age
- Industry
- Job_Title
- Salary
- Currency
- Location
- Postcollege_Experience
- Additionnal_Comments
- Other_Currency

### survey_2
- id
- Timestamp
- Employment_Type
- Company_Name
- Company_Size
- Country
- City
- Industry
- Public_Or_Private
- Experience_In_Industry
- Experience_In_Company
- Job_Title
- Job_Ladder
- Job_Level
- Required_Hours_Per_Week
- Actual_Hours_Per_Week
- Education_Level
- Annual_Base_Pay
- Annual_Bonus
- Annual_Stock_ValueBonus
- Health_Insurance_Offered
- Annual_Week_Vacation
- Satisfied
- Resign_In_Year
- Opinion_industry_direction
- Gender
- Next_10_years_top_skill
- Done_bootcamp

### survey_3
- id
- Timestamp
- Employer
- Location
- Job_Title
- Years_at_Employer
- Years_of_Experience
- Annual_Base_Pay
- Signing_Bonus
- Annual_Bonus
- Annual_Stock_ValueBonus
- Gender
- Additional_Comments