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

INSERT INTO account VALUES ('000','aaa','alpha','alpha@example.com','amber',200,'personal',NULL);
INSERT INTO account VALUES ('001','bbb','bravo','bravo@example.com','bronze',153,'personal',NULL);
INSERT INTO account VALUES ('002','ccc','charlie','charlie@example.com','coral',2000,'personal',NULL);
INSERT INTO account VALUES ('003','ddd','delta','delta@example.com','denim',0,'personal',NULL);
INSERT INTO account VALUES ('004','eee','echo','echo@example.com','emerald',0,'personal',NULL);

INSERT INTO profile_page VALUES ('/alpha','000',NULL,'Alpha','Number one!','Vancouver','BC',0,0,0);
INSERT INTO profile_page VALUES ('/bravo','001',NULL,'Bravo','The second banana.','Victoria','BC',0,0,0);
INSERT INTO profile_page VALUES ('/charlie','002',NULL,'Charlie',NULL,'Vancouver','OR',0,0,0);
INSERT INTO profile_page VALUES ('/delta','003',NULL,NULL,'Good ol triangle.',NULL,'BC',0,0,0);
INSERT INTO profile_page VALUES ('/echo','004',NULL,NULL,NULL,NULL,NULL,0,0,0);

UPDATE account SET profile_page_url = '/alpha' WHERE username = 'alpha';
UPDATE account SET profile_page_url = '/bravo' WHERE username = 'bravo';
UPDATE account SET profile_page_url = '/charlie' WHERE username = 'charlie';
UPDATE account SET profile_page_url = '/delta' WHERE username = 'delta';
UPDATE account SET profile_page_url = '/echo' WHERE username = 'echo';

INSERT INTO follow VALUES ('002','000');
INSERT INTO follow VALUES ('002','001');
INSERT INTO follow VALUES ('003','000');
INSERT INTO follow VALUES ('003','001');
INSERT INTO follow VALUES ('003','002');
INSERT INTO follow VALUES ('003','004');
INSERT INTO follow VALUES ('004','000');
INSERT INTO follow VALUES ('004','001');
INSERT INTO follow VALUES ('004','002');
INSERT INTO follow VALUES ('004','003');

INSERT INTO account_upgrade VALUES ('0','double like',200);
INSERT INTO account_upgrade VALUES ('1','triple like',300);
INSERT INTO account_upgrade VALUES ('2','double dislike',200);
INSERT INTO account_upgrade VALUES ('3','badge',500);
INSERT INTO account_upgrade VALUES ('4','star',1000);
INSERT INTO account_upgrade VALUES ('5','heart',2000);

INSERT INTO superpower VALUES ('0',3);
INSERT INTO superpower VALUES ('1',3);
INSERT INTO superpower VALUES ('2',1);

INSERT INTO accessory VALUES ('3','#16D3F0');
INSERT INTO accessory VALUES ('4','#FCBA03');
INSERT INTO accessory VALUES ('5','#FF307C');

INSERT INTO purchase VALUES ('000','3',1,NULL);
INSERT INTO purchase VALUES ('000','1',1,'2020-04-12');
INSERT INTO purchase VALUES ('001','0',2,'2020-04-16');
INSERT INTO purchase VALUES ('002','4',3,NULL);

INSERT INTO hashtag VALUES ('vancouver');
INSERT INTO hashtag VALUES ('summer');
INSERT INTO hashtag VALUES ('canucks');
INSERT INTO hashtag VALUES ('food');
INSERT INTO hashtag VALUES ('coffee');
INSERT INTO hashtag VALUES ('baking');
INSERT INTO hashtag VALUES ('cat');

INSERT INTO post VALUES ('0000000000000000','000','Rant','2020-03-12 09:24:31',94,15,2,'Main Street','Vancouver','BC');
INSERT INTO post VALUES ('0000000000000001','000','Granola recipe','2020-02-10 18:21:00',105,3,12,NULL,'Richmond','BC');
INSERT INTO post VALUES ('0000000000000002','001','Good boy','2020-01-31 12:00:12',120,0,0,NULL,NULL,NULL);
INSERT INTO post VALUES ('0000000000000003','002','I made bread','2020-01-08 13:21:44',5,0,0,NULL,'Burnaby','BC');
INSERT INTO post VALUES ('0000000000000004','004','It''s too hot outside','2019-08-20 15:51:02',0,0,0,NULL,NULL,NULL);

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

INSERT INTO post_reaction VALUES ('004','0000000000000000',-1,'2020-03-01 18:12:30');
INSERT INTO post_reaction VALUES ('001','0000000000000001',1,'2020-02-23 12:22:00');
INSERT INTO post_reaction VALUES ('002','0000000000000001',1,'2020-03-01 18:12:30');
INSERT INTO post_reaction VALUES ('003','0000000000000002',1,'2020-04-04 09:55:33');
INSERT INTO post_reaction VALUES ('004','0000000000000003',2,'2020-04-14 10:01:54');

INSERT INTO comment VALUES ('0000','0000000000000001','001','2020-01-23 16:21:22','My favrite!!!',0,2);
INSERT INTO comment VALUES ('0001','0000000000000001','001','2020-01-23 16:22:13','*favorite oops',0,0);
INSERT INTO comment VALUES ('0002','0000000000000001','002','2020-02-01 20:27:03','So good',10,1);
INSERT INTO comment VALUES ('0000','0000000000000002','003','2020-02-11 13:51:02','Floof',20,5);

INSERT INTO comment_reaction VALUES ('002','0002','0000000000000001',-1,'2020-01-11 14:02:51');
INSERT INTO comment_reaction VALUES ('003','0002','0000000000000001',1,'2020-02-06 01:10:20');
INSERT INTO comment_reaction VALUES ('004','0000','0000000000000002',1,'2020-03-01 18:12:30');