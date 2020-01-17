-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.11-0ubuntu6 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

-- Dumping structure for table tvz.news
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `active` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  `summary` varchar(500) DEFAULT NULL,
  `description` text,
  `author_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the user who has written the article',
  `approver_id` int(10) unsigned DEFAULT NULL COMMENT 'ID of the user who has approved the article to be published',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author_id_FK` (`author_id`),
  KEY `approver_id_FK` (`approver_id`),
  KEY `active` (`active`),
  CONSTRAINT `approver_id_FK` FOREIGN KEY (`approver_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
  CONSTRAINT `author_id_FK` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

INSERT INTO `news` (`id`, `active`, `title`, `summary`, `description`, `author_id`, `approver_id`, `created`) VALUES
    (1, 1, 'Seminar at the New Bulgarian University', 'New Bulgarian University organizes XX Jubilee Edition of the Master Class of Milcho Leviev and Vicky Almazidou.\r\nIt will take place from 17th to 29th June 2018', '<p>The New Bulgarian University is a Partner for the Study of European Values (full partner). Three years ago, with the support of the leadership of the NBU, Professor Georgy Fotev, EVS National Program Director, created a Center for the Study of European Values. For a second consecutive year, the NBU is hosting a joint university seminar "Contexts of European Values".</p>\r\n<p>In connection with the preparations for the presentation of the data on Bulgaria, which is the President of the EU Council in the first half of 2018, a seminar on &bdquo;How to Debate European Values?" was held. The seminar was attended by associate professor Briana Dimitrova. Director of Alpha research and &nbsp;&nbsp;the most prominent and distinguished analysts, professors at the NBU and at the Sofia University &bdquo;St. Kliment Ohridski&ldquo;: associate professor Antoni Galabov, professor Antoni Todorov, associate professor Haralan Alexandrov, associate professor Ognian Minchev, associate professor Rumyana Kolarova, associate professor Vasil Garnizov, professor Evgeniy Dainov, associate professor Tatyana Bouruzhieva, professor Alexander Kiossev, professor Dimitar Vatsov, professor Hristo Todorov, associate professor Momchil Badzhakov, professor Lyudmil Georgiev and others.</p>\r\n<p>The rector of the NBU, Professor Plamen Bochkov, welcomed the experts and wished them fruitful discussions. Professor Georgy Fotev pointed out that it was necessary to launch a strategic debate on European values in Bulgaria. A solid empirical basis for such a debate could be the vast and unique data from the five waves of EVS. It is expected that by the end of the year all countries of the 5th wave of EVS will complete their research and the data will be published. A conference will be organized in Sofiato discuss the data from the 5th wave of EVS with the participation of researchers from all Balkan countries. The New Bulgarian University is ready to host this event. Prof. Fotev pointed out that it was not self-evident what was meant by &bdquo;European values&ldquo; - whether these were the values functioning in Europe or the values that defined the identity of Europe? Politeism of values (Weber) makes debates on this subject extremely difficult.</p>\r\n<p>Associate Professor Antoniy Galabov made several important distinctions, the first of which was between the values of Europeans and European values. The culture of Europe is value-defined only through the human being, through the individual person and their rights. Europe is where everyone is born equal in dignity and rights; everything else is not Europe, and this is not a geographic issue. Professor Antoni Todorov pointed out that when we talk about &bdquo;European values&ldquo; we should consider a distinction between normative position and expectations or notions of Europe on the one hand, and realities on the other. Associate Professor Tatyana Bouruzhieva defined the purpose of presenting EVS 5th wave data in May 2018 as not just to communicate the results of scientific analysis but to initiate a wide public debate on &bdquo;What kind of Europe do we want?&ldquo; Associate Professor Rumyana Kolarova noted that the European Values Study was a major scientific project whose long-term mission would be to re-establish the links in the academic community, to stimulate inter-university relations, to organize doctoral seminars and other&nbsp; scholarly forums. Professor Alexander Kiossev emphasized the need to make intelligent and understandable distinctions and explanations based on EVS data. As a possible longer-term goal, he identified the link between educational goals and cultural policies. He also insisted on an in-depth analysis of the gap between "Project Europe" and the values practically functioning in the European societies.</p>\r\n<p>Professor Dimitar Vatsov called for a correct presentation of the sociological data, but also stressed the need to send a political message in defense of European values, which, especially in connection with the sharp increase in xenophobia and homophobia among Bulgarians, have been under attack in recent years. Professor Ognian Minchev agreed that defining European values stemmed from the basic recognition of human freedom and dignity. He also questioned whether scholars should speak about them from the position of academic neutrality, or rather that each analyst is associated with certain ideological and political values. Considering the very serious value dynamics in Europe, according to Professor Minchev, it is of utmost importance to put some limits on what it is and what is not a European value, through clearly distancing ourselves from pejorative definitions.</p>\r\n<p>In conclusion, Professor&nbsp; Fotev noted that the goal was not to reach unanimity on the topics discussed, which was hardly possible, but to achieve understanding and dialogue in the Bulgarian society. &bdquo;Our strategic debate on European values &ndash; he said &ndash; needs to be based on a solid empirical basis, on rationalizing the growing complexity of the modern world. Intellectual honesty is a central virtue of scientific activity&nbsp; and we are responsible to the Bulgarian society.&ldquo;</p>\r\n<p style="text-align: right;"><em>Dr. Teodora Karamelska, Assistant Professor at NBU</em></p>', 1, NULL, '2019-05-25 15:58:39'),
    (2, 1, 'Essay contest of Doctoral students', 'Faith and science are not incompatible. Major breakthroughs in modern science have been made by eclesiastics or by believing scientists.', '<p style="text-align: right;"><strong>Organized by:</strong><br />the Learning, Practical and Research Unit &bdquo;Prof. Plamen S. Tzvetkov&ldquo; at <br />the History Department of the New Bulgarian University,<br />under the patronage of the Rector of the NBU Prof. Plamen Bochkov.</p>\r\n<p>Faith and science are not incompatible. Major breakthroughs in modern science have been made by eclesiastics or by believing scientists. Both faith and science have as ultimate goal achieving of the truth in their own way. Faith postulates ethical principles that are valid for everyday life as well as for scientific research.</p>\r\n<p>In view of the importance of the relation Faith - Science - Authority, the LPRU &bdquo;Prof. Plamen Tzvetkov&ldquo;offers doctoral students and postdoctoral researchers to present their views and reflections on that, in an essay contest dedicated to the memory of its patron who was not only a prominent scholar but also a deeply believing person.</p>\r\n<p><strong>The deadline for submission of the essays (ca. 10-15 pages), is October 30, 2019.</strong></p>\r\n<p><strong>The essays should be sent to:</strong> <a href="mailto:rzaimova@abv.bg">rzaimova@abv.bg</a>; <a href="mailto:genov@nbu.bg">genov@nbu.bg</a>; <a href="mailto:mmetodiev@nbu.bg">mmetodiev@nbu.bg</a>.</p>\r\n<p>The prizes for the ranked participants will be respectively: 500, 300 and 200 BGN, and we will look for opportunity of<br />publication of the ranked essays in the NBU&rsquo;s own publications and in prestigious thematic journals.</p>\r\n<p>The ranked essays will be announced, and the prizes and certificates for participation will be given on November 15, 2019, in Hall 214 Corpus I &bdquo;Prof. Plamen Tzvetkov&ldquo;.</p>', 2, NULL, '2019-06-12 18:20:24'),
    (3, 1, 'International Raina Kabaivanska Masterclass at Sofia Opera House', 'In her Master Class Raina Kabaivanska will work on improving the participants vocal technique and interpretation of pieces chosen by themselves.', '<ul>\r\n<li><strong>Application deadlines:</strong> September&nbsp;6</li>\r\n<li><strong>Audition:</strong> September 10</li>\r\n<li><strong>Master class:</strong> 11-28 September</li>\r\n<li><strong>Gala:</strong> September 29 &ndash; 7 p.m., Sofia Opera and Ballet House</li>\r\n<li><a href="http://rainakabaivanska.net/en/master-classes/application-form" target="_blank" rel="noopener">Registration</a></li>\r\n</ul>\r\n<p>In her Master Class Raina Kabaivanska will work on improving the participants vocal technique and interpretation of pieces chosen by themselves.</p>\r\n<p>Eligible Applicants: professional musicians or current students in Academy / Higher Institution of Music up to 32 years old. Each applicant should choose and prepare five arias for the audition.</p>\r\n<p>The most successful participants in the Master class will receive scholarships from the Raina Kabaivanska Fundat New Bulgarian University for training in Italy.</p>\r\n<p>During the audition for the following Master Class Raina Kabaivanska will also choose young talented opera singers for leading roles in the opera "Don Giovanni" by Mozart. The performances will be presented in season 2019/2020 at Sofia Opera and Ballet.</p>', 1, NULL, '2019-08-06 09:12:31'),
    (4, 1, 'Collaboration with the University of Craiova', 'On October 16th 2017 the Vice-Rector for Scientific Research of the University of Craiova /Romania/ Professor Radu Constantinescu visited NBU.', '<p>On <strong>October 16th 2017</strong> the Vice-Rector for Scientific Research of the University of Craiova /Romania/ Professor Radu Constantinescu visited NBU.</p>\r\n<p>During his visit Professor Constantinescu met with NBU\'s Vice-Rector Dr. Kiril Avramov, the Director of the Research Centre "<em>Institute for Advanced Physical Studies</em>" (RC IAPS) Associate Professor Dr. Tzveta Apostolova &nbsp;and &nbsp;IAPS &nbsp;members Professor Vladimir Gerdjikov and Dr. Stoyan Mishev.</p>\r\n<p>In the course of the discussions particular opportunities for collaborative projects were identified in archeology, social sciences, cultural heritage preservation, mathematics, biology, physics and computer science. At the end of the meeting a framework agreement for cooperation in research and education was signed by the two vice-rectors.&nbsp;<br />&nbsp;<br />The collaboration between members of the RC IAPS and the University of Craiova has been established informally since &nbsp;the founding of the Research Center. In the context of this cooperation several papers in the soliton theory were published.<br />&nbsp;<br />Dr. Avramov and Professor Constantinescu agreed on establishing a detailed work plan in the near future as well as to facilitate the dialogue among the researchers and lecturers from both institutions.</p>', 1, NULL, '2019-10-19 12:00:14'),
    (5, 1, 'Improvisation in all styles at New Bulgarian University', 'New Bulgarian University organizes XX Jubilee Edition of the Master Class of Milcho Leviev and Vicky Almazidou, it will take place from 17th to 29th June 2018', '<p><strong>Milcho Leviev</strong> is holder of "Doctor Honoris Causa" at <strong>New Bulgarian University</strong> (1998). Winner of numerous awards, including the Order Stara Planina(1997), Order &bdquo;Saints Cyril And Methodius&ldquo; (2008). He is a world-famous composer, arranger, performer (pianist, keyboardist and conductor), classical and jazz music teacher. He has participated in joint projects and concerts with world-famous musicians such as Al Jarreau, Billy Cobham, George Duke, Don Ellis, Dave Holland, Art Pepper and others. He is awarded the Grammy Award with Al Jarreau for album &ldquo;Breakin \'Away&rdquo; and has nomination for&nbsp; &ldquo;Grammy&rdquo; for album &ldquo;Manhattan Transfer&rdquo;.</p>\r\n<p>Vicky Almazidou has participated in tours with world-famous musicians such as Peter Erskine, Glen Ferris, Airto Moreira and in concerts with many world stars as &nbsp;Billy Cobham, Aaron Goldberg, David Murray. She has created the first class of jazz singing at Contemporary Conservatory in Thessaloniki. Along with her pedagogical work, Vicky Almazidou participated in a number of festivals and sang in the most prestigious jazz clubs in Greece and Bulgaria.</p>\r\n<p>This year a special guest lecturer in the Master class will be the famous trombonist Velislav Stoyanov. He is grandson of the great Bulgarian composer Yosif Tsankov and one of the most popular musicians for concerts and studio recordings. In addition to work with the best Bulgarian musicians, the trombonist had joint projects with persons from the world music stage such as Dephazz, Mezzoforte, Dave Weckl, Jimmy Bosch, Herman Olivera, Max Moya, Peter Herbolzheimer, Poogie Bell, Frankie Morales, Charles Mack, Jiggs Wigham. In 2008, Velislav Stoyanov along with trumpeter Mihail Yossifov, creates conceptual unification of brass musicians Brass Association, which in last few years actively works for the promotion and revival of brass music in Bulgaria, and is an example and support for many young Bulgarian brass musicians.</p>\r\n<p>The Master class will finish with a gala concert at Sofia Life Club on 29th&nbsp; June, 2018 at 21:00, with participation of the best students in the master class together with their teachers. During the concert, Milcho Leviev and Vicky Almazidou will give scholarships from their fund NBU for young musicians.</p>\r\n<p>Over the years, a number of world famous musicians have joined the Master class, including Aron Goldberg &ndash; piano (USA), Billy Cobham, drums (USA), David Murray - saxophone (USA), Craig Bailey - saxophone (USA), Francisco Mela - percussion (USA), Chico Freeman - saxophone (USA), Aaron Goldberg - piano (USA), Marc Halbheer - drums (Switzerland), prof. Glenn Ferris - Trombone (France), and the famous Bulgarian musicians Petar Slavov - contrabass, Stoyan Yankulov -Stundji - drums.</p>', 2, 1, '2019-11-01 16:17:12'),
    (6, 0, 'Lorem ipsum neque porro quisquam est qui dolorem ipsum', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '<p><strong>Where does it come from?</strong></p>\r\n<p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>\r\n<p>The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.</p>\r\n<p>&nbsp;</p>\r\n<p><strong>Why do we use it?</strong></p>\r\n<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>', 2, NULL, '2020-01-17 16:34:14');

-- Dumping structure for trigger tvz.news_bi
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `news_bi` BEFORE INSERT ON `news` FOR EACH ROW BEGIN
    SET NEW.created = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;



-- * * * * * * * * * * * * * * * --
--        NEWS IMAGES DUMP       --


-- Dumping structure for table tvz.news_images
CREATE TABLE IF NOT EXISTS `news_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `news_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'ID of the news article that the image belongs to',
  `file_name` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `newsFK` (`news_id`),
  CONSTRAINT `newsFK` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;


INSERT INTO `news_images` (`id`, `news_id`, `file_name`, `created`) VALUES
    (1, 4, 'craiova.jpg', '2020-01-17 16:20:04'),
    (2, 5, 'img-0614_678x410_crop_478b24840a.jpg', '2020-01-17 16:21:03'),
    (3, 5, 'img-0616_678x410_crop_478b24840a.jpg', '2020-01-17 16:21:09'),
    (4, 3, 'rk-master-class.jpg', '2020-01-17 16:26:30'),
    (5, 1, '1_678x410_crop_478b24840a.jpg', '2020-01-17 16:28:11'),
    (6, 1, '6.jpg', '2020-01-17 16:29:19'),
    (7, 1, '7.jpg', '2020-01-17 16:30:05'),
    (8, 1, '8.jpg', '2020-01-17 16:30:11'),
    (9, 2, 'plamen-cvetkov_678x410_crop_478b24840a.jpg', '2020-01-17 16:32:12'),
    (10, 6, 'monika_pure_green_grass.jpg', '2020-01-17 16:34:14');

-- Dumping structure for trigger tvz.news_images_before_insert
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `news_images_before_insert` BEFORE INSERT ON `news_images` FOR EACH ROW BEGIN
    SET NEW.created = NOW();
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;
