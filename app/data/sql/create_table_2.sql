CREATE TABLE `employee` (
  `Employee_Id` int NOT NULL AUTO_INCREMENT,
  `Job_Id` int NOT NULL,
  `Gender` varchar(255) NOT NULL,
  `Year_In_Company` int NOT NULL,
  `Year_Of_Experience` int NOT NULL,
  `Education_Level` varchar(255) NOT NULL,
  `Satisfied` varchar(255) NOT NULL,
  `Resign_In_Year` varchar(255) NOT NULL,
  `Opinion_Industry_Direction` varchar(255) NOT NULL,
  `Next 10 year top skill` varchar(500) NOT NULL,
  `Done_Bootcamp` varchar(255) NOT NULL,
  PRIMARY KEY (`Employee_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `company` (
  `Company_Id` int NOT NULL AUTO_INCREMENT,
  `Location_ID` int NOT NULL,
  `Company_Name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Company_Size` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Industry` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Public_Or_Private_Company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Health_Insurance_Offered` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`Company_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


CREATE TABLE IF NOT EXISTS `job` (
  `Job_Id` int NOT NULL AUTO_INCREMENT,
  `Company_Id` int NOT NULL,
  `Role_Id` int NOT NULL,
  `Employment_Type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Required_Hours_Per_Week` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Actual_Hours_Per_Week` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Annual_Base_Pay` float NOT NULL,
  `Annual_Bonus` float NOT NULL,
  `Annual_Stock_ValueBonus` float NOT NULL,
  `Annual_Weeks_Vacation` int NOT NULL,
  PRIMARY KEY (`Job_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `role` (
  `Role_Id` int NOT NULL AUTO_INCREMENT,
  `Job_Title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Job_Ladder` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Job_Level` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`Role_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `location` (
  `Location_Id` int NOT NULL AUTO_INCREMENT,
  `Country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `City` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`Location_Id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;