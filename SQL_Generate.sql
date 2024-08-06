-- Drop the database if it exists and create it again
DROP DATABASE IF EXISTS php_group_project;
CREATE DATABASE IF NOT EXISTS php_group_project;
USE php_group_project;

-- Drop tables if they exist to start with a clean slate
DROP TABLE IF EXISTS orders_details, 
                     orders, 
                     cart, 
                     user_ids,
                     mobile;


-- Create the mobile table
CREATE TABLE mobile (
	 m_id 			INT 			NOT NULL,
	 brand 			VARCHAR(30) 	NOT NULL,
	 model_name 	VARCHAR(30) 	NOT NULL,
	 display_size 	FLOAT 			DEFAULT NULL,
     price          FLOAT          NOT NULL,
	 color 			VARCHAR(15) 	DEFAULT NULL,
	 storage 		FLOAT 			DEFAULT NULL,
	 ram 			INT 		 	NOT NULL,
     availability   INT            NOT NULL,
	 PRIMARY KEY (m_id)
);

-- Insert data into the mobile table
INSERT INTO mobile VALUES (1191, 'SAMSUNG', 'A10E', 5.5,800, 'Blue', 32, 4,300),
                          (3322, 'OPPO', 'F1S', 5.0,500,'Black', 64, 5,400),
                          (4634, 'XIAOMI', 'Redmi K30', 6.0,350, 'Red', 128, 6.5,500),
                          (7614, 'SONY', 'Xperia', 4.5, 'Green',700, 64, 5.5,600),
					      (1192, 'ONEPLUS', 'NORD', 5.5,350, 'Blue', 32, 4,300);
-- Create the user_ids table
CREATE TABLE user_ids(
	 u_id 			INT 			NOT NULL,
	 fname		 	VARCHAR(30) 	NOT NULL,
	 lname 			VARCHAR(30) 	NOT NULL,
	 email		 	VARCHAR(30) 	NOT NULL,
	 user_name		VARCHAR(30) 	NOT NULL,
     address 		VARCHAR(90) 	NOT NULL,
     phone_no 		VARCHAR(10) 	NOT NULL,
     password 		VARCHAR(20) 	DEFAULT NULL,
	 PRIMARY KEY (u_id),
	 UNIQUE KEY user_name (user_name),
     UNIQUE KEY email (email)
);

-- Insert data into the user_ids table
INSERT INTO user_ids VALUES (45215, 'John', 'Smith', 'johnsmith@gmail.com', 'johnsmith25', '428 Harvey Drive, Auburn, NY 13021', '6142574125', 'xxx3321'),
                            (25142, 'Paul', 'Howell', 'paulhowl@gmail.com', 'paulhoul1', '9257 Edgewood St. Greensboro, NC 27405', '9178452142', 'yyy2145'),
                            (14521, 'Dave', 'Brown', 'davebrown@gmail.com', 'davebr34', '508 Central Ave. Klamath Falls, OR 97603', '2564154215', 'lakhjsn'),
                            (78451, 'Allan', 'Henry', 'allanhenry@gmail.com', 'allenhy65', '148 S. Ketch Harbour Dr. Revere, MA 02151', '5421542355', 'lll4521');

-- Create the cart table
CREATE TABLE cart(
	 quantity 	INT 	NOT NULL,
	 m_id		INT 	NOT NULL,
	 u_id		INT 	NOT NULL,
	 KEY m_id (m_id),
	 KEY u_id (u_id),
	 CONSTRAINT p_idcfk FOREIGN KEY (m_id) REFERENCES mobile (m_id) ON DELETE CASCADE,
     CONSTRAINT u_idufk FOREIGN KEY (u_id) REFERENCES user_ids (u_id) ON DELETE CASCADE
);

-- Create the orders table
CREATE TABLE orders(
	 order_date DATE    NOT NULL,
	 quantity 	INT 	NOT NULL,
	 m_id		INT 	NOT NULL,
	 u_id		INT 	NOT NULL,
     o_id       INT     NOT NULL,
     PRIMARY KEY (o_id, m_id),
	 KEY m_id (m_id),
	 KEY u_id (u_id),
	 CONSTRAINT p_idofk FOREIGN KEY (m_id) REFERENCES mobile (m_id) ON DELETE CASCADE,
     CONSTRAINT u_idofk FOREIGN KEY (u_id) REFERENCES user_ids (u_id) ON DELETE CASCADE
);



-- Create the orders_details table
CREATE TABLE orders_details(
	 m_id			INT 			NOT NULL,
     o_id       	INT     		NOT NULL,
     p_name     	VARCHAR(30)     NOT NULL,
     price 			FLOAT     		NOT NULL,
	 order_date 	DATE    		NOT NULL,
     quantity 		INT 			NOT NULL,
	 u_id			INT 			NOT NULL,
     PRIMARY KEY (o_id, m_id),
	 KEY m_id (m_id),
	 KEY u_id (u_id),
     CONSTRAINT o_idodfk FOREIGN KEY (o_id, m_id) REFERENCES orders (o_id, m_id) ON DELETE CASCADE,
	 CONSTRAINT p_idodfk FOREIGN KEY (m_id) REFERENCES mobile (m_id) ON DELETE CASCADE,
     CONSTRAINT u_idodfk FOREIGN KEY (u_id) REFERENCES user_ids (u_id) ON DELETE CASCADE
);
