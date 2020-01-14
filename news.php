<?php
include ('layout/header.php');
if (!isset($_GET['detail'])):
# COMMON NEWS PAGE
?>

<h1>News</h1>

<div class="news-section">
    <a href="index.php?page=news&detail=true"><img src="images/news/news5.jpg" alt="Milcho Leviev" title="Milcho Leviev" /></a>
    <a href="index.php?page=news&detail=true"><h2>Improvisation in all styles at New Bulgarian University</h2></a>
    <p>
        Milcho Leviev is holder of "Doctor Honoris Causa" at New Bulgarian University (1998). Winner of numerous awards, including the Order Stara Planina(1997), Order „Saints Cyril And Methodius“ (2008).
    </p>
    <span class="date"><time datetime="2019-11-01">Published: 01 November 2019</time></span>
    <a href="index.php?page=news&detail=true">Read more &raquo;</a>
</div>

<div class="news-section">
    <a href="index.php?page=news&detail=true"><img src="images/news/news4.jpg" alt="Craiova" title="Craiova" /></a>
    <a href="index.php?page=news&detail=true"><h2>Collaboration with the University of Craiova</h2></a>
    <p>
        On October 16th 2017 the Vice-Rector for Scientific Research of the University of Craiova /Romania/ Professor Radu Constantinescu visited NBU.
    </p>
    <span class="date"><time datetime="2019-10-19">Published: 19 October 2019</time></span>
    <a href="index.php?page=news&detail=true">Read more &raquo;</a>
</div>

<div class="news-section">
    <a href="index.php?page=news&detail=true"><img src="images/news/news3.jpg" alt="Raina Kabaivanska" title="Raina Kabaivanska" /></a>
    <a href="index.php?page=news&detail=true"><h2>International Raina Kabaivanska Masterclass at Sofia Opera House</h2></a>
    <p>
        In her Master Class Raina Kabaivanska will work on improving the participants vocal technique and interpretation of pieces chosen by themselves.
    </p>
    <span class="date"><time datetime="2019-08-06">Published: 06 August 2019</time></span>
    <a href="index.php?page=news&detail=true">Read more &raquo;</a>
</div>

<div class="news-section">
    <a href="index.php?page=news&detail=true"><img src="images/news/news2.jpg" alt="Plamen Tzvetkov" title="Plamen Tzvetkov" /></a>
    <a href="index.php?page=news&detail=true"><h2>Essay contest of Doctoral students</h2></a>
    <p>
        Faith and science are not incompatible. Major breakthroughs in modern science have been made by eclesiastics or by believing scientists.
    </p>
    <span class="date"><time datetime="2019-06-12">Published: 12 June 2019</time></span>
    <a href="index.php?page=news&detail=true">Read more &raquo;</a>
</div>

<div class="news-section">
    <a href="index.php?page=news&detail=true"><img src="images/news/news1.jpg" alt="Prof. Fotev and Assoc. Prof. Boryana Dimitrova" title="Prof. Fotev and Assoc. Prof. Boryana Dimitrova" /></a>
    <a href="index.php?page=news&detail=true"><h2>Seminar at the New Bulgarian University</h2></a>
    <p>
        The New Bulgarian University is a Partner for the Study of European Values (full partner) and is hosting a joint university seminar "Contexts of European Values".
    </p>
    <span class="date"><time datetime="2019-05-25">Published: 25 May 2019</time></span>
    <a href="index.php?page=news&detail=true">Read more &raquo;</a>
</div>

<?php
else:
#DETAILED NEWS PAGE
?>

<article>
    <h1>Improvisation in all styles at New Bulgarian University</h1>
    <div class="date"><time datetime="2019-11-01">Published on Friday, 01 November 2019, 17:46</time></div>
    <div class="description">
        <p>New Bulgarian University organizes XX Jubilee Edition of the Master Class of Milcho Leviev and Vicky Almazidou, it will take place from 17th to 29th June 2018</p>
    </div>

    <section class="gallery">
        <figure>
            <img src="images/nbu-main-entrance.jpg" alt="NBU main entrance" />
            <figcaption>The main entrance to the university</figcaption>
        </figure> 

        <div class="thumbs">
            <img src="images/nbu-main-entrance.jpg" title="Prof. Fotev and Assoc. Prof. Boryana Dimitrova" class="active" />
            <img src="images/news/news2.jpg" title="Plamen Tzvetkov" />
            <img src="images/news/news3.jpg" title="Raina Kabaivanska" />
            <img src="images/news/news4.jpg" title="Craiova" />
        </div>
    </section>
    <p>Vicky Almazidou has participated in tours with world-famous musicians such as Peter Erskine, Glen Ferris, Airto Moreira and in concerts with many world stars as  Billy Cobham, Aaron Goldberg, David Murray. She has created the first class of jazz singing at Contemporary Conservatory in Thessaloniki. Along with her pedagogical work, Vicky Almazidou participated in a number of festivals and sang in the most prestigious jazz clubs in Greece and Bulgaria.</p>

    <p>This year a special guest lecturer in the Master class will be the famous trombonist Velislav Stoyanov. He is grandson of the great Bulgarian composer Yosif Tsankov and one of the most popular musicians for concerts and studio recordings.</p>

    <p>In addition to work with the best Bulgarian musicians, the trombonist had joint projects with persons from the world music stage such as Dephazz, Mezzoforte, Dave Weckl, Jimmy Bosch, Herman Olivera, Max Moya, Peter Herbolzheimer, Poogie Bell, Frankie Morales, Charles Mack, Jiggs Wigham. In 2008, Velislav Stoyanov along with trumpeter Mihail Yossifov, creates conceptual unification of brass musicians Brass Association, which in last few years actively works for the promotion and revival of brass music in Bulgaria, and is an example and support for many young Bulgarian brass musicians.</p>

    <p>The Master class will finish with a gala concert at Sofia Life Club on 29th  June, 2018 at 21:00, with participation of the best students in the master class together with their teachers. During the concert, Milcho Leviev and Vicky Almazidou will give scholarships from their fund NBU for young musicians.</p>

    <p>Over the years, a number of world famous musicians have joined the Master class, including Aron Goldberg – piano (USA), Billy Cobham, drums (USA), David Murray - saxophone (USA), Craig Bailey - saxophone (USA), Francisco Mela - percussion (USA), Chico Freeman - saxophone (USA), Aaron Goldberg - piano (USA), Marc Halbheer - drums (Switzerland), prof. Glenn Ferris - Trombone (France), and the famous Bulgarian musicians Petar Slavov - contrabass, Stoyan Yankulov -Stundji - drums.</p>

    <em><a href="news.html">&laquo; Back to all news</a></em>

</article>
<?php
endif;
include ('layout/footer.php');
?>