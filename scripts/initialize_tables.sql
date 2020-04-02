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

INSERT INTO account_upgrade VALUES ('000','Double Like','Gives 2 coins every time you like a post or comment.',200);
INSERT INTO account_upgrade VALUES ('001','Triple Like','Gives 3 coins every time you like a post or comment.',300);
INSERT INTO account_upgrade VALUES ('002','Double Dislike','For when you''re extra angry.',200);
INSERT INTO account_upgrade VALUES ('003','Badge','A shiny badge to put on your profile.',500);
INSERT INTO account_upgrade VALUES ('004','Star','You''re a star!',1000);
INSERT INTO account_upgrade VALUES ('005','Heart','Self care.',2000);

INSERT INTO superpower VALUES ('000',3);
INSERT INTO superpower VALUES ('001',1);
INSERT INTO superpower VALUES ('002',3);

INSERT INTO accessory VALUES ('003','#16D3F0');
INSERT INTO accessory VALUES ('004','#FCBA03');
INSERT INTO accessory VALUES ('005','#FF307C');

INSERT INTO purchase VALUES ('alpha','003',NULL);
INSERT INTO purchase VALUES ('alpha','001','2020-04-12');
INSERT INTO purchase VALUES ('bravo','000','2020-04-16');
INSERT INTO purchase VALUES ('charlie','004',NULL);

INSERT INTO post VALUES ('00000000','alpha','Rant','Why do people do this? I don''t understand. Who does that? I don''t know what I''m talking about!','Main Street','Vancouver','BC','2020-03-12 09:24:31',0,1,0);
INSERT INTO post VALUES ('00000001','alpha','Granola recipe','Mix oats, puffed rice, nuts, seeds, honey, and coconut oil. Bake at 350F for 40 minuts. Stir in dried fruit.',NULL,'Richmond','BC','2020-02-10 18:21:00',4,0,3);
INSERT INTO post VALUES ('00000002','bravo','Good boy','My dog chased away a mole today. It would''ve ruined my garden :O',NULL,NULL,NULL,'2020-01-31 12:00:12',1,1,1);
INSERT INTO post VALUES ('00000003','charlie','I made bread','Took all day but it was worth it!!',NULL,'Burnaby','BC','2020-01-08 13:21:44',5,0,2);
INSERT INTO post VALUES ('00000004','echo','It''s too hot outside','aaaaaaaaaaaaaa I''m dying',NULL,NULL,NULL,'2019-08-20 15:51:02',0,0,0);

INSERT INTO post_reaction VALUES ('echo','00000000',-1);
INSERT INTO post_reaction VALUES ('bravo','00000001',2);
INSERT INTO post_reaction VALUES ('charlie','00000001',1);
INSERT INTO post_reaction VALUES ('delta','00000001',1);
INSERT INTO post_reaction VALUES ('delta','00000002',1);
INSERT INTO post_reaction VALUES ('charlie','00000002',-1);
INSERT INTO post_reaction VALUES ('alpha','00000003',3);
INSERT INTO post_reaction VALUES ('bravo','00000003',2);

INSERT INTO comment VALUES ('0000','00000001','bravo','2020-02-12 16:21:22','My favrite!!!',0,2);
INSERT INTO comment VALUES ('0001','00000001','bravo','2020-02-12 16:22:13','*favorite oops',0,0);
INSERT INTO comment VALUES ('0002','00000001','delta','2020-02-21 20:27:03','So good',1,0);
INSERT INTO comment VALUES ('0000','00000002','charlie','2020-02-03 13:51:02','Floof',0,1);
INSERT INTO comment VALUES ('0000','00000003','delta','2020-02-24 08:12:32','Nice!',0,0);
INSERT INTO comment VALUES ('0001','00000003','echo','2020-03-08 22:11:54','What kind?',3,0);

INSERT INTO comment_reaction VALUES ('charlie','0000','00000001',-1);
INSERT INTO comment_reaction VALUES ('delta','0000','00000001',-1);
INSERT INTO comment_reaction VALUES ('echo','0002','00000001',1);
INSERT INTO comment_reaction VALUES ('alpha','0000','00000002',-1);
INSERT INTO comment_reaction VALUES ('alpha','0001','00000003',1);
INSERT INTO comment_reaction VALUES ('bravo','0001','00000003',1);
INSERT INTO comment_reaction VALUES ('delta','0001','00000003',1);