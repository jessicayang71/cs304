insert into card
values('4520010078928888', 0822);

insert into card
values('4520010078929999', 0924);

insert into card
values('8760432678906543', 0319);

insert into area
values('V6T1L9', 'Vancouver', 'BC');

insert into area
values('V7X1P4', 'Vancouver', 'BC');

insert into area
values('V6T1L8', 'Surrey', 'BC');

insert into area
values('V7A1E7', 'Richmond', 'BC');

insert into area
values('V6S0E3', 'Vancouver', 'BC');

insert into area
values ('V4P1G6', 'Surrey', 'BC');

insert into area
values ('V6Y2B6', 'Richmond', 'BC');

insert into area
values ('V6C1S4', 'Vancouver', 'BC');

insert into area
values ('V5T1R6', 'Vancouver', 'BC');

insert into client
values(1, 'Sam', 'Dover', 'UBC', '306 5725 Agronomy Rd.', 'V6T1L9', '6041234567');

insert into client
values(2, 'Allen', 'Gour', 'Deloitte', '2800 1055 Dunsmuir St.', 'V7X1P4', '7783217654');

insert into client
values(3, 'Ann', 'Cai', 'HSBC', '2459 Wesbrook St.', 'V6T1L8', '6047258371');

insert into client
values(4, 'Thomas', 'Lee', 'Hootsuite', '5 E 8th Ave', 'V5T1R6', '6046814668');

insert into salesrep 
values (1, 'Rebecca', 'Chen');

insert into salesrep
values(2, 'Jessica', 'Yang'); 

insert into premium 
values(1, 1000, 1);

insert into premium
values(2, 1500, 1);

insert into driver 
values (1, 'Ben', 'Hwang', 'Vancouver', 1200, 2000, 'Available');

insert into driver
values (2, 'Brendon', 'Chiang', 'Vancouver', 0800, 1200, 'Occupied');

insert into restaurant
values (1, 'Bao Down', '12 Powel Street', 'V7A1E7', 1100, 2100, 'Asian');

insert into restaurant
values (2, 'Freshii UBC', '3351 Wesbrook Village', 'V6S0E3', 0900, 2200, 'Casual');

insert into restaurant
values (3, 'Curry Sensation', '2828 152 Street', 'V4P1G6', 1100, 2200, 'Indian');

insert into restaurant
values (4, 'Cactus Club Cafe', '6551 No. 3 Road', 'V6Y2B6', 1130, 2330, 'Canadian');

insert into restaurant
values (5, 'Miku', '200 Granville Street', 'V6C1S4', 1130, 2130, 'Japanese');

insert into menu 
values (100, 1);

insert into menu 
values (200, 1);

insert into menu 
values (300, 1);

insert into menu
values (111, 2);

insert into menu
values (222, 2);

insert into menu
values (333, 3);

insert into menu
values (444, 3);

insert into menu
values (555, 3);

insert into menu 
values (666, 3);

insert into menu
values (110, 4);

insert into menu
values (220, 5);

insert into menuitem 
values (1, 100, 'Braised Pork Bun', 4.99, 'Lunch');

insert into menuitem 
values (2, 100, 'Chow Mein', 8.49, 'Lunch');

insert into menuitem 
values (3, 100, 'Shrimp Sring Roll', 6.37, 'Lunch');

insert into menuitem 
values (4, 200, 'Beef Rice Bowl', 8.99, 'Dinner');

insert into menuitem 
values (5, 200, 'Chicken Rice Bowl', 8.99, 'Dinner');

insert into menuitem 
values (6, 300, 'Milk Tea', 4.99, 'Drink');

insert into menuitem 
values (7, 300, 'Green Tea', 4.99, 'Drink');

insert into menuitem 
values (8, 111, 'Oaxaca Bowl', 10.49, 'Bowls');

insert into menuitem 
values (9, 111, 'Acai Bowl', 8.99, 'Bowls');

insert into menuitem 
values (10, 222, 'Chicken Burrito', 9.99, 'Burritos');

insert into menuitem 
values (11, 333, 'Cauliflower Curry', 9.50, 'Main');

insert into menuitem 
values (12, 333, 'Butter Chicken', 11.50, 'Main');

insert into menuitem 
values (13, 444, 'Caesar Salad', 7.99, 'Lunch');

insert into menuitem 
values (14, 444, 'Quinoa Salad', 7.99, 'Lunch');

insert into menuitem 
values (15, 555, 'Calamari', 12.99, 'Dinner');

insert into menuitem 
values (16, 555, 'French Fries', 3.99, 'Dinner');

insert into menuitem 
values (17, 555, 'Pasta', 14.25, 'Dinner');

insert into menuitem 
values (18, 666, 'Belini', 6.00, 'Drink');

insert into menuitem 
values (19, 666, 'Caesar', 6.00, 'Drink');

insert into menuitem 
values (20, 110, 'Chocolate Cake', 8.39, 'Dessert');

insert into menuitem 
values (21, 110, 'Cheesecake', 6.49, 'Dessert');

insert into menuitem 
values (22, 210, 'Salmon Oshi', 11.99, 'Dinner');

insert into menuitem 
values (23, 210, 'Beef Carpacio', 11.99, 'Dinner');

insert into orders 
values (1, '2017-11-13', '306 5725 Agronomy Rd.', 'V6T1L9', '2017-11-20', 1900, 'Delivered', 55.89, '4520010078928888', 1, 1, 1, 1);

insert into orders 
values (2, '2017-11-15', '306 5725 Agronomy Rd.', 'V6T1L9', '2017-12-25', 2000, 'Processing', 34.13, '4520010078928888', 1, 1, 1, NULL);

insert into orders 
values (3, '2017-11-17', '306 5725 Agronomy Rd.', 'V6T1L9', '2017-12-26', 1100, 'Processing', 26.86, '4520010078928888', 1, 2, 1, NULL);

insert into orders 
values (4, '2017-11-13', '306 5725 Agronomy Rd.', 'V6T1L9', '2017-11-20', 1500, 'Delivered', 55.89, '4520010078928888', NULL, 4, 1, 2);

insert into orders 
values (5, '2017-11-18', '306 5725 Agronomy Rd.', 'V6T1L9', '2017-11-19', 1730, 'Delivered', 37.59, '4520010078928888', 1, 4, 1, 2);

insert into orders 
values (6, '2017-11-13', '2800 1055 Dunsmuir St.', 'V7X1P4', '2017-11-20', 1500, 'Delivered', 23.52, '8760432678906543', 1, 3, 3, 1);

insert into orders 
values (7, '2017-11-14', '2459 Wesbrook St.', 'V6T1L8', '2017-11-21', 1300, 'Cancelled', 12.80, '4520010078929999', 1, 3, 2, 1);

insert into ordereditem
values (1, 1, 10);

insert into ordereditem
values (2, 1, 1);

insert into ordereditem
values (2, 2, 4);

insert into ordereditem
values (2, 3, 1);

insert into ordereditem
values (3, 8, 1);

insert into ordereditem
values (3, 9, 1);

insert into ordereditem
values (4, 20, 4);

insert into ordereditem
values (5, 20, 4);

insert into ordereditem
values (6, 11, 1);

insert into ordereditem
values (6, 12, 1);

insert into ordereditem
values (7, 12, 1);


commit;