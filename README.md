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

### Call Example

> /survey_2?Annual_Base_Pay[gte]=50000&fields=Annual_Base_Pay,Public_Or_Private_Company&sort=Annual_Base_Pay

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

- id (int)
- Timestamp varchar(19)
- Age (int)
- Industry varchar(255)
- Job_Titlevarchar(255)
- Annual_Base_Pay(float)
- Currencyvarchar(255)
- Locationvarchar(255)
- Postcollege_Experience (int)
- Additionnal_Commentst (ext)
- Other_Currency varchar(255)


### survey_2

- id (int)
- Timestamp varchar(19)
- Employment_Type varchar(255)
- Company_Name varchar(255)
- Company_Size varchar(255)
- Country varchar(255)
- City varchar(255)
- Industry varchar(255)
- Public_Or_Private_Company varchar(255)
- Years_Of_Experience int
- Years_In_Company int
- Job_Title varchar(255)
- Job_Ladder varchar(255)
- Job_Level varchar(255)
- Required_Hours_Per_Week varchar(255)
- Actual_Hours_Per_Week varchar(255)
- Education_Level varchar(255)
- Annual_Base_Pay float
- Annual_Bonus float
- Annual_Stock_ValueBonus float
- Health_Insurance_Offered varchar(3)
- Annual_Weeks_Vacation int
- Satisfied varchar(255)
- Resign_In_Year varchar(255)
- Opinion_industry_direction text
- Gender varchar(255)
- Next_10_years_top_skill varchar(500)
- Done_Bootcamp varchar(255)



### survey_3
- id int
- Timestamp varchar(19)
- Company_Name varchar(255)
- Location varchar(255)
- Job_Title varchar(255)
- Years_In_Company int
- Years_Of_Experience int
- Annual_Base_Pay float
- Signing_Bonus float
- Annual_Bonus float
- Annual_Stock_ValueBonus float
- Gender varchar(255)
- Additional_Comments varchar(500)

