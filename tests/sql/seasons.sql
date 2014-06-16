CREATE TABLE `seasons` (
  "season_id" int NOT NULL PRIMARY KEY,
  "competition_id" int unsigned DEFAULT NULL,
  "name" varchar(20) NOT NULL DEFAULT '',
  "winner_team_id" int unsigned DEFAULT NULL,
  "start_date" date DEFAULT NULL,
  "end_date" date DEFAULT NULL
);

INSERT INTO `seasons` VALUES(614, 72, '1930 Uruguay', 2300, '1930-07-13', '1930-07-30');
INSERT INTO `seasons` VALUES(613, 72, '1934 Italy', 1318, '1934-05-27', '1934-06-10');
INSERT INTO `seasons` VALUES(612, 72, '1938 France', 1318, '1938-06-04', '1938-06-19');
INSERT INTO `seasons` VALUES(611, 72, '1950 Brazil', 2300, '1950-06-24', '1950-07-16');
INSERT INTO `seasons` VALUES(610, 72, '1954 Switzerland', 2825, '1954-06-16', '1954-07-04');
INSERT INTO `seasons` VALUES(609, 72, '1958 Sweden', 349, '1958-06-08', '1958-06-29');
INSERT INTO `seasons` VALUES(608, 72, '1962 Chile', 349, '1962-05-30', '1962-06-17');
INSERT INTO `seasons` VALUES(607, 72, '1966 England', 774, '1966-07-11', '1966-07-30');
INSERT INTO `seasons` VALUES(606, 72, '1970 Mexico', 349, '1970-05-31', '1970-06-21');
INSERT INTO `seasons` VALUES(605, 72, '1974 Germany', 2825, '1974-06-13', '1974-07-07');
INSERT INTO `seasons` VALUES(604, 72, '1978 Argentina', 132, '1978-06-01', '1978-06-25');
INSERT INTO `seasons` VALUES(603, 72, '1982 Spain', 1318, '1982-06-13', '1982-07-11');
INSERT INTO `seasons` VALUES(602, 72, '1986 Mexico', 132, '1986-05-31', '1986-06-29');
INSERT INTO `seasons` VALUES(601, 72, '1990 Italy', 2825, '1990-06-08', '1990-07-08');
INSERT INTO `seasons` VALUES(600, 72, '1994 USA', 349, '1994-06-17', '1994-07-17');
INSERT INTO `seasons` VALUES(599, 72, '1998 France', 944, '1998-06-10', '1998-07-12');
