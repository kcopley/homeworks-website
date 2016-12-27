-- cPanel mysql backup
GRANT USAGE ON *.* TO 'homewot5'@'localhost' IDENTIFIED BY PASSWORD '*4A6FE5E9638A99EF46090DF3D32FFFA8B1782E4E';
GRANT ALL PRIVILEGES ON `homewot5\_kctopspin`.* TO 'homewot5'@'localhost';
GRANT ALL PRIVILEGES ON `homewot5`.* TO 'homewot5'@'localhost';
GRANT ALL PRIVILEGES ON `homewot5\_cms`.* TO 'homewot5'@'localhost';
GRANT ALL PRIVILEGES ON `homewot5\_wpsite`.* TO 'homewot5'@'localhost';
GRANT ALL PRIVILEGES ON `homewot5\_%`.* TO 'homewot5'@'localhost';
GRANT USAGE ON *.* TO 'homewot5_admin'@'localhost' IDENTIFIED BY PASSWORD '*5117E7AF88F1BE79A10A9814CF8D1CAE480B71D2';
GRANT ALL PRIVILEGES ON `homewot5\_wpsite`.* TO 'homewot5_admin'@'localhost';
GRANT ALL PRIVILEGES ON `homewot5\_kctopspin`.* TO 'homewot5_admin'@'localhost';
