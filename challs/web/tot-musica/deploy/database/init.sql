USE red;

CREATE TABLE users (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(64),
    username VARCHAR(64) UNIQUE,
    passwd VARCHAR(32),
    email VARCHAR(32),
    roles INTEGER
);

CREATE TABLE songs (
    id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(64),
    lyric TEXT,
    url VARCHAR(255),
    user_id INTEGER
);

INSERT INTO songs (title, lyric, url, user_id) VALUES 
    ('Tot Musica', '[Intro]
Gah zan tak gah zan tat tat brak
Gah zan tak gah zan tat tat brak

[Verse 1]
Ame utsu kokoro samayou izuko
Karehatezu waku negai to namida
Tokihanatsu shu wo tsumugu kotonoha
Mie keg ok giek giek
Kah phas tezze lah (Woah, hey, woah)
Shi wo mo korogasu sukui no sanka
Motomeraretaru meshia

[Pre-Chorus]
Inori no hazama de madou
Tada umi no nagu mirai wo kou

[Chorus]
Sono gougan burei na doukoku wo
Dasei naki urei ni wa boukyaku wo
Saa konton no jidai ni wa shuushifu wo
Iza muge ni blah, blah, blah!
Mujouken zettai gekkou nara singing the song
Ikansen bari zougon demo singing the song
Uzou muzou no big bang itsukushimi bukaku
Okore tsudoe utae hametsu no uta wo

[Verse 2]
Mie keg ok giek giek
Kah phas tezze lah (Woah, hey, woah)
Chikai tateshi jiyuu te ni shite ouka
Hirefusaretaru mеshia

[Pre-Chorus]
Toubou no hate nozomu kibou
Wasureji no tomoshibi wo matou

[Bridge]
Sono mi ga tsukiru made kanadе yo
Yumemi utsutsu agame yo
Subete wo terashidasu hikari wo
Iza muge ni blah, blah, blah!

[Chorus]
Sono gougan burei na doukoku wo
Zankyou gekirei sura boukyaku wo
Saa konton no jidai ni wa shuushifu wo
Iza muge ni blah, blah, blah!
Mujouken zettai gekkou nara singing the song
Ikansen bari zougon demo singing the song
Uzou muzou no big bang itsukushimi bukaku
Okore tsudoe utae hametsu no uta wo (Woah, oh)


[Outro]
Gah zan tak gah zan tat tat brak
Gah zan tak gah zan tat tat brak', 'https://www.youtube.com/watch?v=V9_ZpqfqHFI', 1);

INSERT INTO songs (title, lyric, url, user_id) VALUES 
    ('New Genesis', '[Intro]
Shinjidai wa kono mirai da
Sekaijuu zenbu kaete shimaeba, kaete shimaeba

[Verse 1]
Jamamono, yanamono nante keshite
Kono yo to metamorufooze shiyouze
Myuujikku, kimi ga okosu majikku

[Verse 2]
Me wo tojireba mirai ga hiraite
Itsumademo owari ga konai you nitte
Kono uta wo utau yo

[Pre-Chorus]
Do you wanna play?
Riaru geemu, girigiri, tsuna watari mitaina senritsu
Mitomenai modorenai wasuretai, yume no naka ni isasete
I wanna be free
Mieruyo shinjidai ga sekai no mukou e
Saa iku yo, new world

[Chorus]
Shinjidai wa kono mirai da
Sekaijuu zenbu kaete shimaeba, kaete shimaeba
Hateshinai ongaku ga motto todoku you ni
Yume wa minai wa, kimi ga hanashita
"Boku wo shinjite"

[Interlude]
Ooh

[Verse 3]
Arеkore iranai mono wa keshite
Riaru wo karafuru ni koе youze
Myuujikku, ima hajimaru raijingu

[Verse 4]
Me wo tsuburi minna de nigeyou yo
Ima yori ii mono wo misete ageru yo
Kono uta wo utaeba

[Pre-Chorus]
Do you wanna play?
Riaru geemu, girigiri, tsuna watari mitaina unmei
Mitomenai modorenai wasuretai, yume no naka ni isasete
I wanna be free
Mieru yo shinjidai ga sekai no mukou e
Saa iku yo, new world

[Chorus]
Shinjitaiwa kono mirai wo
Sekaijuu zenbu kaete shimaeba, kaete shimaeba
Hateshinai ongaku ga motto todoku you ni
Yume wo miseru yo, yume wo miseru yo
Shinjidai da


[Interlude]
Ooh

[Outro]
Shinjidai da', 'https://www.youtube.com/watch?v=1FliVTcX8bQ', 1);




