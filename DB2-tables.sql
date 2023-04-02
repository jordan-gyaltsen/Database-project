
DROP TABLE IF EXISTS enroll;
DROP TABLE IF EXISTS assign;
DROP TABLE IF EXISTS material;
DROP TABLE IF EXISTS meetings;
DROP TABLE IF EXISTS time_slot;
DROP TABLE IF EXISTS groups;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS child_of;
DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS parents;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS member_of;

-- ----------------------------
-- Table structure for users
-- ----------------------------

CREATE TABLE users (
  id int(11),
  email varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  name varchar(255) NOT NULL,
  phone varchar(255) DEFAULT NULL,
  security_question varchar(255) NOT NULL,
  security_answer varchar(255) NOT NULL,
  PRIMARY KEY (id) 
);

INSERT INTO users (id, email, password, name, phone, security_question,security_answer)
VALUES 
(0, 'a@gmail.com',PASSWORD('a'), 'John','111-111-1111', 'sec_q_01', 'Cool'),

(1, 'b@gmail.com',PASSWORD('b'), 'Carl','111-111-1111', 'sec_q_01', 'Cool'),
(2, 'c@gmail.com',PASSWORD('c'), 'Bob','111-111-1111', 'sec_q_01', 'Cool'),
(3, 'd@gmail.com',PASSWORD('d'), 'Jim','111-111-1111', 'sec_q_01', 'Cool'),
(4, 'e@gmail.com',PASSWORD('e'), 'John','111-111-1111', 'sec_q_01', 'Cool'),
(5, 'f@gmail.com',PASSWORD('f'), 'Rem','111-111-1111', 'sec_q_01', 'Cool'),

(6, 'g@gmail.com',PASSWORD('g'), 'Phil','111-111-1111', 'sec_q_01', 'Cool'),
(7, 'h@gmail.com',PASSWORD('h'), 'Hum','111-111-1111', 'sec_q_01', 'Cool'),
(8, 'i@gmail.com',PASSWORD('i'), 'Ham','111-111-1111', 'sec_q_01', 'Cool'),
(9, 'j@gmail.com',PASSWORD('j'), 'Jimathy','111-111-1111', 'sec_q_01', 'Cool'),
(10, 'k@gmail.com',PASSWORD('k'), 'Duke','111-111-1111', 'sec_q_01', 'Cool');

-- ----------------------------
-- Table structure for parents
-- ----------------------------

CREATE TABLE parents (
  parent_id int(11),
  PRIMARY KEY (parent_id),
  FOREIGN KEY (parent_id) REFERENCES users (id) ON DELETE CASCADE
);

INSERT INTO parents (parent_id)
VALUES 
(6),
(7),
(8),
(9),
(10);

-- ----------------------------
-- Table structure for students
-- ----------------------------

CREATE TABLE students (
  student_id int(11),
  grade int(11),
  bday DATE NOT NULL,
  pronouns VARCHAR(255) NOT NULL,
  allergies VARCHAR(255) NOT NULL,
  PRIMARY KEY (student_id),
  FOREIGN KEY (student_id) REFERENCES users (id) ON DELETE CASCADE
);

INSERT INTO students (student_id,grade,bday,pronouns,allergies)
VALUES 
(1, 12,'2005-05-23','They/Them','Peanuts'),
(2, 12,'2005-05-23','Him/Her','Bagels'),
(3, 12,'2004-07-23','She/Him','Jello'),
(4, 12,'2005-05-23','Him/He','Peas'),
(5, 12,'2005-03-23','Car/Airplan','Bob');

-- ----------------------------
-- Table structure for child_of
-- ----------------------------

CREATE TABLE child_of (
  student_id int(11),
  parent_id int(11),
  PRIMARY KEY (student_id, parent_id),
  FOREIGN KEY (student_id) REFERENCES students (student_id),
  FOREIGN KEY (parent_id) REFERENCES parents (parent_id) 
);


INSERT INTO child_of (student_id,parent_id)
VALUES 
(2,6),
(4,7),
(2,8),
(2,7),
(1,9);

-- ----------------------------
-- Table structure for admins
-- ----------------------------

CREATE TABLE admins (
  admin_id int(11),
  PRIMARY KEY (admin_id),
  FOREIGN KEY (admin_id) REFERENCES users (id) ON DELETE CASCADE
);

INSERT INTO admins (admin_id)
VALUES 
(0);

-- ----------------------------
-- Table structure for groups
-- ----------------------------

CREATE TABLE groups (
  group_id int(11),
  name varchar(255) NOT NULL,
  description varchar(255),
  grade_req int(11),
  PRIMARY KEY (group_id)
);

INSERT INTO groups (group_id,name,description,grade_req)
VALUES 
(1, 'A','Dude for',12),
(2, 'B','Ham man',15),
(3, 'C','Top mop',3),
(4, 'D','Bill Bob',5),
(5, 'E','himhum',5);

-- ----------------------------
-- Table structure for time_slot (this table is from normalizaion)
-- ----------------------------

CREATE TABLE time_slot (
  time_slot_id int(11),
  day_of_the_week varchar(255) NOT NULL,
  start_time time NOT NULL,
  end_time time NOT NULL,
  PRIMARY KEY (time_slot_id)
);


INSERT INTO time_slot (time_slot_id,day_of_the_week,start_time,end_time)
VALUES 
(1, 'Wed','10:30:23','11:30:23'),
(2, 'Wed','10:30:23','11:30:23'),
(3, 'Wed','10:30:23','11:30:23'),
(4, 'Wed','10:30:23','11:30:23'),
(5, 'Wed','10:30:23','11:30:23');

-- ----------------------------
-- Table structure for meetings
-- ----------------------------

CREATE TABLE meetings (
  meeting_id int(11),
  meeting_name varchar(255) NOT NULL,
  date date NOT NULL,
  time_slot_id int(11) NOT NULL,
  capacity int(11) NOT NULL,
  group_id int(11) NOT NULL,
  announcement varchar(255),
  PRIMARY KEY (meeting_id),
  FOREIGN KEY (group_id) REFERENCES groups (group_id),
  FOREIGN KEY (time_slot_id) REFERENCES time_slot (time_slot_id)
);

INSERT INTO meetings (meeting_id,meeting_name,date,time_slot_id,capacity,group_id,announcement)
VALUES 
(1, 'A','2020-06-13',1,0,1,'ANNOUNCE'),
(2, 'B','2020-06-20',2,0,2,'This is an alert'),
(3, 'C','2023-10-22',3,0,3,'Yoyoyoy'),
(4, 'D','2023-06-24',4,0,1,'Emergency'),
(5, 'E','2023-10-22',5,0,2,'w');

-- ----------------------------
-- Table structure for material
-- ----------------------------

CREATE TABLE material (
  material_id int(11),
  meeting_id int(11),
  title varchar(255) NOT NULL,
  author varchar(255),
  type varchar(255),
  url varchar(255),
  notes varchar(255),
  assigned_date date NOT NULL,
  isbn varchar(255),
  PRIMARY KEY (material_id, meeting_id),
  FOREIGN KEY (meeting_id) REFERENCES meetings (meeting_id) ON DELETE CASCADE
);


INSERT INTO material (material_id,meeting_id,title,author,type,url,notes,assigned_date,isbn)
VALUES 
(1, 1,'Harry Potter','Rowling','Magic','agsag','Cool.com', '2020-06-23','978-0439708180'),
(2, 1,'The Cam','John','sdfadsa','agsag','Test', '2020-06-23','978-0439708180'),
(3, 2,'The Aee','Jim','sdfadsa','agsag','sgwegweshewrhewghewgewgewgew', '2020-06-23','978-0439708180'),
(4, 4,'The geg','String','sdfadsa','agsag','asfas', '2020-06-23','978-0439708180'),
(5, 2,'The Maen','String','sdfadsa','agsag','asf', '2020-06-23','978-0439708180');


-- ----------------------------
-- Table structure for enroll
-- ----------------------------

CREATE TABLE enroll (
  meeting_id int(11) NOT NULL,
  student_id int(11) NOT NULL,
  PRIMARY KEY (meeting_id, student_id),
  FOREIGN KEY (student_id) REFERENCES students (student_id),
  FOREIGN KEY (meeting_id) REFERENCES meetings (meeting_id)
);


INSERT INTO enroll (meeting_id,student_id)
VALUES 
(1, 2),
(2, 2),
(3, 3),
(3, 4),
(3, 2);

-- ------------------------------
-- Table structure for member_of
-- ------------------------------

CREATE TABLE member_of (
  group_id int(11) NOT NULL,
  student_id int(11) NOT NULL,
  PRIMARY KEY (group_id, student_id),
  FOREIGN KEY (student_id) REFERENCES students (student_id),
  FOREIGN KEY (group_id) REFERENCES groups (group_id)
);


INSERT INTO member_of (group_id,student_id)
VALUES 
(1, 2),
(2, 2),
(3, 3),
(3, 4),
(3, 2);



