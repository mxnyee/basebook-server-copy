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

INSERT INTO permissions VALUE ('personal',FALSE,FALSE,FALSE,FALSE);
INSERT INTO permissions VALUE ('premium',TRUE,FALSE,FALSE,FALSE);
INSERT INTO permissions VALUE ('admin',TRUE,TRUE,TRUE,TRUE);

INSERT INTO account VALUES ('alpha','alpha@example.com','amber',200,'personal',NULL);
INSERT INTO account VALUES ('bravo','bravo@example.com','bronze',153,'personal',NULL);
INSERT INTO account VALUES ('charlie','charlie@example.com','coral',2000,'personal',NULL);
INSERT INTO account VALUES ('delta','delta@example.com','denim',0,'personal',NULL);
INSERT INTO account VALUES ('echo','echo@example.com','emerald',0,'personal',NULL);

INSERT INTO profile_page VALUES ('/alpha','alpha',NULL,'Alpha','Number one!','Vancouver','BC',0,0,0);
INSERT INTO profile_page VALUES ('/bravo','bravo',NULL,'Bravo','The second banana.','Victoria','BC',0,0,0);
INSERT INTO profile_page VALUES ('/charlie','charlie',NULL,'Charlie',NULL,'Vancouver','OR',0,0,0);
INSERT INTO profile_page VALUES ('/delta','delta',NULL,NULL,'Good ol triangle.',NULL,'BC',0,0,0);
INSERT INTO profile_page VALUES ('/echo','echo',NULL,NULL,NULL,NULL,NULL,0,0,0);

UPDATE account SET profile_page_url = '/alpha' WHERE username = 'alpha';
UPDATE account SET profile_page_url = '/bravo' WHERE username = 'bravo';
UPDATE account SET profile_page_url = '/charlie' WHERE username = 'charlie';
UPDATE account SET profile_page_url = '/delta' WHERE username = 'delta';
UPDATE account SET profile_page_url = '/echo' WHERE username = 'echo';

INSERT INTO follow VALUES ('charlie','alpha');
INSERT INTO follow VALUES ('charlie','bravo');
INSERT INTO follow VALUES ('delta','alpha');
INSERT INTO follow VALUES ('delta','bravo');
INSERT INTO follow VALUES ('delta','charlie');
INSERT INTO follow VALUES ('delta','echo');
INSERT INTO follow VALUES ('echo','alpha');
INSERT INTO follow VALUES ('echo','bravo');
INSERT INTO follow VALUES ('echo','charlie');
INSERT INTO follow VALUES ('echo','delta');

INSERT INTO account_upgrade VALUES ('double like',200);
INSERT INTO account_upgrade VALUES ('triple like',300);
INSERT INTO account_upgrade VALUES ('double dislike',200);
INSERT INTO account_upgrade VALUES ('badge',500);
INSERT INTO account_upgrade VALUES ('star',1000);
INSERT INTO account_upgrade VALUES ('heart',2000);

INSERT INTO superpower VALUES ('double like',3);
INSERT INTO superpower VALUES ('triple like',3);
INSERT INTO superpower VALUES ('double dislike',1);

INSERT INTO accessory VALUES ('badge','#16D3F0');
INSERT INTO accessory VALUES ('star','#FCBA03');
INSERT INTO accessory VALUES ('heart','#FF307C');

INSERT INTO purchase VALUES ('alpha','badge',1,NULL);
INSERT INTO purchase VALUES ('alpha','triple like',1,'2020-04-12');
INSERT INTO purchase VALUES ('bravo','double like',2,'2020-04-16');
INSERT INTO purchase VALUES ('charlie','star',3,NULL);

INSERT INTO hashtag VALUES ('vancouver');
INSERT INTO hashtag VALUES ('summer');
INSERT INTO hashtag VALUES ('canucks');
INSERT INTO hashtag VALUES ('food');
INSERT INTO hashtag VALUES ('coffee');
INSERT INTO hashtag VALUES ('baking');
INSERT INTO hashtag VALUES ('cat');

INSERT INTO post VALUES ('0000000000000000','alpha','Rant','2020-03-12 09:24:31',94,15,2,'Main Street','Vancouver','BC');
INSERT INTO post VALUES ('0000000000000001','alpha','Granola recipe','2020-02-10 18:21:00',105,3,12,NULL,'Richmond','BC');
INSERT INTO post VALUES ('0000000000000002','bravo','Good boy','2020-01-31 12:00:12',120,0,0,NULL,NULL,NULL);
INSERT INTO post VALUES ('0000000000000003','charlie','I made bread','2020-01-08 13:21:44',5,0,0,NULL,'Burnaby','BC');
INSERT INTO post VALUES ('0000000000000004','echo','It''s too hot outside','2019-08-20 15:51:02',0,0,0,NULL,NULL,NULL);

INSERT INTO text_post VALUES ('0000000000000000','Why do people do this? What even is that? I don'' understand. Why doesn''t anything make sense? Who does that? No one listens to me. I don''t know what I''m talking about!');
INSERT INTO text_post VALUES ('0000000000000001','2 cups rolled oats' + CHAR(13) + CHAR(10) + '2 cups puffed rice' + CHAR(13) + CHAR(10) + '1 cup chopped nuts'  + CHAR(13) + CHAR(10) + '1/2 cup honey'  + CHAR(13) + CHAR(10) + '1/2 cup coconut oil'  + CHAR(13) + CHAR(10) + 'Bake at 350F for 40 minutes'  + CHAR(13) + CHAR(10) + 'Stir in 2 cups dried fruit');
INSERT INTO text_post VALUES ('0000000000000004','aaaaaaaaaaaaaa I''m dying');

INSERT INTO photo_post VALUES ('0000000000000002','/picture-of-cat.jpg');
INSERT INTO photo_post VALUES ('0000000000000003','/sourdough-bread.jpg');

INSERT INTO post_hashtag VALUES ('0000000000000001','food');
INSERT INTO post_hashtag VALUES ('0000000000000001','summer');
INSERT INTO post_hashtag VALUES ('0000000000000002','cat');
INSERT INTO post_hashtag VALUES ('0000000000000003','food');
INSERT INTO post_hashtag VALUES ('0000000000000003','baking');
INSERT INTO post_hashtag VALUES ('0000000000000004','vancouver');
INSERT INTO post_hashtag VALUES ('0000000000000004','summer');

INSERT INTO post_reaction VALUES ('echo','0000000000000000',-1,'2020-03-01 18:12:30');
INSERT INTO post_reaction VALUES ('bravo','0000000000000001',1,'2020-02-23 12:22:00');
INSERT INTO post_reaction VALUES ('charlie','0000000000000001',1,'2020-03-01 18:12:30');
INSERT INTO post_reaction VALUES ('delta','0000000000000002',1,'2020-04-04 09:55:33');
INSERT INTO post_reaction VALUES ('echo','0000000000000003',2,'2020-04-14 10:01:54');

INSERT INTO comment VALUES ('0000','0000000000000001','bravo','2020-01-23 16:21:22','My favrite!!!',0,2);
INSERT INTO comment VALUES ('0001','0000000000000001','bravo','2020-01-23 16:22:13','*favorite oops',0,0);
INSERT INTO comment VALUES ('0002','0000000000000001','charlie','2020-02-01 20:27:03','So good',10,1);
INSERT INTO comment VALUES ('0000','0000000000000002','delta','2020-02-11 13:51:02','Floof',20,5);

INSERT INTO comment_reaction VALUES ('charlie','0002','0000000000000001',-1,'2020-01-11 14:02:51');
INSERT INTO comment_reaction VALUES ('delta','0002','0000000000000001',1,'2020-02-06 01:10:20');
INSERT INTO comment_reaction VALUES ('echo','0000','0000000000000002',1,'2020-03-01 18:12:30');