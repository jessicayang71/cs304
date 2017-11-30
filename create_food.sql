drop table orders;
drop table card; 
drop table area;
drop table ordereditem;
drop table menuitem;
drop table menu;
drop table restaurant;
drop table driver;
drop table premium;
drop table salesrep;
drop table client;

CREATE TABLE client (
	cid integer NOT NULL,
	cfname varchar(30) NOT NULL,
	clname varchar(30) NOT NULL,
	ccompany varchar(30) NOT NULL, 
	street varchar(30) NOT NULL,
	postal varchar(6) NOT NULL,
	cphone varchar(10) NOT NULL,
	PRIMARY KEY (cid)
	);

CREATE TABLE salesrep (
	sid integer NOT NULL,
	sfname varchar(30) NOT NULL,
	slname varchar(30) NOT NULL,
	PRIMARY KEY (sid)
	); 

CREATE TABLE premium (
	cid integer NOT NULL,
	points integer NOT NULL,
	sid integer NOT NULL,
	PRIMARY KEY (cid),
	FOREIGN KEY (cid) REFERENCES client(cid)
		ON DELETE CASCADE,
	FOREIGN KEY (sid) REFERENCES salesrep(sid)
		ON DELETE SET NULL 
	);

CREATE TABLE driver (
	did integer NOT NULL,
	dfname varchar(30) NOT NULL,
	dlname varchar(30) NOT NULL,
	dlocation varchar(30) NOT NULL,				/* location that driver is situated */
	startshift integer NOT NULL,
	endshift integer NOT NULL,					
	dstatus varchar(30) NOT NULL,				/* either available or busy */
	PRIMARY KEY (did)
	);

CREATE TABLE restaurant (
	rid integer NOT NULL,
	rname varchar(30) NOT NULL,
	street varchar(30) NOT NULL,
	postal varchar(6) NOT NULL,
	opentime integer NOT NULL,
	closetime integer NOT NULL,
	cuisinetype varchar(30) NOT NULL,
	PRIMARY KEY (rid)
	);

CREATE TABLE menu (
	mid integer NOT NULL,
	rid integer NOT NULL,
	PRIMARY KEY (mid),
	FOREIGN KEY (rid) REFERENCES restaurant(rid)
		ON DELETE CASCADE
	);

CREATE TABLE menuitem (
	iid integer NOT NULL,
	mid integer NOT NULL,
	iname varchar(30) NOT NULL,
	iprice float(10) NOT NULL,
	icategory varchar(30) NOT NULL,				/* menu category */
	PRIMARY KEY (iid)
	);

CREATE TABLE ordereditem (
	ono integer NOT NULL,
	iid integer NOT NULL,
	qty integer NOT NULL,
	PRIMARY KEY (ono, iid),
	FOREIGN KEY (iid) REFERENCES menuitem(iid)
		ON DELETE CASCADE
	);

CREATE TABLE area (								/* decomposed table */
	postal char(6) NOT NULL,
	city varchar(30) NOT NULL,
	province varchar(2) NOT NULL,
	PRIMARY KEY(postal)
	);

CREATE TABLE card (								/* decomposed table */
	cardNo char(16) NOT NULL,
	expdate number NOT NULL,
	PRIMARY KEY(cardNo)
	);

CREATE TABLE orders (
	ono integer NOT NULL,						
	placeddate date NOT NULL,					/* date that order was placed */
	street varchar(30) NOT NULL,				/* deliver to this address */
	postal char(6) NOT NULL,					
	deliverdate date NOT NULL,					/* date for delivery to be made */
	delivertime integer NOT NULL,				/* expected delivery time */
	ostatus varchar(30) NULL,					/* status of order */
	oamount float(10) NOT NULL,					/* dollar amount spent */	
	cardno char(16) NOT NULL,					/* card used to make order */
	sid integer NULL,
	rid integer NOT NULL,
	cid integer NOT NULL,
	did integer NULL,
	PRIMARY KEY (ono),
	FOREIGN KEY (sid) REFERENCES salesrep(sid)
		ON DELETE CASCADE,
	FOREIGN KEY (rid) REFERENCES restaurant(rid)
		ON DELETE CASCADE,
	FOREIGN KEY (cid) REFERENCES client(cid)
		ON DELETE CASCADE,
	FOREIGN KEY (did) REFERENCES driver(did)
		ON DELETE CASCADE
	);

commit;