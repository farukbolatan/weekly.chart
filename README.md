WorkFlow
==================================
**Before Start**: 

# You need to follow the steps below.
* It was written in accordance with PHP 8.0 version.
* This project was prepared using Symfony 4.
* Run the following command within the project files ```composer.install```

# For database connection
Enter your database information into the ```.env``` file that will be created after the packages are installed.
Run the following queries in your database

 ```
##### business_charts table
CREATE TABLE `business_charts` (
	`id` int NOT NULL AUTO_INCREMENT,
	`name` varchar(255) DEFAULT NULL,
	`cook_time` int DEFAULT NULL,
	PRIMARY KEY (`id`)
);
#####

##### employees table
CREATE TABLE `employees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `work_speed` int DEFAULT NULL,
  PRIMARY KEY (`id`)
);
#####

INSERT INTO `your_db_name`.`employees` (`name`, `work_speed`) VALUES ('Omer', 1), ('Faruk',3), ('Sezer',2), ('Ahmet',2), ('Necla',1);
```
 
* You can view it by running the GetSchedule command in the project.
# Best regards

