INSERT INTO country VALUES ('BC','CA');
INSERT INTO country VALUES ('ON','CA');
INSERT INTO country VALUES ('MB','CA');
INSERT INTO country VALUES ('WA','US');
INSERT INTO country VALUES ('OR','US');
INSERT INTO country VALUES ('CA','US');
INSERT INTO country VALUES ('VA','US');

INSERT INTO city VALUES ('Vancouver','BC');
INSERT INTO city VALUES ('Vancouver','OR');
INSERT INTO city VALUES ('North Vancouver','BC');
INSERT INTO city VALUES ('Victoria','BC');
INSERT INTO city VALUES ('Burnaby','BC');
INSERT INTO city VALUES ('Richmond','BC');
INSERT INTO city VALUES ('Richmond','VA');

INSERT INTO location VALUES ('UBC','Vancouver','BC');
INSERT INTO location VALUES ('Rogers Arena','Vancouver','BC');
INSERT INTO location VALUES ('Main Street','Vancouver','BC');
INSERT INTO location VALUES ('Main Street','North Vancouver','BC');
INSERT INTO location VALUES ('Metrotown','Burnaby','BC');
INSERT INTO location VALUES ('Aberdeen','Richmond','BC');

INSERT INTO permissions VALUE ('regular',FALSE,FALSE);
INSERT INTO permissions VALUE ('premium',TRUE,FALSE);
INSERT INTO permissions VALUE ('deluxe',TRUE,TRUE);

INSERT INTO account VALUES ('alpha','alpha@example.com','amber','Alpha','Vancouver','BC',200,'Deluxe');
INSERT INTO account VALUES ('bravo','bravo@example.com','bronze','Bravo','Victoria','BC',153,'Premium');
INSERT INTO account VALUES ('charlie','charlie@example.com','coral','Charlie','Vancouver','OR',2000,'Regular');
INSERT INTO account VALUES ('delta','delta@example.com','denim',NULL,NULL,'BC',0,'Regular');
INSERT INTO account VALUES ('echo','echo@example.com','emerald',NULL,NULL,NULL,0,'Regular');

INSERT INTO account_upgrade VALUES (1,'Double Like','Gives 2 coins every time you like a post or comment.',200);
INSERT INTO account_upgrade VALUES (2,'Triple Like','Gives 3 coins every time you like a post or comment.',300);
INSERT INTO account_upgrade VALUES (3,'Double Dislike','For when you''re extra angry.',200);
INSERT INTO account_upgrade VALUES (4,'Badge','A shiny badge to put on your profile.',500);
INSERT INTO account_upgrade VALUES (5,'Star','You''re a star!',1000);
INSERT INTO account_upgrade VALUES (6,'Heart','Self care.',2000);

INSERT INTO superpower VALUES (1,3);
INSERT INTO superpower VALUES (2,1);
INSERT INTO superpower VALUES (3,3);

INSERT INTO accessory VALUES (4,'#16D3F0');
INSERT INTO accessory VALUES (5,'#FCBA03');
INSERT INTO accessory VALUES (6,'#FF307C');

INSERT INTO purchase VALUES ('alpha',4,NULL);
INSERT INTO purchase VALUES ('alpha',2,'2020-04-12');
INSERT INTO purchase VALUES ('bravo',1,'2020-04-16');
INSERT INTO purchase VALUES ('charlie',5,NULL);

INSERT INTO post VALUES (1,'alpha','Rant','Why do people do this? I don''t understand. Who does that? I don''t know what I''m talking about!','Main Street','Vancouver','BC','2020-03-12 09:24:31',0,1,0);
INSERT INTO post VALUES (2,'alpha','Granola recipe','Mix oats, puffed rice, nuts, seeds, honey, and coconut oil. Bake at 350F for 40 minuts. Stir in dried fruit.',NULL,'Richmond','BC','2020-02-10 18:21:00',4,0,3);
INSERT INTO post VALUES (3,'bravo','Good boy','My dog chased away a mole today. It would''ve ruined my garden :O',NULL,NULL,NULL,'2020-01-31 12:00:12',1,1,1);
INSERT INTO post VALUES (4,'charlie','I made bread','Took all day but it was worth it!!',NULL,'Burnaby','BC','2020-01-08 13:21:44',5,0,2);
INSERT INTO post VALUES (5,'echo','It''s too hot outside','aaaaaaaaaaaaaa I''m dying',NULL,NULL,NULL,'2019-08-20 15:51:02',0,0,0);

INSERT INTO post_reaction VALUES ('echo',1,-1);
INSERT INTO post_reaction VALUES ('bravo',2,2);
INSERT INTO post_reaction VALUES ('charlie',2,1);
INSERT INTO post_reaction VALUES ('delta',2,1);
INSERT INTO post_reaction VALUES ('delta',3,1);
INSERT INTO post_reaction VALUES ('charlie',3,-1);
INSERT INTO post_reaction VALUES ('alpha',4,3);
INSERT INTO post_reaction VALUES ('bravo',4,2);

INSERT INTO comment VALUES (1,2,'bravo','2020-02-12 16:21:22','My favrite!!!',0,2);
INSERT INTO comment VALUES (2,2,'bravo','2020-02-12 16:22:13','*favorite oops',0,0);
INSERT INTO comment VALUES (3,2,'delta','2020-02-21 20:27:03','So good',1,0);
INSERT INTO comment VALUES (1,3,'charlie','2020-02-03 13:51:02','Floof',0,1);
INSERT INTO comment VALUES (1,4,'delta','2020-02-24 08:12:32','Nice!',0,0);
INSERT INTO comment VALUES (2,4,'echo','2020-03-08 22:11:54','What kind?',3,0);

INSERT INTO comment_reaction VALUES ('charlie',1,2,-1);
INSERT INTO comment_reaction VALUES ('delta',1,2,-1);
INSERT INTO comment_reaction VALUES ('echo',3,2,1);
INSERT INTO comment_reaction VALUES ('alpha',1,3,-1);
INSERT INTO comment_reaction VALUES ('alpha',2,4,1);
INSERT INTO comment_reaction VALUES ('bravo',2,4,1);
INSERT INTO comment_reaction VALUES ('delta',2,4,1);