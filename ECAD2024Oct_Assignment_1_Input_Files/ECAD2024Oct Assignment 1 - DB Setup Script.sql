--  Module: ECAD                                        
--  Database Script for setting up the MySQL database   
--  required for ECAD Assignment.             
--  Creation Date: 03 Jan 2025. 

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- Delete tables before creating   
DROP TABLE IF EXISTS GST;
DROP TABLE IF EXISTS OrderData;
DROP TABLE IF EXISTS ShopCartItem;
DROP TABLE IF EXISTS ShopCart;
DROP TABLE IF EXISTS CatProduct;
DROP TABLE IF EXISTS ProductSpec;
DROP TABLE IF EXISTS Specification;
DROP TABLE IF EXISTS Product;
DROP TABLE IF EXISTS Category;
DROP TABLE IF EXISTS Feedback;
DROP TABLE IF EXISTS Shopper;


-- Create the tables 

CREATE TABLE Shopper 
(
  ShopperID INT(4) NOT NULL AUTO_INCREMENT,
  Name VARCHAR(50) NOT NULL,
  BirthDate DATE DEFAULT NULL,
  Address VARCHAR(150) DEFAULT NULL,
  Country VARCHAR(50) DEFAULT NULL,
  Phone VARCHAR(20) DEFAULT NULL,
  Email VARCHAR(50) NOT NULL,
  Password VARCHAR(20) NOT NULL,
  PwdQuestion VARCHAR(100) DEFAULT NULL,
  PwdAnswer VARCHAR(50) DEFAULT NULL,
  ActiveStatus INT(4) NOT NULL DEFAULT 1,
  DateEntered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopperID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Feedback
(
  FeedBackID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  Subject VARCHAR(255) NULL,
  Content LONGTEXT NULL,
  Rank INT(4) DEFAULT NULL,
  DateTimeCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (FeedBackID),
  FOREIGN KEY fk_Feedback_Shopper(ShopperID) REFERENCES Shopper(ShopperID)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Category 
(
  CategoryID INT(4) NOT NULL AUTO_INCREMENT,
  CatName VARCHAR(255) DEFAULT NULL,
  CatDesc LONGTEXT DEFAULT NULL,
  CatImage VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (CategoryID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Product 
(
  ProductID INT(4) NOT NULL AUTO_INCREMENT,
  ProductTitle VARCHAR(255) DEFAULT NULL,
  ProductDesc LONGTEXT DEFAULT NULL,
  ProductImage VARCHAR(255) DEFAULT NULL,
  Price DOUBLE NOT NULL DEFAULT 0.0,
  Quantity INT(4) NOT NULL DEFAULT 0,
  Offered INT(4) NOT NULL DEFAULT 0,
  OfferedPrice DOUBLE DEFAULT NULL,
  OfferStartDate DATE DEFAULT NULL,
  OfferEndDate DATE DEFAULT NULL,
  PRIMARY KEY (ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE Specification
(
  SpecID INT(4) NOT NULL AUTO_INCREMENT,
  SpecName VARCHAR(64) DEFAULT NULL,
  PRIMARY KEY (SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ProductSpec 
(
  ProductID INT(4) NOT NULL,
  SpecID INT(4) NOT NULL,
  SpecVal VARCHAR(255) DEFAULT NULL,
  Priority INT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (ProductID, SpecID),
  FOREIGN KEY fk_PS_Product(ProductID) REFERENCES Product(ProductID),
  FOREIGN KEY fk_PS_Specification(SpecID) REFERENCES Specification(SpecID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE CatProduct 
(
  CategoryID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  PRIMARY KEY (CategoryID, ProductID),
  FOREIGN KEY fk_CP_Category(CategoryID) REFERENCES Category(CategoryID),
  FOREIGN KEY fk_CP_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ShopCart
(
  ShopCartID INT(4) NOT NULL AUTO_INCREMENT,
  ShopperID INT(4) NOT NULL,
  OrderPlaced INT(4) NOT NULL DEFAULT 0,
  Quantity INT(4) DEFAULT NULL,  
  SubTotal DOUBLE DEFAULT NULL,
  Tax DOUBLE DEFAULT NULL,
  ShipCharge DOUBLE DEFAULT NULL,
  Discount DOUBLE NOT NULL DEFAULT 0.0,
  Total DOUBLE DEFAULT NULL,
  DateCreated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (ShopCartID),
  FOREIGN KEY fk_SC_Shopper(ShopperID) REFERENCES Shopper(ShopperID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE ShopCartItem 
(
  ShopCartID INT(4) NOT NULL,
  ProductID INT(4) NOT NULL,
  Price DOUBLE NOT NULL,
  Name VARCHAR(255) NOT NULL,
  Quantity INT(4) NOT NULL,
  PRIMARY KEY (ShopCartID, ProductID),
  FOREIGN KEY fk_SCI_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID),
  FOREIGN KEY fk_SCI_Product(ProductID) REFERENCES Product(ProductID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE OrderData
(
  OrderID INT(4) NOT NULL AUTO_INCREMENT,
  ShopCartID INT(4) NOT NULL,
  ShipName VARCHAR(50) NOT NULL,
  ShipAddress VARCHAR(150) NOT NULL,
  ShipCountry VARCHAR(50) NOT NULL,
  ShipPhone VARCHAR(20) DEFAULT NULL,
  ShipEmail VARCHAR(50) DEFAULT NULL,
  BillName VARCHAR(50) NOT NULL,
  BillAddress VARCHAR(150) NOT NULL,
  BillCountry VARCHAR(50) NOT NULL,
  BillPhone VARCHAR(20) DEFAULT NULL,
  BillEmail VARCHAR(50) DEFAULT NULL,
  DeliveryDate DATE DEFAULT NULL,
  DeliveryTime VARCHAR(50) DEFAULT NULL,
  DeliveryMode VARCHAR(50) DEFAULT NULL,
  Message VARCHAR(255) DEFAULT NULL,
  OrderStatus INT(4) NOT NULL DEFAULT 1,
  DateOrdered TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (OrderID),
  FOREIGN KEY fk_Order_ShopCart(ShopCartID) REFERENCES ShopCart(ShopCartID)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE GST 
(
  GstId INT(4) NOT NULL AUTO_INCREMENT,
  EffectiveDate DATE NOT NULL,
  TaxRate DOUBLE NOT NULL,
  PRIMARY KEY (GstId)
)ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- Load the tables with sample data  

-- Shoppers
insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('James Ecader','1970-01-01','School of Infocomm Technology, Ngee Ann Polytechnic','Singapore','(65) 64601234','ecader@np.edu.sg','ecader','Which polytechnic?','Ngee Ann', 1, '2013-01-01 10:05:30' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('Peter Tan','1977-05-15','Blk 108, Hougang Ave 1, #04-04','Singapore','(65) 62881111','PeterTan@hotmail.com','PeterTan','wife''s name?','Lucy', 0, '2013-01-01 15:35:20' );

insert into Shopper(Name, BirthDate, Address, Country, Phone, EMail, Password, PwdQuestion, PwdAnswer, ActiveStatus, DateEntered) 
values('Mary Mai','1982-08-09','123, Sunset Way, Spore 555123','Singapore','(65) 62881111','MaryMai@yahoo.com','MaryMai','How many brothers and sisters?','0', 1, '2012-05-01 09:45:23' );


-- Categories
insert into Category(CatName, CatDesc, CatImage) 
values('Baby Clothing','The stylish designs reflect fashionable mum and cute baby.','Baby_Clothing.jpg');

insert into Category(CatName, CatDesc, CatImage) 
values('Baby Gear','Our baby gear provides all you need to transport, entertain, and feed your baby comfortably and safely.','Baby_Gear.jpg');

insert into Category(CatName, CatDesc, CatImage) 
values('Bathing and Grooming','Our products feature highly in safety to provide peace of mind.','Bathing_and_Grooming.jpg');

-- Specifications 
insert into Specification(SpecName) values('Age Range');
insert into Specification(SpecName) values('Material');
insert into Specification(SpecName) values('Colour');
insert into Specification(SpecName) values('Gender');
insert into Specification(SpecName) values('Size');
insert into Specification(SpecName) values('Weight');
insert into Specification(SpecName) values('Capacity');


-- Products
insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Panda Baby Romper', 'Casual clothes outfit. Round neck, short sleeve infant toddler jumpsuit, with hat.',
'Panda_Romper.jpg', 13.90, 200);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Cocotina Rose Flower Print Petti Ruffle Romper', 'One-piece fancy playsuit with Strap. Bow Decor.',
'Cocotina_Rose_Flower_Petti_Ruffle_Romper.jpg', 13.40, 300, 1, 8.90, '2024-12-01', '2024-12-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Sanwood Baby Toddler Anti-Slip Sandal', 'Little feet need protection. Our soft and flexible pre-walker shoes slip on easily. Provide a snug and secure fit without restricting movement.',
'Sanwood_Baby_Toddler_AntiSlip_Sandal.jpg', 24.70, 300, 1, 7.40, '2025-01-01', '2025-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Joovy Caboose Ultralight Stand-On Tandem Stroller', 'The new Caboose Ultralight is the lightest, most maneuverable tandem stroller available on the market today. It can navigate narrow spaces with ease. The large, sealed ball bearing wheels give your children a smooth ride and you’ll find it is super easy to push. Thanks to its minimal weight, you can steer with just one hand!',
'Joovy_Caboose_Ultralight_Stand-On_Tandem_Stroller.jpg', 504.00, 100);

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Fisher-Price Newborn to Toddler Portable Rocker', 'The perfect stationary seat or rocker for even the youngest babies to play, eat or rest! Easy adjustments let you convert it from a rocker to stationary seat with three position recline. Overhead toys provide bat-at play. When it is time to relax, switch on soothing vibrations. Calming vibrations and a reclining seat help soothe.',
'Fisher-Price_Portable_Rocker.jpg', 170.00, 100, 1, 129.90, '2025-01-01', '2025-01-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Chicco Band Baby Walker', 'The Band is a baby walker that can be adjusted in height to suit the build of different children, and ensure their feet touch the floor correctly; It also has six safety brakes, which stop the structure when it reaches a step. The well-padded seat and rigid backrest offers maximum support to baby. Chicco Band also has an electronic play panel with lots of activities, coloured lights and fun music. The toy can be removed, to reveal a useful play or food tray, then using the straps provided the toy can be fixed to a stroller bumper bar.',
'Chicco_Band_Baby_Walker.jpg', 130.00, 5, 1, 99.00, '2025-01-01', '2025-03-01');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('Puku Mini Bath Tub', 'Help baby to take a bath. The baby is happy and the mother feels secure too. Non-slip handle convenient for moving around. Soft cushion to protect baby\'s head. Drain plug design Non-slip pad avoids bathtub slipping.',
'Puku_Mini_Bath_Tub.jpg', 38.00, 100, 1, 29.90, '2025-03-01', '2025-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity, Offered, OfferedPrice, OfferStartDate, OfferEndDate) 
values('California Baby Shampoo And Bodywash - Calming', 'California Baby Calming Shampoo & Bodywash gently and effectively cleans hair, body and face using natural vegetable glucoside cleansers that are non-stripping, sulfate-free and extremely biodegradable. This must-have staple, is lightly scented with California Baby\'s light and fresh Calming essential oil blend that helps to promote calmness, making bath-time stress free and fun! All of our Shampoo & Bodywashes are allergy-tested, tear-free, non-drying and leave hair and skin soft, shiny and manageable.',
'California_Baby_Shampoo_And_Bodywash-Calming.jpg', 28.00, 2, 1, 22.90, '2025-01-01', '2025-03-31');

insert into Product(ProductTitle, ProductDesc, ProductImage, Price, Quantity) 
values('Konicare Minyak Telon', 'Konicare Minyak Telon oil is made of natural ingredients that can relieve pain or itching on the skin. It help to keep baby warm, prevent colic and protect your little one from mosquito bites with our lovely lavender fragrant telon oil.',
'Konicare_Minyak_Telon.jpg', 12.00, 0);


-- Product Specifications
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(1, 1, 'Suitable for 3-6 months babies', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(1, 2, '100% Cotton', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(1, 3, 'White, Gray, Black', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(1, 4, 'Unisex', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 1, 'Suitable for 6-12 months babies', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 2, 'Satin lace', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 3, 'Pink', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(2, 4, 'Girl', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 1, 'Suitable for 12-18 months babies', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 2, 'Coral fleece and mircofabric', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 3, 'White', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 4, 'Girl', 4);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(3, 5, 'Outer Sole Length: 13 cm, Sole Width: 6cm', 5);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(4, 1, 'Suitable for baby/toddler of 2-month to 3-year old', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(4, 3, 'Red and Black', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(4, 5, 'Height: 41.50in, Width: 21.5in, Length: 37in', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(4, 6, '22.1 pounds', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(5, 1, 'Use from newborn to toddler — up to 18 kg', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(5, 3, 'Light green', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(5, 5, '55.25cm x 11.43cm x 40.64cm (L x W x H)', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(5, 6, '4.8kg', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(6, 1, 'Suitable for babies who can sit unaided from 6 months', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(6, 3, 'Turquoise Blue', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(6, 5, '90cm x 63cm x 50cm (L x W x H)', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(6, 6, '3.5kg', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 1, 'For baby from newborn to less than 75 cm height', 1);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 3, 'Pink', 2);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 5, '45cm x 45cm x 45cm (L x W x H)', 3);
insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(7, 6, '1.8kg', 4);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(8, 7, '8.5oz', 1);

insert into ProductSpec(ProductID, SpecID, SpecVal,Priority) 
values(9, 7, '125ml', 1);

-- Products' Category 
insert into CatProduct(CategoryID, ProductID) values(1,1);
insert into CatProduct(CategoryID, ProductID) values(1,2);
insert into CatProduct(CategoryID, ProductID) values(1,3);
insert into CatProduct(CategoryID, ProductID) values(2,4);
insert into CatProduct(CategoryID, ProductID) values(2,5);
insert into CatProduct(CategoryID, ProductID) values(2,6);
insert into CatProduct(CategoryID, ProductID) values(3,7);
insert into CatProduct(CategoryID, ProductID) values(3,8);
insert into CatProduct(CategoryID, ProductID) values(3,9);


-- Shopping Cart 
insert into ShopCart (ShopperId, OrderPlaced, Quantity, Subtotal, Tax, ShipCharge, Discount, Total, DateCreated)
values(1, 1, 2, 27.80, 1.95, 5.00, 0.00, 34.75,'2024-12-20 09:56:30');


-- Shopping Cart Items 
insert into ShopCartItem(ShopCartId, ProductId, Name, Price, Quantity) 
values(1, 1, 'Panda Baby Romper', 13.90, 2);


-- Order Data 
insert into OrderData(ShopCartId,ShipName,ShipAddress,ShipCountry,ShipPhone,ShipEmail,
BillName,BillAddress,BillCountry,BillPhone,BillEmail,DeliveryDate, DeliveryMode,
Message, OrderStatus,DateOrdered) 
values(1, 'Jenny Lai', 'Blk 222, Ang Mo Kio Ave 1, #12-12, S(560222)', 'Singapore', '(65) 63447777', 'JennyLai@yahoo.com.sg', 
'James Ecader', 'School of InfoComm Technology, Ngee Ann Polytechnic', 'Singapore','(65) 64601234', 'ecader@np.edu.sg', '2024-12-22', 'Normal',
'Merry Christmas!', 3, '2024-12-20 10:01:35');


-- Feedback 
insert into Feedback(ShopperID, Subject, Content, Rank, DateTimeCreated)
values(1, 'Feebdack about the service', 'The website provides helpful information. Fast in delivery goods.', 4, '2024-12-23 09:50:30');


-- GST 
insert into GST(EffectiveDate, TaxRate) values ('2004-01-01',5.0);
insert into GST(EffectiveDate, TaxRate) values ('2007-07-01',7.0);
insert into GST(EffectiveDate, TaxRate) values ('2023-01-01',8.0);
insert into GST(EffectiveDate, TaxRate) values ('2024-01-01',9.0);
insert into GST(EffectiveDate, TaxRate) values ('2030-01-01',10.0);