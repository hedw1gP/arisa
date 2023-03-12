# Arisa

~~Live demo at [arisa.qwp.moe](https://arisa.qwp.moe).~~

## There is a better project called [HoneyWorks-Info](https://github.com/HaniwaWiki/HoneyWorks-Info) which is way better than my homework.

Consider using my referral link to support.

[![DigitalOcean Referral Badge](https://web-platforms.sfo2.cdn.digitaloceanspaces.com/WWW/Badge%201.svg)](https://www.digitalocean.com/?refcode=94b9f74b90ff&utm_campaign=Referral_Invite&utm_medium=Referral_Program&utm_source=badge)

### important message

this is purely for my homework, so my S3 bucket won't update frequently. please don't rely on this project too much. 

### requirements

the project uses MySQL as code says `mysqli_connect`.

use [hedw1gP/hwpm-data](https://github.com/hedw1gP/hwpm-data) to generate required SQL. 

you need to create an account for your SQL server: `arisa`, password is empty. don't forget to set permissions to allow `arisa` access `hwpm` database.

if you want to use your own S3 bucket to store image files, please edit `$source` to your link. 

to disable `.webp` please edit: (I hope you don't waste my S3 transfer quota)

```php
echo "<a href=\"$picture_card_link1.png\"><img src=\"$picture_card_link1.webp\" width='50%' /></a>";
echo "<a href=\"$picture_card_link2.png\"><img src=\"$picture_card_link2.webp\" width='50%' /></a>";
```

to:

```php
echo "<a href=\"$picture_card_link1.png\"><img src=\"$picture_card_link1.png\" width='50%' /></a>";
echo "<a href=\"$picture_card_link2.png\"><img src=\"$picture_card_link2.png\" width='50%' /></a>";
```

### maintenance

each time HoneyWorks Premium Live updates main app version, you need to edit `function getRemoteMasterVersion()`. modify `X-Chikuzen-Client-App-Version` to current version, or the RemoteMasterVersion won't work.

each time HoneyWorks Premium Live updates new card/characters, please use [hedw1gP/hwpm-data](https://github.com/hedw1gP/hwpm-data) to generate new SQL and execute it to `hwpm` database. and don't forget to upload corresponding new image file to your S3. 

### license

uses GPLv3.

### contact me

Discord: hedw1gP#9886

Twitter: @hedw1gP
